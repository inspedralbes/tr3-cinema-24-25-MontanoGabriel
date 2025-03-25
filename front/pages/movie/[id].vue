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

    <!-- Horarios disponibles (Solo si la película es la película del día) -->
    <div v-if="esPeliculaDelDia" class="Horario-diponible">
      <h3>Horarios disponibles:</h3>
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
    <div v-if="!esPeliculaDelDia" class="Horario-no-disponible">
      <h3>No hay horarios disponibles para esta película hoy.</h3>
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
});


// Obtener el ID de la película desde los parámetros de la ruta
const movieId = route.params.id
const horarios = ref([]);
const movie = ref(null)
const esPeliculaDelDia = ref(false);

const cargarHorarios = async () => {
  try {
    // Hacemos la solicitud para obtener los horarios
    const response = await fetch(`http://localhost:8000/api/session-movies`);
    if (!response.ok) throw new Error('Error al obtener los horarios');
    
    // Convertimos la respuesta a JSON
    const data = await response.json();

    // Inicializamos las variables para los horarios y la película seleccionada
    const sesiones = [];
// Reiniciar valores
    horarios.value = [];
    esPeliculaDelDia.value = false;

    let peliculaSeleccionada = null;

    // Obtenemos la película del día y las películas semanales
    const peliculaDelDia = data.movieOfTheDay;
    const peliculasSemanales = data.weeklyMovies;

     // Verificamos si hay una película del día y si coincide con el movieId
     if (peliculaDelDia && peliculaDelDia.movie_id === parseInt(movieId)) {
       esPeliculaDelDia.value = true; // Confirmamos que es la película del día
     }

    // Buscamos los horarios en las películas semanales
    peliculasSemanales.forEach((peli) => {
      if (peli.movie_id === parseInt(movieId)) {
        sesiones.push(peli.time);  // Agregamos los horarios correspondientes
        if (!peliculaSeleccionada) {
          peliculaSeleccionada = peli;  // Asignamos los detalles de la película si no se ha asignado aún
        }
      }
    });

    // Asignamos los horarios a la variable reactiva
    horarios.value = sesiones;

    // Verificamos que se haya encontrado la película
    if (peliculaSeleccionada) {
      movie.value = {
        title: peliculaSeleccionada.title || 'Título no disponible',
        description: peliculaSeleccionada.description || 'Descripción no disponible',
        duration: peliculaSeleccionada.duration || 'Duración no disponible',
        poster_url: peliculaSeleccionada.poster_url || 'Poster de la pelicula no disponible',
      };
    } else {
      console.warn('No se encontró la película con el ID especificado');
    }

  } catch (error) {
    console.error('Error al cargar los horarios y los detalles de la película:', error);
  }
};




// Llamamos a la función cuando el componente se monta
onMounted(() => {
  cargarHorarios();
});



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
  background-color: #f8fafc; /* Fondo blanco-azul muy suave */
}

/* Navbar */
.navbar {
  background-color: #1e3a8a; /* Azul oscuro */
  padding: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: white;
}

.nav-button {
  background-color: #3b82f6; /* Azul claro */
  border: none;
  padding: 8px 12px;
  cursor: pointer;
  color: white;
  border-radius: 5px;
}

.nav-button:hover {
  background-color: #2563eb; /* Azul más oscuro */
}

/* Estilos de los detalles de la película */
.movie-details {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding: 20px;
  max-width: 1000px;
  margin: auto;
  background: white;
  border-radius: 10px;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

.poster img {
  width: 250px;
  height: 375px;
  border-radius: 8px;
  object-fit: cover;
}

.info {
  flex: 1;
  padding: 0 20px;
}

.info h2 {
  font-size: 26px;
  margin-bottom: 10px;
  color: #1e3a8a; /* Azul oscuro */
}

.info p {
  font-size: 18px;
  color: #555;
}

.duration {
  font-size: 18px;
  font-weight: 500;
  color: #333;
}

/* Contenedor de horarios */
.Horario-diponible, .Horario-no-disponible {
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
  background-color: #1e3a8a; /* Azul oscuro */
  color: white;
  padding: 10px 20px;
  font-size: 18px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

.schedule-button:hover {
  background-color: #2563eb; /* Azul más claro */
}
</style>
