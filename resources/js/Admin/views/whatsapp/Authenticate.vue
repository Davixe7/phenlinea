<script setup>
import axios from 'axios'
import { onMounted, ref } from 'vue';

const api = axios.create({
  baseURL: `${process.env.MIX_SOCKET_BASE_URL}`,
  headers: {
    'Access-Control-Allow-Origin': '*',
    'mode': 'no-cors',
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  'credentials': 'same-origin',
})

const props = defineProps(['method', 'instance_id', 'phone'])
const emits = defineEmits(['authenticated'])

const instance_id = ref(null)
const qrcode = ref('')
const pairingCode = ref('')

async function getInstance() {
  if (props.method == 'pairingCode') {
    instance_id.value = (await api.get('pairing_code', { params: { phone: props.phone } })).data.instance_id
    return
  }
  instance_id.value = (await api.get('create_instance')).data.instance_id
  qrcode.value = (await api.get('get_qrcode', { params: { instance_id: instance_id.value } })).data.base64
}

function setSockets() {
  const socket = io(process.env.MIX_SOCKET_BASE_URL);

  socket.on('pairingCode', (data) => {
    data = JSON.parse(data)
    pairingCode.value = data.pairingCode
  })

  socket.on('qrcode', (data) => {
    data = JSON.parse(data)
    if (data.instance_id != instance_id.value) return
    qrcode.value = data.qrcode
  })

  socket.on('ready', (data) => {
    data = JSON.parse(data)
    if (data.phone != props.phone) return
    emits('authenticated', data)
  })
}

onMounted(() => {
  if (props.instance_id == null) {
    getInstance();
    setSockets();
    return;
  }
})
</script>

<template>
  <div style="height: 100%;">
    <template v-if="!props.instance_id" class="d-flex flex-column" style="flex: 1 1 auto;">

      <div class="instance-id" style="flex: 0 0 auto;">
        {{ instance_id ? instance_id : '' }}
      </div>

      <div class="instance-id" style="flex: 0 0 auto;">
        {{ props.phone ? props.phone : '' }}
      </div>

      <div v-if="method=='qrCode'" class="qrcode d-flex justify-content-center align-items-center" style="flex: 1 1 auto;">
        <img v-if="qrcode" :src="qrcode" alt="">
        <div v-else class="spinner-border spinner-border-lg" role="status">
        </div>
      </div>

      <div v-else class="pairing-code">
        <div v-if="pairingCode">
          {{ pairingCode }}
        </div>
        <div v-else class="spinner-border spinner-border-lg" role="status">
        </div>
      </div>
    </template>

    <template v-else>
      <div class="d-flex flex-column align-items-center justify-content-center"
        style="height: 100%; background: radial-gradient(#36cbf2b8, #36ecf21c, transparent);">
        <div>
          <img src="/img/sent.png">
        </div>
        <div class="mb-2" style="font-weight: 700; font-size: 18px;">
          ¡Listo!
        </div>
        <div class="mb-2">
          El envío de su mensaje empezara en breve.
        </div>
        <a href="/home" class="btn btn-primary">
          Volver al menú
        </a>
      </div>
    </template>
  </div>
</template>

<style lang="scss">
.instance-id {
  padding: 1rem;
  text-align: center;
  border: 1px solid rgba(0, 0, 0, .7);
}
.pairing-code {
  font-size: 1.25rem;
  font-family: 'Monospace';
  letter-spacing: .4rem;
  padding: 1rem;
  text-align: center;
  border: 1px solid rgba(0, 0, 0, .7);
}
</style>