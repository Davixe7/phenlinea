<template>
  <div id="product-form">
    <div class="form-group">
      <label for="#">Nombre</label>
      <input type="text" class="form-control" v-model="model.name">
    </div>
    <div class="form-group">
      <label for="#">Precio</label>
      <input type="number" class="form-control" v-model.number="model.price">
    </div>
    <div class="form-group">
      <label for="#">Descripción</label>
      <textarea name="" id="" rows="4" class="form-control" v-model="model.description"></textarea>
    </div>
    
    <div class="form-group panel" v-if="model.id && model.pictures.length">
      <label for="#">Galería actual</label>
      <div class="well">
        <pics-list :pictures="model.pictures" @removePic="removeUploadedPic"/>
      </div>
    </div>
    
    <div class="form-group panel">
      <div class="well">
        <pics-list :pictures="pictures" @removePic="removePic"/>
        <file-input @filesLoaded="pics=>pictures=pics"/>
      </div>
    </div>
    
    <div class="form-group text-right">
      <v-btn text @click="$emit('cancel')">Cancelar</v-btn>
      <v-btn dark v-if="!product" @click="storeProduct" :loading="saving">Guardar</v-btn>
      <v-btn dark v-else @click="updateProduct" :loading="saving">Actualizar</v-btn>
    </div>
  </div>
</template>

<script>
export default {
  props:['product'],
  data(){return{
    model: {},
    defaultProduct:{
      name: 'Producto',
      description: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quibusdam nobis earum voluptatibus ea, iure. Tempore nam placeat beatae officiis, et, dolore architecto praesentium. Excepturi nostrum totam asperiores culpa provident ullam!',
      pictures: [],
      price: 1000
    },
    pictures: [],
    saving: false
  }},
  methods:{
    removePic(pic){
      let picIndex = this.pictures.indexOf(pic)
      this.pictures.splice( picIndex, 1 )
    },
    removeUploadedPic(pic){
        let picIndex = this.pictures.indexOf(pic)
        let data = {_method:'DELETE', picture: pic.url}
        axios.post('/products/'+this.product.id+'/deletePicture', data)
        .then(response=>this.model.pictures.splice( picIndex, 1 ))
    },
    loadData(){
      this.saving = true
      let data = new FormData()
      let attributes = ['name', 'description', 'price']
      attributes.forEach(attr=>data.append(attr, this.model[attr]))
      return data
    },
    storeProduct(){
      let data = this.loadData()
      this.pictures.forEach( p=>data.append('pictures[]',p) )
      
      axios.post('/products', data).then(response=>{
        this.$toasted.show('Producto guardado!',{'position':'bottom-center'})
        this.$emit('updated', response.data.data)
        this.setProduct()
      })
    },
    updateProduct(){
      let data = this.loadData()
      data.append('_method', 'PUT')
      axios.post(`/products/${this.model.id}`, data).then(response=>{
        this.$toasted.show('Producto actualizado!',{'position':'bottom-center'})
        this.$emit('updated', response.data.data)
        this.setProduct()
      })
    },
    setProduct(newProduct){
      this.saving = false
      this.pictures = []
      this.model = newProduct ? {...newProduct} : {...this.defaultProduct}
    }
  },
  watch:{
    product(newProduct){
      this.setProduct(newProduct)
    }
  },
  mounted(){
    this.setProduct(this.product)
  }
}
</script>

<style lang="css" scoped>
</style>
