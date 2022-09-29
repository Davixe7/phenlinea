<template>
  <div id="admin-sms-history">
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
        <v-spacer></v-spacer>
        <v-btn icon @click="filtering = !filtering">
          <v-icon>sort</v-icon>
        </v-btn>
      </v-card-title>

      <v-container v-show="filtering" transition="scale-transition">
        <v-row>
          <v-col cols="5">
            <v-menu
              v-model="menu1"
              :close-on-content-click="true"
              transition="scale-transition"
              offset-y
              min-width="auto"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="dateFrom"
                  label="Desde"
                  prepend-inner-icon="today"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
              </template>
              <v-date-picker v-model="dateFrom"></v-date-picker>
            </v-menu>
          </v-col>
          <v-col cols="5">
            <v-menu
              v-model="menu2"
              :close-on-content-click="true"
              transition="scale-transition"
              offset-y
              min-width="auto"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="dateTo"
                  label="Hasta"
                  prepend-inner-icon="today"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
              </template>
              <v-date-picker v-model="dateTo"></v-date-picker>
            </v-menu>
          </v-col>
          <v-col cols="2">
            <v-btn small elevation="0" class="ml-auto" style="margin-top: 15px">
              Listo
            </v-btn>
          </v-col>
        </v-row>
      </v-container>

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
        <div class="label">Mensajes Enviados</div>
      </div>
      <div class="hero-counter">
        <div class="count">100 COP</div>
        <div class="label">Precio SMS por Móvil</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "AdminSmsHistory",
  data() {
    return {
      search: "",
      items: [],
      headers: [
        {
          text: "Enviado el",
          align: "start",
          sortable: true,
          value: "created_at",
        },
        {
          text: "Cuerpo del Mensaje",
          align: "center",
          sortable: true,
          value: "content",
        },
        {
          text:"Cantidad",
          align: "end",
          sortable: true,
          value: "count"
        },
      ],
    };
  },
  computed:{
    results(){
      if( !this.dateFrom || !this.dateTo ) return [...this.items]
      return this.items.filter(f=> f.created_at >= this.dateFrom && f.created_at <= this.dateTo)
    },
    messagesCount(){
      return this.results.length
    }
  }
};
</script>