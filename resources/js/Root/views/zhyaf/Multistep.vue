<script setup>
    import {ref} from 'vue'
    import Doors from './Doors.vue'
    import Devices from './Devices.vue'

    const loading = ref(false)
    const props = defineProps(['activeStepIndex'])
    const localActiveStepIndex = ref(1)
    const doors = ref([])
    const steps = ref([
        {
            "title": "Comunidad y Edificio",
            "status": 1,
            "enabled": 1,
        },
        {
            "title": "Puntos de Acceso y Dispositivos",
            "status": 0,
            "enabled": 1
        },
        {
            "title": "Apartamentos",
            "status": 0,
            "enabled": 0
        },
        {
            "title": "Residentes",
            "status": 0,
            "enabled": 0
        },
        {
            "title": "Registros faciales",
            "status": 0,
            "enabled": 0
        },
        {
            "title": "Confirmacion",
            "status": 0,
            "enabled": 0
        }
    ])

    async function syncStep01(){
        loading.value = true
        try {
            let communityCreated = await createCommunity()
            let unitCreated      = await createUnit()
            steps.value[0].status = 1
            steps.value[1].enabled = 1
        }
        catch(exception){
            console.log(exception)
        }
        loading.value = false
    }

    function syncStep02(){
        fetchDoors()
    }

    function createCommunity(){
        return axios.post('/zhyaf/admins/1/createcommunity')
    }

    function createUnit(){
        return axios.post('/zhyaf/admins/1/createunit')
    }

    function skipStep(){
        if( localActiveStepIndex.value == steps.value.length -1 ){ return; }
        localActiveStepIndex.value = localActiveStepIndex.value + 1
    }
</script>

<template>
    <div class="container">
        <div class="row"> 
            <div class="col-lg-4">
                <ul class="stepper">
                    <li
                        v-for="(step, n) in steps"
                        @click="localActiveStepIndex=n"
                        class="stepper-item"
                        :class="{
                            'active': localActiveStepIndex == n,
                            'disabled': step.enabled == false,
                            'success': step.status == 1,
                        }">
                        <div class="stepper-item__index">
                            <div class="stepper-item__indexNumber">
                                <div v-if="loading && localActiveStepIndex == n" class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <template v-else>
                                    {{ n+1 }}
                                </template>
                            </div>
                        </div>
                        <div class="stepper-item__title">{{ step.title }}</div>
                    </li>
                </ul>
            </div>
            <div class="col-lg-7 offset-lg-1 justify-content-center d-flex">
                <div v-if="localActiveStepIndex==0" class="d-flex flex-column">
                    <h4 :class="{'text-danger': steps[0].status == 2}">
                        {{ steps[0].title }}
                    </h4>
                    <p>
                        <template v-if="steps[0].status!=2">
                            Verificar la existencia de la comunidad y su edificio y sincronizar nombres e identificadores
                        </template>
                        <template v-else class="text-danger">
                            Ha ocurrido un error mientras intentamos sincronizar esta parte de la informacion
                        </template>
                    </p>
                    <div class="d-flex justify-content-end mt-auto">
                        <button
                            v-if="steps[0].status !=1"
                            @click="syncStep01()"
                            class="btn w-auto"
                            :class="{'btn-danger': steps[0].status ==2, 'btn-primary': steps[0].status ==0}">
                            <template v-if="steps[0].status==0">
                                Sincronizar ahora
                            </template>
                            <template v-if="steps[0].status==2">
                                Reintentar
                            </template>
                        </button>
                        <button v-else @click="skipStep()" class="btn btn-primary w-auto">Continuar</button>
                    </div>
                </div>

                <div v-if="localActiveStepIndex==1" class="d-flex flex-column">
                    <h4 :class="{'text-danger': steps[1].status == 2}">
                        {{ steps[1].title }}
                    </h4>
                    <p>
                        <template v-if="steps[1].status!=2">
                            Verificar la existencia de los puntos de acceso como entradas y salidas peatonales, vehiculares y sus dispositivos de control de acceso asociados.
                        </template>
                        <template v-else class="text-danger">
                            Ha ocurrido un error mientras intentamos sincronizar esta parte de la informacion
                        </template>
                    </p>
                    <Doors></Doors>
                    <Devices></Devices>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
.stepper {
    display: flex;
    flex-flow: column;
}
.stepper-item {
    --primary-color: rgba(0, 0, 0, .3);
    font-weight: 500;
    font-size: 1.2rem;
    color: var(--primary-color);
    padding: .5rem 0;
    display: flex;
    align-items: center;
    cursor: pointer;
    border-bottom: 1px solid rgba(0,0,0,.07);
    list-style-type: none;

    &.active {
        --primary-color: rgba(0, 0, 0, 1);
    }
    &.success {
        --primary-color: var(--bs-success);
    }
    &.disabled {
        opacity: .5;
    }
}
.stepper-item__index {
    flex: 0 0 fit-content;
    display: flex;
    justify-content: center;
    align-items: center;
}
.stepper-item__indexNumber {
    border-radius: 50%;
    color: #fff;
    background-color: var(--primary-color);
    width: 1.5rem;
    height: 1.5rem;
    display: flex;
    justify-content: center;
    align-items: center;
}
.stepper-item__title {
    padding-left: .75rem;
}
</style>