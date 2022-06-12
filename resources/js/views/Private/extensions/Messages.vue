<template>
  <div id="messages">
    <div class="row">
      <div class="col-lg-6">
        <v-card>
          <v-card-text>
            <form id="sendmessage-form" ref="sendMessageForm">
              <div class="form-group">
                <!-- <label for="message" class="mb-3">
                  Cuerpo del mensaje
                </label> -->
                <textarea
                  placeholder="Escribe tu mensaje"
                  name="message"
                  id="message"
                  rows="7"
                  class="form-control"
                  minlength="20"
                  maxlength="160"
                  v-model="message"
                  required></textarea>
                <div class="help-text">
                  <i>Caracteres restantes:</i>
                  <span class="chars-count">{{ 160 - charsCount }}</span>
                </div>
              </div>
              
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="toOwners" id="radio1" v-model="toOwners" :value="true">
                <label class="form-check-label" for="radio1">Solo propietarios</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="selectAll" id="radio2" v-model="selectAll" :value="true">
                <label class="form-check-label" for="radio2">Todos</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="selectAll" id="radio3" v-model="selectAll" :value="false">
                <label class="form-check-label" for="radio3">Seleccionar</label>
              </div>
              
              <div class="form-check-wrapper" v-show="!selectAll && extensions && extensions.length">
                <div class="form-check" v-for="e in extensiones">
                  <input
                    type="checkbox"
                    :value="e.id"
                    v-model="selected"
                    :id="'smsCheck'+e.id"
                    class="form-check-input"
                  />
                  <label class="form-check-label" :for="'smsCheck'+e.id">{{ e.name }}</label>
                </div>
              </div>
              
              <div class="help-text mb-3">
                <i>Unidades seleccionadas:</i>
                {{ selected.length }}
              </div>
              
              <v-btn dark
                v-if="extensions && extensions.length"
                @click="sendMessage"
                class="w-100">
                Enviar
              </v-btn>
            </form>
          </v-card-text>
        </v-card>
      </div>
      <div class="col-lg-6">
        <v-card>
        <v-card-title>
          Historial de SMS
          <v-spacer></v-spacer>
          <v-text-field
            class="p-0 m-0"
            v-model="search"
            append-icon="search"
            label="Buscar..."
            single-line
            hide-details
          ></v-text-field>
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="items"
          :search="search"
        ></v-data-table>
      </v-card>
      <div class="counters">
          <div class="hero-counter">
            <div class="count">
              {{ messagesCount }}
            </div>
            <div class="label">
              Mensajes Enviados
            </div>
          </div>
          <div class="hero-counter">
            <div class="count">
              100 COP
            </div>
            <div class="label">
              Precio SMS por M贸vil
            </div>
          </div>
      </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Messages',
  props:{
    extensions: {type: Array},
    log: {
      type: Array,
      default:()=>([])
    }
  },
  data(){
    return {
      toOwners: false,
      selectAll: false,
      message: '',
      selected: [],
      isValid: false,
      extensiones: null,
      items: [],
      
      search: '',
      headers: [
        {text: 'Enviado el', align: 'start', sortable: true, value:'created_at'},
        {text: 'Cuerpo del Mensaje', align: 'center', sortable: true, value:'content'},
        {text: 'Cantidad', align: 'end', sortable: true, value:'count'},
      ],
      types: {
        'delivery': 'encomienda',
        'admin':    'administracion',
        'services': 'servicios'
      }
    }
  },
  computed:{
    messagesCount(){
      return this.items.reduce((sum, item)=>{
        return Number(sum) + Number(item.count)
      }, 0)
    },
    charsCount(){
      return this.message.length
    },
    extensionsIds(){
      return this.extensiones.map(ext => ext.id)
    },
  },
  watch:{
    extensions(newVal){
      this.extensiones = [...newVal]
    },
    selectAll(newVal){
      if(newVal){
        this.selected = this.extensionsIds
      }
    },
    toOwners(newVal){
      this.extensiones = (newVal) ? this.extensions.filter( e => e.owner_phone ) : [...this.extensions]
      if( this.selectAll ){
        this.selected = this.extensionsIds
      }
    }
  },
  methods:{
    sendMessage(){
      this.isValid = this.$refs.sendMessageForm.reportValidity()
      if( !window.confirm('Señor usuario, recuerde que los mensajes enviados por este medio generan un costo, ¿confirma que desea enviar?') ){ return }
      if( this.isValid ){
        let data = {
          message: this.message,
          receiverType: (this.toOwners) ? 'owners' : 'extensions',
          receiver: this.selected
        }
        axios.post('/bulk', data).then(response=>{
          this.items.push( response.data.data )
          this.$toasted.success('Mensaje masivo enviado exitosamente', {position:'bottom-left'})
          this.clearForm()
        },error=>{
          this.errors = error.response.data.errors
          this.$toasted.error('No tienes permiso para realizar esta acci璐竛', {position:'bottom-left'})
        })
      }
    },
    clearForm(){
      this.selectAll = false
      this.message = ''
    }
  },
  mounted(){
    this.items = [...this.log]
    this.extensiones = [...this.extensions]
  }
}
</script>

<style lang="scss" scoped>
    .counters{
        text-align: center;
        display: flex;
        justify-content: center;
    }
  .hero-counter {
    color: rgba(0,0,0,.25);
    font-size: 4em;
    font-weight: 600;
    padding: 50px;
    .label {
      font-weight: 500;
      font-size: .25em;
    }
  }
  .help-text, .chars-count {
    font-size: .9em;
    color: #ccc;
  }
  .form-check-wrapper {
      overflow: auto;
      height: 200px;
      border:1px solid #c1c1c1;
      padding: 5px 10px;
  }  
</style>

