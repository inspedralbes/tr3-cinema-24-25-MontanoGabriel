<template>
  <div class="container">
    <!-- Navbar -->
    <nav class="navbar">
      <button class="nav-button" @click="goBack">⬅ Volver</button>
      <h1>Detalles de la Película</h1>
    </nav>

    <!-- Contenido de la película -->
    <div v-if="movie" class="movie-details">
      <!-- Imagen a la izquierda -->
      <div class="poster">
        <img :src="movie.poster_url" :alt="movie.title" />
      </div>

      <!-- Descripción en el centro -->
      <div class="info">
        <h2>{{ movie.title }}</h2>
        <p>{{ movie.description }}</p>
      </div>

      <!-- Duración a la derecha -->
      <div class="duration">
        <h3>Duración:</h3>
        <p>{{ movie.duration }} minutos</p>
      </div>
    </div>

    <!-- Horarios disponibles -->
    <div class="schedule-container">
      <h3>Horarios disponibles:</h3>
      <div class="schedule-buttons">
        <button class="schedule-button" @click="comprarEntrada('16:00')">16:00</button>
        <button class="schedule-button" @click="comprarEntrada('18:00')">18:00</button>
      </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
      © 2025 CineApp - Todos los derechos reservados
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const movie = ref(null)
const route = useRoute()
const router = useRouter()

// Obtener el ID de la película desde los parámetros de la ruta
const movieId = route.params.id

onMounted(async () => {
  try {
    // Construir la URL de la API para obtener los detalles de la película
    const apiUrl = `http://localhost:8000/api/movie/${movieId}`

    // Usar fetch para hacer la petición
    const response = await fetch(apiUrl)

    if (!response.ok) {
      throw new Error(`Error al obtener los detalles de la película: ${response.status}`)
    }

    const data = await response.json()

    movie.value = {
      title: data.titulo || 'Título no disponible',
      description: data.descripcion || 'Descripción no disponible',
      duration: data.duracion || 'Duración no disponible',
      poster_url: data.url_poster || 'https://via.placeholder.com/200x300?text=No+Image',
    }
  } catch (error) {
    console.error('Error al cargar los detalles de la película:', error)
  }
})

// Función para regresar a la página anterior
const goBack = () => {
  router.go(-1) // Regresa a la página anterior
}

// Función para redirigir a la compra de entradas con el horario seleccionado
const comprarEntrada = (hora) => {
  router.push(`/compra/${movieId}?hora=${hora}`)
}
</script>

<style scoped>
/* Estilos del contenedor principal */
.container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: #e5e7eb;
}

/* Navbar */
.navbar {
  background-color: gray;
  padding: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: black;
}

.nav-button {
  background-color: gainsboro;
  border: none;
  padding: 8px 12px;
  cursor: pointer;
}

/* Estilos de los detalles de la película */
.movie-details {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  max-width: 900px;
  margin: auto;
  background: white;
  border-radius: 10px;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

.poster img {
  width: 200px;
  border-radius: 8px;
}

.info {
  flex: 1;
  padding: 0 20px;
}

.info h2 {
  font-size: 24px;
  margin-bottom: 10px;
}

.info p {
  font-size: 16px;
  color: #555;
}

.duration {
  text-align: center;
  font-weight: bold;
}

/* Contenedor de los horarios */
.schedule-container {
  text-align: center;
  margin-top: 20px;
}

.schedule-buttons {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-top: 10px;
}

.schedule-button {
  background-color: red;
  color: white;
  padding: 10px 20px;
  font-size: 18px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

.schedule-button:hover {
  background-color: darkred;
}

/* Footer */
.footer {
  background-color: #a1a1a1;
  padding: 15px;
  text-align: center;
  color: black;
  font-weight: bold;
  margin-top: 20px;
}
</style>
