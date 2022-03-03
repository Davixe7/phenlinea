<template>
  <v-card>
    <v-card-text>
      <v-form ref="form" v-model="valid">
        <v-text-field
          type="password"
          v-model="old_password"
          :counter="16"
          :rules="passwordRules"
          label="Contraseña actual"
          required
        ></v-text-field>
    
        <v-text-field
          type="password"
          v-model="password"
          :counter="16"
          :rules="newPasswordRules"
          label="Contraseña nueva"
          required
        ></v-text-field>
        
        <v-text-field
          type="password"
          v-model="password_confirm"
          :counter="16"
          label="Confirmar contraseña"
          required
        ></v-text-field>
    
        <v-card-actions class="mt-3">
          <v-spacer></v-spacer>
          <v-btn :loading="saving" dark @click="resetPassword">Actualizar</v-btn>
        </v-card-actions>
      </v-form>
    </v-card-text>
  </v-card>
</template>

<script>
  export default {
    props:['commerce'],
    data: () => ({
      errors: [],
      valid: true,
      saving: false,
      old_password: '',
      password: '',
      password_confirm: '',
      passwordRules: [
        v => (!!v && v).length >= 6 || 'La contraseña debe contener al menos 6 caracteres',
        v => (!!v && v).length <= 16 || 'La contraseña debe contener máximo 6 caracteres',
      ],
      
    }),
    computed:{
      newPasswordRules(){
        let matchRule = v=>(!!v&&v)===this.password_confirm||'Las contraseñas no coinciden'
        return [...this.passwordRules, matchRule]
      },
    },
    watch:{
      old_password:'validate',
      password:'validate',
      password_confirm:'validate',
    },
    methods: {
      resetPassword(){
        this.saving = true
        let data = {
          _method: 'PUT',
          old_password: this.old_password,
          password: this.password,
          password_confirmation: this.password_confirm,
        }
        axios.post(`/stores/${this.commerce.id}/reset-password`,data).then(response=>{
          this.$toasted.show('Contraseña actualizada')
          this.saving = false
        },err=>{
          this.errors = err.response.data.errors
        })
      },
      validate () {
        this.$refs.form.validate()
      }
    },
  }
</script>