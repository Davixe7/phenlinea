<template>
    <tr>
        <td>
            <label class="">{{ name }}</label>
        </td>
        <td>
            <input v-if="!loading" type="checkbox" v-model="localAuth" @change="toggleAuth" />
            <span v-else>Cargando...</span>
        </td>
      </tr>
  </template>
  
  <script setup>
  import { ref, watch } from 'vue';
  
  // Define props
  const props = defineProps({
    resident: Object,
    sn: String,
    name: String,
    auth: [Boolean, Number]
  });

  const loading = ref(false)
  
  // Local state for the checkbox
  const localAuth = ref(props.auth === true || props.auth === 1);
  
  // Watch for changes in prop auth and update local state
  watch(() => props.auth, (newVal) => {
    localAuth.value = newVal === true || newVal === 1;
  });
  
  // Emit event to parent on checkbox change
  const emit = defineEmits(['update:auth']);
  const toggleAuth = () => {
    syncDevice()
    const newAuth = localAuth.value ? 1 : 0;
    emit('update:auth', newAuth);
  };

  function syncDevice(){
    let endpoint = localAuth.value ? 'add' : 'delete';
    loading.value = true
    axios.get(`/residents/${props.resident.id}/devices/${endpoint}/?devSns=${props.sn}`)
    .then(response => {
      console.log(response)
    })
    .finally(()=>loading.value = false)
  }
  </script>
  
  <style scoped>
  .row {
    margin-bottom: 10px;
  }
  </style>
  