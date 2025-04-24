<script setup>
import { onMounted, ref, watch } from 'vue';

const props = defineProps({
  message: {
    type: Object,
    default:()=>({
      id: null,
      title: '',
      body: '',
      validated: false
    })
  },
  extensions: {
    type: Array,
    default: ()=>[]
  },
  instance_id: {
    type: String,
    default: null
  },
  access_token: '',
  phone: '',
  method: ''
})
const activeStep = ref(1)
const steps = ref([
  { index: 1, title: 'Destinatarios', enabled: 1 },
  { index: 2, title: 'Redactar', enabled: 0 },
  { index: 3, title: 'Confirmar', enabled: 0 },
])

const recepients = ref([])
watch(recepients, () => steps.value[1].enabled = recepients.value.length)

const message = ref({...props.message})
watch(message, () => steps.value[2].enabled = message.value.id, {deep:true})

const instance_id = ref(props.instance_id)
const storing = ref(false)

function storeMessage(file){
  storing.value = true
  let data = new FormData()
  data.append('title',     message.value.title)
  data.append('body',      message.value.body)
  recepients.value.forEach((number,i) => data.append(`receivers[${i}]`, number))
  if( file ){ data.append('file', file)}

  axios.post('/batch-messages', data)
  .then(response => {
    message.value.id = response.data.data.id
    enableStep(3)
  })
  .catch(err => console.log(err.response))
  .finally(()=>storing.value = false)
}

function authenticate(data){
  axios.post(`/batch-messages/authenticate`, data)
  .then(response => instance_id.value = data.instance_id)
  .catch(err => console.log(err.response))
}

function enableStep(stepNumber){
  steps.value = steps.value.map((step,i) => {
    step.enabled = (i+1 == stepNumber ) ? true : false
    return step;
  })
  activeStep.value = stepNumber
}

onMounted(() => {
  console.log(process.env.MIX_SOCKET_BASE_URL)
  if( props.message.id ){
    enableStep(3)
  }
})
</script>

<template>
  <div class="col-lg-4 mx-auto">
    <Multipaso v-model="activeStep" :steps="steps">
      <template v-if="activeStep == 1">
        <Recepients
          v-if="recepients"
          v-model="recepients"
          :extensions="extensions">
        </Recepients>
        <div class="d-flex">
          <button
            @click="activeStep=2"
            :class="{ disabled: !steps[1].enabled }"
            class="btn btn-primary ms-auto">
            Continuar
          </button>
        </div>
      </template>
      <template v-if="activeStep == 2">
        <MessageForm
          @storeMessage="storeMessage"
          v-model="message">
        </MessageForm>
        <div class="d-flex">
          <!-- <button
            @click="activeStep=3"
            class="btn btn-primary ms-auto"
            :class="{ disabled: !message.validated }" >
            Continuar
          </button> -->
        </div>
      </template>
      <template v-if="activeStep == 3">
        <Authenticate
          @authenticated="authenticate"
          :method="method"
          :instance_id="instance_id"
          :phone="phone"
          :access_token="access_token">
        </Authenticate>
      </template>
    </Multipaso>
  </div>
</template>

<style>
body {
  background: #F3F6FC;
}
</style>