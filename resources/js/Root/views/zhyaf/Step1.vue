<template>
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
    <button
        @click="createCommunity()"
        class="btn w-auto"
        :class="{'btn-danger': steps[0].status ==2, 'btn-success': steps[0].status ==1, 'btn-primary': steps[0].status ==0}">
        <template v-if="steps[0].status==0">
            Sincronizar ahora
        </template>
        <template v-if="steps[0].status==2">
            Reintentar
        </template>
    </button>
</template>