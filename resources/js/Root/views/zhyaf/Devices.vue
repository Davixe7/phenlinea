<script setup>
import {ref, onMounted} from 'vue'
const loading = ref(true)
const editing = ref(false)

const doors = ref([])

const devices = ref([])
const device  = ref({name:'', devSn: '', doorId: null})

function fetchDoors(){
    loading.value = true
    axios.get('/zhyaf/admins/1/doors')
    .then(response => doors.value = [...response.data.data])
    .finally(()=>loading.value = false)
}

onMounted(() => {
    fetchDoors()
    fetchDevices()
})

function fetchDevices(){
    loading.value = true
    axios.get('/zhyaf/admins/1/devices')
    .then(response => devices.value = [...response.data.data])
    .finally(()=>loading.value = false)
}

function setDevice(localDevice = {id: null, name:'', devSn: '', doorId: null}){
    device.value = localDevice
    editing.value = true
}

function save(){
    if( device.value.id ){
        updateDevice();
        return;
    }
    createDevice();
}

function createDevice(){
    loading.value = true
    axios.post('zhyaf/admins/1/devices', {...device.value})
    .then(response => {
        devices.value.push( {id: response.data.data.id, name: device.value.name} )
        setDevice()
        editing.value = false
    })
    .finally(() => loading.value = false)
}

function updateDevice(){
    loading.value = true
    axios.post('zhyaf/admins/1/devices', {...device.value, _method:'PUT'})
    .then(response => devices.value.push( response.data.data ))
    .finally(() => loading.value = false)
}

function deleteDevice(door){
    if( !window.confirm('Seguro que desea eliminar el dispositivo?') ){return;}
    loading.value = true
    axios.post('zhyaf/admins/1/devices', {id:door.id, _method:'DELETE'})
    .then(response => devices.value.splice(doors.value.indexOf(device), 1))
    .finally(() => loading.value = false)
}
</script>

<template>
    <div>
        <table
        v-if="devices.length && !editing"
        class="table">
        <thead>
            <th>Serial del dispositivo</th>
            <th>Puerta</th>
            <th class="text-end">Acciones</th>
        </thead>
        <tbody>
            <tr v-for="device in devices">
                <td>{{ device.devSn }}</td>
                <td>{{ device.positionFullName }}</td>
                <td class="text-end">
                    <i @click="setDevice(device)" class="material-symbols-outlined">edit</i>
                    <i @click="deleteDevice(device)" class="material-symbols-outlined">delete</i>
                </td>
            </tr>
        </tbody>
    </table>

    <div
        v-if="!editing"
        @click="editing=true"
        class="d-flex justify-content-end">
        <button class="btn btn-primary btn-sm w-auto" @click="setDevice()">
            Nuevo dispositivo
        </button>
    </div>

    <form @submit.prevent="save" v-if="editing" ref="deviceForm">
        <div class="form-group mb-3">
            <label for="#">Numero de Serial del Dispositivo</label>
            <input type="text" class="form-control" v-model="device.devSn" required>
        </div>

        <div class="form-group mb-3">
            <label for="#">Puerta o punto de acceso</label>
            <select
                v-model="device.doorId"
                class="form-control"
                required>
                <option
                    v-for="door in doors"
                    :key="door.id"
                    :value="door.id">
                    {{ door.name }}
                </option>
            </select>
        </div>

        <div class="d-flex justify-content-end">
            <button type="button" @click="()=>{setDevice(); editing=false}" class="btn btn-link">Cancelar</button>
            <button type="submit" class="btn btn-primary w-auto">
                <template v-if="!device.id">
                    Agregar
                </template>
                <template v-else>
                    Actualizar
                </template>
                Dispositivo
            </button>
        </div>
    </form>
    </div>
</template>