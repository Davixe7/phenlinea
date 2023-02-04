<template>
  <div id="users">
    <div v-if="users.length" class="table-responsive">
      <h1>Usuarios</h1>
      <table class="table">
        <thead>
          <th>ID</th>
          <th>Nombre</th>
          <th>Correo</th>
          <th class="text-right">Opciones</th>
        </thead>
        <tbody>
          <tr v-for="user in users">
            <td>{{ user.id }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>

            <td class="text-right">
              <div class="btn-group">
                <a
                  href="#"
                  class="btn btn-sm btn-link"
                  @click="editUser(user)"
                >
                  <i class="material-icons">edit</i></a
                >
                <a
                  href="#"
                  class="btn btn-sm btn-link"
                  @click="deleteUser(user.id)"
                >
                  <i class="material-icons">delete</i></a
                >
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else class="alert alert-info">No hay usuarios para mostrar</div>

    <div class="fab-container">
      <button
        type="button"
        class="btn btn-primary btn-circle"
        data-toggle="modal"
        data-target="#exampleModal"
        @click="
          editing = false;
          extToEdit = null;
        "
      >
        <i class="material-icons">add</i>
      </button>
    </div>

    <!-- Modal -->
    <div
      ref="UsersModal"
      class="modal fade"
      id="exampleModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              <span v-if="!editing">Crear</span>
              <span v-else>Actualizar</span>
              usuario
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <create-user
              :userToEdit="userToEdit"
              :editing="editing"
              @userStored="appendUser"
              @userUpdated="updateUser"
            >
            </create-user>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CreateUser from "./CreateUser.vue";
export default {
  name: "Users",
  components: { CreateUser },
  data() {
    return {
      userToEdit: null,
      editing: false,
      users: [],
    };
  },
  methods: {
    editUser(user) {
      this.userToEdit = user;
      this.editing = true;
      $(this.$refs.UsersModal).modal("show");
    },
    updateUser(user) {
      this.users = this.users.map((cUser) => {
        return user.id == cUser.id ? user : cUser;
      });
      this.$toasted.success("Usuario actualizado exitosamente", {
        position: "bottom-left",
      });
    },
    deleteUser(id) {
      if (window.confirm("¿Seguro que quieres eliminar al usuario?")) {
        axios
          .delete("/admin/users/" + id)
          .then((response) => {
            this.users = this.users.filter((user) => {
              return user.id != id;
            });
            this.$toasted.success("Usuario eliminado exitosamente", {
              position: "bottom-left",
            });
          })
          .catch((error) => {
            console.log(error.response);
            if (error.response.status == 403) {
              this.$toasted.error(
                "No tienes permisos para realizar esta acción",
                { position: "bottom-left" }
              );
            }
          });
      }
    },
    appendUser(user) {
      this.users.push(user);
      this.$toasted.success("Usuario creado exitosamente", {
        position: "bottom-left",
      });
    },
  },
  mounted() {
    axios.get("/admin/users/list").then((response) => {
      this.users = response.data.data;
    });
  },
};
</script>
