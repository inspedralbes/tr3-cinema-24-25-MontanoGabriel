<template>
  <div class="container">
    

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
      </section>

      <!-- Horario Semanal -->
      <section class="weekly-movies" v-if="weeklyMovies.length">
        <h2>Horario Semanal de sesiones/películas</h2>
        <div class="movies-list">
          <div v-for="(movie, index) in weeklyMovies" :key="index" class="weekly-movie">
            <h3>{{ diasSemana[index] }}: {{ movie.title }}</h3>
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

    
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'nuxt/app'

const router = useRouter()
const buscarPeli = ref('')
const isLogged = ref(false)
const profilePhoto = ref('https://static.vecteezy.com/system/resources/thumbnails/019/879/186/small/user-icon-on-transparent-background-free-png.png');
const diasSemana = ['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'];


// Variables reactivas para almacenar la película del día y las películas semanales
const movieOfTheDay = ref(null)
const weeklyMovies = ref([])




// Función para navegar a los detalles de la película
const verDetalles = (movieId) => {
  if (!movieId) {
    console.error("ID de película no válido");
    return;
  }
  router.push(`/movie/${movieId}`);
}

//  Funcion para obtener la informacion de las peliculas
const obtenerDetallesPelicula = async (movieId) => {
  try {
    const response = await fetch(`http://localhost:8000/api/movie/${movieId}`);
    if (!response.ok) {
      throw new Error(`Error al obtener los detalles de la película con ID: ${movieId}`);
    }
    const data = await response.json();
    return {
      title: data.titulo || "Título no disponible",
      poster_url: data.url_poster || "https://via.placeholder.com/200",
      description: data.descripcion || "Sin descripción",
      id: data.id
    };
  } catch (error) {
    console.error('Error al obtener los detalles de la película:', error);
    return null;
  }
};



onMounted(() => {
  const token = localStorage.getItem('auth_token')
  isLogged.value = !!token
})

// Al montar el componente, realizamos la petición al endpoint de películas
onMounted(async () => {
  try {
    const response = await fetch('http://localhost:8000/api/session-movies');
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    const data = await response.json();

    // Al recibir la película del día, utilizar `movie_id` como identificador
if (data.movieOfTheDay && data.movieOfTheDay.movie_id) {
  const movieId = data.movieOfTheDay.movie_id;
  const movieResponse = await fetch(`http://localhost:8000/api/movie/${movieId}`);
  if (!movieResponse.ok) {
    throw new Error(`Error al obtener los detalles de la película con ID: ${movieId}`);
  }
  const movieData = await movieResponse.json();

  movieOfTheDay.value = {
    title: movieData.titulo || "Título no disponible",
    poster_url: movieData.url_poster || "https://via.placeholder.com/300",
    id: movieData.id || movieId  // Usa el `movie_id` si el `id` no está presente
  };
}

 else {x
      console.warn("⚠️ No hay película del día disponible");
      movieOfTheDay.value = null;
    }

    // Validar si `weeklyMovies` es un array antes de mapearlo
    if (Array.isArray(data.weeklyMovies) && data.weeklyMovies.length > 0) {
      // Obtener los detalles de cada película asociada a las sesiones
      const movies = await Promise.all(data.weeklyMovies.map(async (movie) => {
        const movieDetails = await obtenerDetallesPelicula(movie.movie_id);
        return {
          ...movie,
          title: movieDetails?.title || "Título no disponible",
          poster_url: movieDetails?.poster_url || "https://via.placeholder.com/200",
          description: movieDetails?.description || "Sin descripción",
          id: movieDetails?.id || movie.movie_id  // Aquí aseguramos que cada película tenga un `id`
        };
      }));

      weeklyMovies.value = movies;
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
  background-color: #ffffff; /* Fondo blanco */
  color: #000000; /* Texto negro */
  font-family: 'Arial', sans-serif;
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

.nav-button, .nav-calendar {
  background-color: #1976d2; /* Azul claro */
  border: none;
  padding: 10px 15px;
  cursor: pointer;
  color: white;
  border-radius: 5px;
  transition: background-color 0.3s ease;
}

.nav-button:hover, .nav-calendar:hover {
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
  width: 40px;
  height: 40px;
  border-radius: 50%;
  cursor: pointer;
}

/* Contenido principal */
.content {
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  padding: 20px;
}

.movie-of-the-day img {
  max-width: 100%;
  height: 400px;
  border-radius: 8px;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
}

.movie-of-the-day img:hover {
  transform: scale(1.05);
  box-shadow: 0px 8px 12px rgba(0, 0, 0, 0.3);
}

.weekly-movies {
  margin-top: 20px;
}

.movies-list {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 20px;
}

.weekly-movie {
  border: 1px solid #ccc;
  padding: 15px;
  width: 200px;
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.weekly-movie:hover {
  transform: translateY(-5px);
  box-shadow: 0px 8px 12px rgba(0, 0, 0, 0.2);
}

.weekly-movie img {
  width: 100%;
  border-radius: 8px;
}

/* Footer */
.footer {
  background-color: #0d47a1; /* Azul oscuro */
  padding: 15px;
  text-align: center;
  color: white;
  font-weight: bold;
  margin-top: auto;
}
</style>