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
      <!-- Película del Día -->
      <section class="movie-of-the-day" v-if="movieOfTheDay">
        <h1>Película del Día</h1>
        <h2>{{ movieOfTheDay.title }}</h2>
        <img
          :src="movieOfTheDay.poster_url"
          :alt="movieOfTheDay.title"
          class="movie-image"
          @click="verDetalles(movieOfTheDay.id)"
        />
        <p>{{ movieOfTheDay.description }}</p>
      </section>

      <!-- Horario Semanal -->
      <section class="weekly-movies" v-if="weeklyMovies.length">
        <h2>Horario Semanal de sesiones/películas</h2>
        <div class="movies-list">
          <div v-for="(movie, index) in weeklyMovies" :key="index" class="weekly-movie">
            <h3>Día {{ index + 1 }}: {{ movie.title }}</h3>
            <img
              :src="movie.poster_url"
              :alt="movie.title"
              class="movie-image"
              @click="verDetalles(movie.id)"
            />
            
          </div>
        </div>
      </section>
    </div>

    <!-- Footer -->
    <footer class="footer">
      © 2025 CineApp - Todos los derechos reservados
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'nuxt/app'
import axios from 'axios'

const router = useRouter()
const buscarPeli = ref('')

// Variables reactivas para almacenar la película del día y las películas semanales
const movieOfTheDay = ref(null)
const weeklyMovies = ref([])

// Función para navegar a la página del calendario
const irCalendario = () => {
  router.push('/Calendar')
}

// Función para navegar a los detalles de la película
const verDetalles = (movieId) => {
  router.push(`/movie/${movieId}`)
}

// Al montar el componente, realizamos la petición al endpoint de películas
onMounted(async () => {
  try {
    const response = await fetch('http://localhost:8000/api/movies');
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    const data = await response.json();

    // Validar si `movieOfTheDay` existe y tiene datos correctos
    if (data.movieOfTheDay && data.movieOfTheDay.titulo) {
      movieOfTheDay.value = {
        title: data.movieOfTheDay.titulo || "Título no disponible",
        poster_url: data.movieOfTheDay.url_poster || "https://via.placeholder.com/300",
        description: data.movieOfTheDay.descripcion || "Sin descripción",
        id: data.movieOfTheDay.id // Asegúrate de que el ID esté presente
      };
    } else {
      console.warn("⚠️ No hay película del día disponible");
      movieOfTheDay.value = null;
    }

    // Validar si `weeklyMovies` es un array antes de mapearlo
    if (Array.isArray(data.weeklyMovies) && data.weeklyMovies.length > 0) {
      weeklyMovies.value = data.weeklyMovies.map(movie => ({
        title: movie.titulo || "Título no disponible",
        poster_url: movie.url_poster || "https://via.placeholder.com/200",
        description: movie.descripcion || "Sin descripción",
        id: movie.id // Asegúrate de que el ID esté presente
      }));
    } else {
      console.warn("⚠️ No hay películas semanales disponibles");
      weeklyMovies.value = [];
    }

  } catch (error) {
    console.error('Error al obtener las películas:', error);
  }
});
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

.nav-button, .nav-calendar {
  background-color: gainsboro;
  border: none;
  padding: 8px 12px;
  cursor: pointer;
}

.search-bar {
  padding: 8px;
}

/* Contenido principal */
.content {
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  padding: 10px;
}

.movie-of-the-day img{
  max-width: 100%;
  height: 400px;
  border-radius: 8px;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}
.weekly-movie img {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}


.movies-list {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 10px;
}

.weekly-movie {
  border: 1px solid #ccc;
  padding: 10px;
  width: 200px;
}

.weekly-movie img {
  width: 100%;
}

/* Footer */
.footer {
  background-color: #a1a1a1;
  padding: 15px;
  text-align: center;
  color: black;
  font-weight: bold;
}

/* Efecto hover para las imágenes */
.movie-image {
  max-width: 100%;
  height: 400px;
  border-radius: 8px;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
}

.movie-image:hover {
  transform: scale(1.05); /* Aumenta ligeramente el tamaño de la imagen */
  box-shadow: 0px 8px 12px rgba(0, 0, 0, 0.3); /* Aumenta la sombra */
}

/* Semanas */
.weekly-movie img {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
}

.weekly-movie img:hover {
  transform: scale(1.05); /* Aumenta ligeramente el tamaño de la imagen */
  box-shadow: 0px 8px 12px rgba(0, 0, 0, 0.3); /* Aumenta la sombra */
}
</style>
