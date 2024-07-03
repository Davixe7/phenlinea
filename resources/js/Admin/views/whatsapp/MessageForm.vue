<script setup>
import { ref, watch } from 'vue';

const props       = defineProps(['value'])
const emits       = defineEmits(['input'])
const messageForm = ref(null)
const fileInput   = ref(null)
const message = ref({...props.value})

function storeMessage(){
  let file = fileInput.value.files.length
             ? fileInput.value.files[0]
             : null

  emits('storeMessage', file)
}

watch(
  ()=>message,
  ()=>{
  message.value.validated = messageForm.value.checkValidity()
  emits('input', message.value)
}, {deep: true})
</script>

<template>
  <form
    ref="messageForm"
    action="#"
    @submit.prevent="storeMessage"
    class="d-flex flex-column"
    style="flex: 1 1 auto;">

    <div class="my-3">
      <input
        type="text"
        class="form-control"
        placeholder="Asunto"
        v-model="message.title"
        minlength="3"
        required
        >
    </div>
    <div class="mb-3" style="flex: 1 1 auto;">
      <textarea
        minlength="3"
        rows="4"
        class="form-control"
        v-model="message.body"
        placeholder="Escriba su mensaje"
        style="height: 100%;"
        required
      >
      </textarea>
    </div>
    <div class="d-flex mb-3">
      <input
        ref="fileInput"
        type="file"
        class="form-control me-3"
        accept="image/png, image/jpeg, application/pdf"
        >
        <button
          :class="{disabled: !message.validated}"
          type="submit"
          class="btn btn-primary"
          style="white-space: nowrap;">
          Enviar mensaje
        </button>
    </div>
  </form>
</template>