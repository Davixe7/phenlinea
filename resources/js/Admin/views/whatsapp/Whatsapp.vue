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
  }
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
    enableStep(2)
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
        <TemplatesList :recepients="recepients" @success="enableStep(3)"/>
        <!-- <MessageForm
          @storeMessage="storeMessage"
          v-model="message">
        </MessageForm> -->
      </template>
      <template v-if="activeStep == 3">
        <Confirm>
        </Confirm>
      </template>
    </Multipaso>
  </div>
</template>

<style>
body {
  background: #F3F6FC;
}
</style>