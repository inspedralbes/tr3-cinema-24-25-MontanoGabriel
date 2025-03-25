<template>
  <div class="container">
    <!-- Contenido principal -->
    <div class="content">
      <h1 class="title">Películas Semanales</h1>

      <div v-if="weeklyMovies.length" class="movies-list">
        <div
          v-for="movie in weeklyMovies"
          :key="movie.id"
          class="movie-card"
          @click="goToSessions(movie.id)"
        >
          <img :src="movie.poster_url" :alt="movie.title" class="movie-poster" />
          <h3>{{ movie.title }}</h3>
        </div>
      </div>

      <p v-else class="loading-text">Cargando películas...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const weeklyMovies = ref([]) // Aquí se guardarán las películas semanales
const router = useRouter()

// Función para redirigir a la página de detalles de la película
const goToSessions = (movieId) => {
  router.push(`/movie/${movieId}`)
}

// Función para obtener los detalles completos de una película
const obtenerDetallesPelicula = async (movieId) => {
  try {
    const response = await fetch(`http://localhost:8000/api/movie/${movieId}`);
    if (!response.ok) {
      throw new Error(`Error al obtener los detalles de la película con ID: ${movieId}`);
    }
    const data = await response.json();
    return {
      title: data.titulo || "Título no disponible",
      poster_url: data.url_poster || "https://via.placeholder.com/200x300?text=No+Image",
      description: data.descripcion || "Sin descripción",
      id: data.id
    };
  } catch (error) {
    console.error('Error al obtener los detalles de la película:', error);
    return null;
  }
};

// Función para obtener las películas semanales y sus detalles
const obtenerPeliculasSemanales = async () => {
  try {
    const response = await fetch('http://localhost:8000/api/session-movies');
    if (!response.ok) {
      throw new Error(`Error al obtener las películas semanales: ${response.status}`);
    }
    const data = await response.json();

    // Validamos que weeklyMovies tenga el formato correcto
    if (Array.isArray(data.weeklyMovies) && data.weeklyMovies.length > 0) {
      const movies = await Promise.all(data.weeklyMovies.map(async (movie) => {
        // Usamos la función obtenerDetallesPelicula para obtener los detalles
        const movieDetails = await obtenerDetallesPelicula(movie.movie_id);
        return {
          ...movie,
          title: movieDetails?.title || "Título no disponible",
          poster_url: movieDetails?.poster_url || "https://via.placeholder.com/200x300?text=No+Image",
          description: movieDetails?.description || "Sin descripción",
          id: movieDetails?.id || movie.movie_id  // Aseguramos que cada película tenga un `id` válido
        };
      }));

      weeklyMovies.value = movies;
    } else {
      console.warn("⚠️ No hay películas semanales disponibles");
      weeklyMovies.value = [];
    }
  } catch (error) {
    console.error('Error al obtener las películas semanales:', error);
  }
};

// Llamamos a la función cuando el componente se monta
onMounted(() => {
  obtenerPeliculasSemanales();
});
</script>

<style scoped>
/* Contenedor principal */
.container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: #e5e7eb;
}

/* Contenido */
.content {
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  padding: 20px;
}

.title {
  font-size: 2rem;
  font-weight: bold;
  color: #333;
  margin-bottom: 20px;
}

/* Lista de películas */
.movies-list {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 20px;
}

/* Tarjeta de película */
.movie-card {
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
  padding: 15px;
  width: 220px;
  text-align: center;
  border: 1px solid #ccc;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.3s;
}

.movie-card:hover {
  transform: scale(1.05);
  box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.15);
}

/* Imagen de la película */
.movie-poster {
  width: 100%;
  height: auto;
  border-radius: 8px;
  margin-bottom: 10px;
}

/* Texto de carga */
.loading-text {
  font-size: 1.2rem;
  color: #1976d2;
  font-weight: bold;
  margin-top: 20px;
}
</style>
