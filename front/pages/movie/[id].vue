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
        <p class="description">{{ movie.description }}</p>

        <!-- Mostrar el tráiler solo si existe -->
        <div v-if="isValidYouTubeUrl(movie.url_trailer)" class="trailer">
          <iframe 
            :src="movie.url_trailer.replace('watch?v=', 'embed/')" 
            allowfullscreen
          ></iframe>
        </div>
        <p v-else class="no-trailer">🎬 Tráiler no disponible</p>
      </div>
      

      <!-- Duración a la derecha -->
      <div class="duration">
        <h3>⏳ Duración:</h3>
        <p>{{ movie.duration }} minutos</p>
        <p class="rating">⭐ Valoración: {{ movie.valoration }}</p>

        </div>
      </div>
    <!-- Horarios disponibles -->
    <div v-if="esPeliculaDelDia" class="horarios">
      <h3>🎟️ Horarios disponibles:</h3>
      <div class="schedule-buttons">
        <button 
          v-for="hora in horarios" 
          :key="hora" 
          class="schedule-button" 
          @click="comprarEntrada(hora)"
        >
          {{ hora }}
        </button>
      </div>
    </div>

    <!-- Mensaje si no hay horarios -->
    <div v-if="!esPeliculaDelDia" class="horarios-no-disponibles">
      <h3>📅 No hay horarios disponibles para esta película hoy.</h3>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
  
const route = useRoute()
const router = useRouter()

definePageMeta({
  layout: false,
})

// Obtener el ID de la película desde los parámetros de la ruta
const movieId = route.params.id
const horarios = ref([])
const movie = ref(null)
const esPeliculaDelDia = ref(false)


const isValidYouTubeUrl = (url) => {
  return url && url.includes('youtube.com/watch?v=');
};

const cargarHorarios = async () => {
  try {
    const response = await fetch(`http://cinemix.daw.inspedralbes.cat/laravel/public/api/session-movies`)
    if (!response.ok) throw new Error('Error al obtener los horarios')

    const data = await response.json()
    horarios.value = []
    esPeliculaDelDia.value = false

    const peliculaDelDia = data.movieOfTheDay
    const peliculasSemanales = data.weeklyMovies

    if (peliculaDelDia && peliculaDelDia.movie_id === parseInt(movieId)) {
      esPeliculaDelDia.value = true
    }

    peliculasSemanales.forEach((peli) => {
      if (peli.movie_id === parseInt(movieId)) {
        horarios.value.push(peli.time)
      }
    })
  } catch (error) {
    console.error('Error al cargar los horarios:', error)
  }
}

onMounted(() => {
  cargarHorarios()
})

onMounted(async () => {
  try {
    const apiUrl = `http://cinemix.daw.inspedralbes.cat/laravel/public/api/movie/${movieId}`
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
      url_trailer: data.trailer_url || 'trailer no encontrado',
      valoration: data.rating || 'Valoraciones no disponible',
    }
  } catch (error) {
    console.error('Error al cargar los detalles de la película:', error)
  }
})

const goBack = () => {
  router.go(-1)
}

const comprarEntrada = (hora) => {
  router.push(`/compra/${movieId}?hora=${hora}`)
}
</script>

<style scoped>
/* Estilos generales */
.container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: #f9fafb;
  padding: 20px;
}

/* Navbar */
.navbar {
  background-color: #0d47a1;
  padding: 15px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: white;
  border-radius: 8px;
}

.nav-button {
  background-color: #1976d2;
  border: none;
  padding: 10px 15px;
  cursor: pointer;
  color: white;
  border-radius: 5px;
  transition: 0.3s;
}

.nav-button:hover {
  background-color: #2563eb;
}

/* Sección de detalles de la película */
.movie-details {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  max-width: 1000px;
  margin: 20px auto;
  background: white;
  border-radius: 10px;
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
  padding: 20px;
}

.poster img {
  width: 250px;
  height: auto;
  border-radius: 10px;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

.info {
  flex: 1;
  padding: 0 20px;
}

.info h2 {
  font-size: 28px;
  color: #1e3a8a;
  margin-bottom: 10px;
}

.description {
  font-size: 18px;
  color: #444;
  margin-bottom: 15px;
}

.trailer iframe {
  width: 100%;
  max-width: 560px;
  height: 300px;
  border-radius: 10px;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

.no-trailer {
  text-align: center;
  font-weight: bold;
  color: #ff6347;
  padding: 10px;
  background: #f7f7f7;
  border-radius: 5px;
}

.rating {
  font-size: 20px;
  color: #f59e0b;
  font-weight: bold;
}

/* Horarios */
.horarios, .horarios-no-disponibles {
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
  background-color: #1e3a8a;
  color: white;
  padding: 10px 20px;
  font-size: 18px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

.schedule-button:hover {
  background-color: #2563eb;
}

@media (max-width: 768px) {
  .movie-details {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .info {
    padding: 10px;
  }
}
</style>