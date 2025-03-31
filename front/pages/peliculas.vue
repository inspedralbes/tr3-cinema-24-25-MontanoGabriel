<template>
    <div class="container">
      <!-- Navbar -->
      <nav class="navbar">
        <button class="nav-button">Inicio/logo</button>
        <input
          type="text"
          v-model="buscarPeli"
          placeholder="Buscar..."
          class="search-bar"
        />
        <button class="nav-calendar">Sesión semanal</button>
        <button class="nav-button">Sesión del día</button>
        <button class="nav-button">🛒</button>
        <button class="nav-button">Ícono login</button>
      </nav>
  
      <!-- Contenido principal -->
      <div class="content">
        <h1 class="title">Todas las Películas</h1>
  
        <div v-if="movies.length" class="movies-list">
          <div
            v-for="movie in filteredMovies"
            :key="movie.id"
            class="movie-card"
            @click="goToSessions(movie.id)"
          >
            <img
              :src="movie.poster_url"
              :alt="movie.title"
              class="movie-poster"
            />
            <h3>{{ movie.title }}</h3>
          </div>
        </div>
  
        <p v-else class="loading-text">Cargando películas...</p>
      </div>
  
      <!-- Footer -->
      <footer class="footer">
        © 2025 CineApp - Todos los derechos reservados
      </footer>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, computed } from 'vue'
  import { useRouter } from 'vue-router'
  
  const movies = ref([])
  const buscarPeli = ref('')
  const router = useRouter()
  
  // Función para redirigir a la página de detalles de la película
  const goToSessions = (movieId) => {
    router.push(`/movie/${movieId}`)
  }
  
  // Computed property para filtrar películas según la búsqueda
  const filteredMovies = computed(() => {
    return movies.value.filter(movie =>
      movie.title.toLowerCase().includes(buscarPeli.value.toLowerCase())
    )
  })
  
  onMounted(async () => {
    try {
      const response = await fetch('http://cinemix.daw.inspedralbes.cat/laravel/public/api/movies')
      if (!response.ok) {
        throw new Error(`Error HTTP: ${response.status}`)
      }
      const data = await response.json()
  
      if (Array.isArray(data)) {
        movies.value = data.map(movie => ({
          id: movie.id,
          title: movie.titulo || "Título no disponible",
          poster_url: movie.url_poster || "https://via.placeholder.com/200x300?text=No+Image",
        }))
      } else {
        console.warn("⚠️ No hay películas disponibles")
        movies.value = []
      }
    } catch (error) {
      console.error('Error cargando las películas:', error)
    }
  })
  </script>
  
  <style scoped>
  /* Contenedor principal */
  .container {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    background-color: #e5e7eb;
  }
  
  /* Navbar */
  .navbar {
    background-color: gray;
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
    color: #666;
    font-weight: bold;
    margin-top: 20px;
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
  