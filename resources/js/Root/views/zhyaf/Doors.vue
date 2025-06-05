<script setup>
import {ref, onMounted} from 'vue'
const loading = ref(true)
const editing = ref(false)

const doors = ref([])
const door   = ref({name:'', devSn: ''})
const doorForm = ref(null)

onMounted(()=>{ fetchDoors() })

function fetchDoors(){
    loading.value = true
    axios.get('/zhyaf/admins/1/doors')
    .then(response => doors.value = [...response.data.data])
    .finally(()=>loading.value = false)
}

function setDoor(localDoor = {id: null, name:'', devSn: ''}){
    door.value = localDoor
    editing.value = true
}

function save(){
    if( door.value.id ){
        updateDoor();
        return;
    }
    createDoor();
}

function createDoor(){
    loading.value = true
    axios.post('zhyaf/admins/1/doors', {...door.value})
    .then(response => {
        doors.value.push( {id: response.data.data.id, name: door.value.name} )
        setDoor()
        editing.value = false
    })
    .finally(() => loading.value = false)
}

function updateDoor(){
    loading.value = true
    axios.post('zhyaf/admins/1/doors', {...door.value, _method:'PUT'})
    .then(response => {
        console.log('Door updated successfully')
        setDoor()
        editing.value = false
    })
    .finally(() => loading.value = false)
}

function deleteDoor(door){
    if( !window.confirm('Seguro que desea eliminar la puerta?') ){return;}
    loading.value = true
    axios.post('zhyaf/admins/1/doors', {id:door.id, _method:'DELETE'})
    .then(response => doors.value.splice(doors.value.indexOf(door), 1))
    .finally(() => loading.value = false)
}
</script>

<template>
<div class="doors-form">
    <table
        v-if="doors.length && !editing"
        class="table">
        <thead>
            <th>Puerta</th>
            <th class="text-end">Acciones</th>
        </thead>
        <tbody>
            <tr v-for="door in doors">
                <td>{{ door.name }}</td>
                <td class="text-end">
                    <i @click="setDoor(door)" class="material-symbols-outlined">edit</i>
                    <i @click="deleteDoor(door)" class="material-symbols-outlined">delete</i>
                </td>
            </tr>
        </tbody>
    </table>
    <div
        v-if="!editing"
        @click="editing=true"
        class="d-flex justify-content-end">
        <button class="btn btn-primary btn-sm w-auto" @click="setDoor()">
            Nueva puerta
        </button>
    </div>

    <form @submit.prevent="save" v-if="editing" ref="doorForm">
        <div class="form-group mb-3">
            <label for="#">Nombre de la Puerta o Punto de acceso</label>
            <input type="text" class="form-control" v-model="door.name" required>
        </div>

        <div class="d-flex justify-content-end">
            <button type="button" @click="()=>{setDoor(); editing=false}" class="btn btn-link">Cancelar</button>
            <button class="btn btn-primary w-auto">
                <template v-if="!loading">
                    <template v-if="!door.id">
                        Agregar
                    </template>
                    <template v-else>
                        Actualizar
                    </template>
                    punto de acceso
                </template>
                <template v-else>
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </template>
            </button>
        </div>
    </form>
</div>
</template>

<style>
.btn {
    width: auto;
}
.btn-sm {
    height: 2rem;
}
</style>