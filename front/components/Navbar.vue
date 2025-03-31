<template>
  <div class="container">
    <!-- Barra de Navegación -->
    <nav class="navbar">
      <button class="nav-button" @click="irHomePage">Home</button>
      <input
        type="text"
        v-model="buscarPeli"
        placeholder="Buscar..."
        class="search-bar"
      />
      <button class="nav-calendar" @click="irCalendario">Sesión semanal</button>
      <!-- Botón de perfil o ícono de login según si está logueado -->
      <div v-if="isLogged" class="profile-photo" @click="irPerfil">
        <img class="loginIcon" :src="profilePhoto" alt="Foto de perfil" />
      </div>

      <button v-else class="nav-button" @click="irLogin">Login</button>
    </nav>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "nuxt/app";

const router = useRouter();
const buscarPeli = ref("");
const isLogged = ref(false);
const profilePhoto = ref(
  "https://static.vecteezy.com/system/resources/thumbnails/019/879/186/small/user-icon-on-transparent-background-free-png.png"
);

// Comprobar si el usuario está autenticado al montar el componente
onMounted(() => {
  if (process.client) {
    const token = localStorage.getItem("auth_token");
    isLogged.value = !!token; // Si hay token, isLogged será true
  }
});

// Detectar cambios en el localStorage para actualizar el estado
watchEffect(() => {
  if (process.client) {
    const token = localStorage.getItem("auth_token");
    isLogged.value = !!token;
  }
});

// Función para navegar a la página del calendario
const irCalendario = () => {
  router.push("/Calendar");
};
// Ir a todas las peliculas
const irPeliculas = () => {
  router.push("/peliculas");
};
const irHomePage = () => {
  router.push("/");
};

// Función para ir al perfil
const irPerfil = () => {
  const token = localStorage.getItem("auth_token");
  if (token) {
    router.push("/perfil"); // Si hay token, redirige al perfil
  } else {
    router.push("/login"); // Si no hay token, redirige al login
  }
};

// Función para ir al login (cuando no estás logueado)
const irLogin = () => {
  router.push("/login"); // Siempre redirige al login si no estás logueado
};
</script>

<style scoped>
/* Contenedor principal */
.container {
  display: flex;
  flex-direction: column;
  background-color: #ffffff; /* Fondo blanco */
  color: #000000; /* Texto negro */
  font-family: "Arial", sans-serif;
}

/* Barra de navegación */
.navbar {
  background-color: #0d47a1; /* Azul oscuro */
  padding: 15px;
  display: flex;
  justify-content: space-around;
  align-items: center;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.nav-button,
.nav-calendar {
  background-color: #1976d2; /* Azul claro */
  border: none;
  padding: 10px 15px;
  cursor: pointer;
  color: white;
  border-radius: 5px;
  transition: background-color 0.3s ease;
}

.nav-button:hover,
.nav-calendar:hover {
  background-color: #2196f3; /* Azul brillante al hacer hover */
}

.search-bar {
  padding: 8px;
  border-radius: 5px;
  border: 1px solid #ccc;
  background-color: #ffffff;
  color: #000000;
}
.loginIcon {
  width: 55px;
  height: 50px;
  border-radius: 50%;
  cursor: pointer;
}
</style>
