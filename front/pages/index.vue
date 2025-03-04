<template>
  <div class="container">
    <!-- Barra de Navegación -->
    <nav class="navbar">
      <button class="nav-button">Inicio/logo</button>
      <input
        type="text"
        v-model="buscarPeli"
        placeholder="Buscar..."
        class="search-bar"
      />
      <button class="nav-calendar" @click="irCalendario">Sesión semanal</button>
      <button class="nav-button">Sesión del día</button>
      <button class="nav-button">🛒</button>
      <button class="nav-button">Ícono login</button>
    </nav>

    <!-- Contenido Principal -->
    <div class="content">
      <h1>Sesión del Día</h1>
      <h2>Horario Semanal de sesiones/películas</h2>
    </div>

    <!-- Footer -->
    <footer class="footer">
      © 2025 CineApp - Todos los derechos reservados
    </footer>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router' 



const router = useRouter()

const irCalendario = () => {
  router.push('/Calendar')
}

</script>

<style scoped>
/* Contenedor principal */
.container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: #e5e7eb; /* Color gris claro */
}

/* Barra de navegación */
.navbar {
  background-color: gray; /* Gris oscuro */
  padding: 25px;
  display: flex;
  justify-content: space-around;
  color: black;
}

.navbar a {
  text-decoration: none;
  color: black;
  font-weight: bold;
  padding: 4px;
  background-color: gainsboro;
}

.navbar a:hover {

}

/* Contenido principal */
.content {
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  text-align: center;
}

.content h1 {
  font-size: 2rem;
  font-weight: bold;
}

.content h2 {
  font-size: 1.5rem;
  margin-top: 10px;
}

/* Footer */
.footer {
  background-color: #a1a1a1;
  padding: 15px;
  text-align: center;
  color: black;
  font-weight: bold;
}
</style>
