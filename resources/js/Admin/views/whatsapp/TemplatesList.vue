<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';

const props = defineProps(['modelValue', 'recepients'])
const emits = defineEmits(['update:modelValue', 'success'])

const templates = ref([])
const template = ref(null)
const loading = ref(false)
const submitting = ref(false)
const form = ref({})
const messageFormRef = ref(null)
const fileInputRef = ref(null)
const title = ref('')

async function fetchTemplates(){
    try {
        loading.value = true
        templates.value = (await axios.get('/message-templates')).data.data
    } catch (error) {
        console.log(error)
    }
    finally {
        loading.value = false
    }
}

async function submitForm(){
    try {
        if( !messageFormRef.value.reportValidity() ){ return; }
        submitting.value = true
        let data = new FormData()
        data.append('title', title.value)
        data.append('template_name', template.value.name)
        Object.keys(form.value).forEach(fieldId => {
            data.append(`fields[${fieldId}][value]`, form.value[fieldId])
        })

        props.recepients.forEach((number,i) => data.append(`receivers[${i}]`, number))

        if( fileInputRef.value.files[0] ) {
            data.append('file', fileInputRef.value.files[0])
        }

        await axios.post('/meta-messages', data)

        emits('success')
    } catch (error) {
        console.log(error)
    }
    finally {
        submitting.value = false
    }
}

onMounted(async () => {
    await fetchTemplates()
    template.value = templates.value.length ? templates.value[0] : template.value
})
</script>

<template>
    <div>
        <h3 style="font-size: 16px; font-weight: 400; margin: 16px 0 8px 0;">Seleccione la plantilla del mensaje</h3>
        <select v-model="template" class="form-control mb-3" v-if="templates && templates.length">
            <option :value="temp" v-for="temp in templates" :key="temp.id">
                {{ temp.label }}
            </option>
        </select>

        <form ref="messageFormRef"
        @submit.prevent="submitForm"
        v-if="template">

        <label for="subject">Asunto</label>
        <input type="text" id="subject" class="form-control mb-2" v-model="title" required/>

        <div class="row">
            <div
                v-for="field in template.fields"
                :key="field.name"
                :class="`mb-2 col-12 col-md-${field.cols}`"
            >
            
            <label :for="`input${field.id}`">{{ field.label }}</label>
            <input type="text"
                :id="`input${field.id}`"
                class="form-control mb-2"
                v-model="form[field.id]"
                :required="!!field.required"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-3">
                <div class="d-flex items-center" style="border: 1px solid rgba(0,0,0,.25); border-radius: 4px; padding: 4px;">
                    <i class="material-symbols-outlined me-2">attach_file</i>
                    <input type="file" ref="fileInputRef">
                </div>
            </div>

            <div class="col-12">
                <button
                    class="btn btn-primary w-100 mb-3"
                    type="submit">
                    Enviar mensaje
                </button>
            </div>
        </div>
        </form>
    </div>
</template>

<style>
label {
    font-size: .9rem;
}
</style>