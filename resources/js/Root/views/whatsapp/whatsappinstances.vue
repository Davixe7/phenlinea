<template>
    <div id="admins">
  
      <div class="table-responsive">
        <div class="d-flex align-items-center">
          <h1 style="flex: 1;">Instancias de WhatsApp</h1>
          <div class="d-flex py-3 px-3" style="flex: 1;">
            <SearchForm
              v-if="admins && admins.length"
              v-model="results"
              :collection="rows"
              :attribute="'name'"
              class="ms-auto">
            </SearchForm>
          </div>
        </div>
        <table class="table" v-if="results && results.length">
          <thead>
            <th>Admin</th>
            <th>ID</th>
          </thead>
          <tbody>
            <tr v-for="admin in results">
              <td>{{ admin.name }}</td>
              <td>
                <div class="d-flex align-items-center justify-content-center">
                  <input type="tel" class="form-control form-control-sm m-2" v-model="admin.whatsapp_instance_id">
                  <button @click="updateInstance(admin)" class="btn">
                      <i class="material-symbols-outlined">update</i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="!results || !results.length" class="alert alert-info">
        No hay administradores para mostrar
      </div>
  
    </div>
  </template>
  
  <script setup>
  //import CreateAdmin from './CreateAdmin.vue'
  import SearchForm from './../../../Admin/components/SearchForm.vue'
  import { ref, onMounted } from 'vue'
  
  const props = defineProps(['admins'])
  
  const adminToEdit = ref({})
  const editing = ref(false)
  const results = ref([])
  const errors = ref({})
  const rows   = ref([])
  const loading = ref(false)
  
  const AdminsModal   = ref(null)
  const PaymentsModal = ref(null)
  
  function updateInstance(admin) {
    axios.post(`/admin/whatsapp_instances/${admin.id}`, {_method:'PUT', whatsapp_instance_id: admin.whatsapp_instance_id})
  }
  
  onMounted(() => {
    rows.value    = [...props.admins]
    results.value = [...props.admins]
  })
  </script>
  
  <style>
  .btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
  }
  label {
    font-size: 12px;
    margin-bottom: 8px;
  }
  input.form-control {
    height: 45px;
  }
  .form-control {
    margin-bottom: 10px;
  }
  .modal-title {
    font-size: 1.1em;
  }
  </style>
  