<template>
    <div class="container">
      <!-- Navbar -->
      <nav class="navbar">
        <button class="nav-button" @click="goBack">⬅ Volver</button>
        <h1>Compra de Entradas</h1>
      </nav>
  
      <!-- Información de la Película -->
      <div v-if="movie" class="movie-details">
        <!-- Póster a la izquierda -->
        <div class="poster">
          <img :src="movie.poster_url" :alt="movie.title" />
        </div>
  
        <!-- Información en el centro -->
        <div class="info">
          <h2>{{ movie.title }}</h2>
          <p>{{ movie.description }}</p>
        </div>
  
        <!-- Detalle (por ejemplo, duración) a la derecha -->
        <div class="duration">
          <h3>Duración:</h3>
          <p>{{ movie.duration }} minutos</p>
        </div>
      </div>
  
      <!-- Selección de Horario (por ejemplo, 16:00 y 18:00) -->
      <div class="schedule-container">
        <p class="selected-hour" v-if="selectedHour">Horario seleccionado: {{ selectedHour }}</p>
      </div>
  
      <!-- Mapa de Butacas -->
      <div class="seat-selection">
        <h3>Selecciona tus asientos (máx. 10):</h3>
        <div class="screen">🎬 Pantalla</div>
        <div class="seating-chart">
          <div
            v-for="(row, rowIndex) in seats"
            :key="rowIndex"
            class="seat-row"
          >
            <span class="row-label">{{ rowLabels[rowIndex] }}</span>
            <div
              v-for="(seat, seatIndex) in row"
              :key="seatIndex"
              :class="['seat', seat.status]"
              @click="toggleSeat(rowIndex, seatIndex)"
            >
              {{ seatIndex + 1 }}
            </div>
          </div>
        </div>
        <p v-if="selectedSeats.length" class="selected-seats">
          Asientos seleccionados: {{ selectedSeats.join(', ') }}
        </p>
      </div>
  
      <!-- Formulario de Datos Personales -->
      <div class="form-container">
        <h3>Introduce tus datos</h3>
        <input type="text" v-model="user.name" placeholder="Nombre" required />
        <input type="text" v-model="user.surname" placeholder="Apellido" required />
        <input type="tel" v-model="user.phone" placeholder="Teléfono" required />
      </div>
  
      <!-- Botón para Confirmar Compra -->
      <div class="buy-button-container">
        <button class="buy-button" @click="finalizarCompra" :disabled="!canComprar">
          Confirmar Compra
        </button>
      </div>
  
      <!-- Mensaje de Confirmación o Error -->
      <div v-if="message" class="message">
        {{ message }}
      </div>
  
      <!-- Footer -->
      <footer class="footer">
        © 2025 CineApp - Todos los derechos reservados
      </footer>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, computed } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  
  
  const route = useRoute()
  const router = useRouter()
  
  
  // Datos de la película
  const movie = ref(null)
  
  // Hora seleccionada (obtenida de la query o por elección)
  const selectedHour = ref(route.query.hora || '')
  
  // Datos de usuario
  const user = ref({ name: "", surname: "", phone: "" })
  
  // Mapa de butacas: 12 filas (A-L) x 10 columnas
  const rowLabels = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L']
  const seats = ref(
    Array.from({ length: 12 }, () =>
      Array.from({ length: 10 }, () => ({ status: "available" }))
    )
  )
  const selectedSeats = ref([])
  
  // Límite de butacas por sesión
  const maxSeats = 10
  
  // Obtener detalles de la película
  onMounted(async () => {
    try {
      const apiUrl = `http://localhost:8000/api/movie/${route.params.id}`
      const response = await fetch(apiUrl)
      if (!response.ok) {
        throw new Error(`Error al obtener los detalles de la película: ${response.status}`)
      }
      const data = await response.json()
      movie.value = {
        title: data.titulo || 'Título no disponible',
        description: data.descripcion || 'Descripción no disponible',
        duration: data.duracion || 'Duración no disponible',
        poster_url: data.url_poster || 'https://via.placeholder.com/200x300?text=No+Image'
      }
  
      // (Opcional) Si deseas obtener los asientos ocupados desde la API, podrías hacerlo aquí
      // Ejemplo: const seatsResponse = await axios.get(`http://localhost:8000/api/seats/${route.params.id}`)
      // Luego marcar como "occupied" los asientos correspondientes.
  
    } catch (error) {
      console.error('Error al cargar los detalles de la película:', error)
    }
  })
  
  // Función para regresar
  const goBack = () => {
    router.go(-1)
  }
  
  // Función para seleccionar el horario
  const setHour = (hora) => {
    selectedHour.value = hora
  }
  
  // Alternar selección de butacas
  const toggleSeat = (rowIndex, seatIndex) => {
    const seat = seats.value[rowIndex][seatIndex]
    const seatLabel = `${rowLabels[rowIndex]}${seatIndex + 1}`
  
    // No permitir selección si el asiento está ocupado
    if (seat.status === "occupied") return
  
    if (seat.status === "selected") {
      seat.status = "available"
      selectedSeats.value = selectedSeats.value.filter(s => s !== seatLabel)
    } else if (selectedSeats.value.length < maxSeats) {
      seat.status = "selected"
      selectedSeats.value.push(seatLabel)
    }
  }
  
  // Habilitar botón de compra solo si hay asientos seleccionados y se ha elegido horario y datos de usuario
  const canComprar = computed(() => {
    return (
      selectedSeats.value.length > 0 &&
      selectedHour.value &&
      user.value.name.trim() !== "" &&
      user.value.surname.trim() !== "" &&
      user.value.phone.trim() !== ""
    )
  })
  
  // Función para confirmar la compra
  const finalizarCompra = async () => {
    
  if (!canComprar.value) {
    alert("Completa todos los campos y selecciona al menos un asiento y un horario.");
    return;
  }

  // El formato de los asientos debería ser [{ row: "A", seat: 1 }, { row: "A", seat: 2 }]
  const selectedSeats = [
    { row: "A", seat: 1 },
    { row: "A", seat: 2 }
  ];

  try {
    const response = await fetch("http://localhost:8000/api/compra", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        session_id: selectedSessionId,  // Este valor debe ser el id de la sesión de la tabla sessionmovies
        seats: selectedSeats,           // Los asientos seleccionados con formato { row, seat }
        user_id: user.value.id,         // El id del usuario que compra las entradas
        hour: selectedHour.value,       // La hora seleccionada
      }),
    });

    if (response.ok) {
      const data = await response.json();
      message.value = data.message || "Compra realizada con éxito.";
    } else {
      const errorData = await response.json();
      message.value = errorData.message || "Error al procesar la compra.";
    }
  } catch (error) {
    console.error("Error al comprar:", error);
    message.value = "Error al procesar la compra.";
  }
};





  
  const message = ref("")
  </script>
  
  <style scoped>
  /* Contenedor principal */
  .container {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    background-color: #e5e7eb;
    text-align: center;
    padding: 20px;
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
  
  /* Detalles de la película */
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
    margin-bottom: 20px;
  }
  
  .poster img {
    width: 200px;
    border-radius: 8px;
  }
  
  .info {
    flex: 1;
    padding: 0 20px;
    text-align: left;
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
  
  /* Horario */
  .schedule-container {
    text-align: center;
    margin-bottom: 20px;
  }
  
 
  
  
  
  
  
 
  
  /* Mapa de butacas */
  .seat-selection {
    margin: 20px 0;
  }
  
  .screen {
    background-color: #444;
    color: #fff;
    padding: 8px;
    border-radius: 5px;
    margin-bottom: 10px;
    font-weight: bold;
  }
  
  .seating-chart {
    display: flex;
    flex-direction: column;
    gap: 5px;
    align-items: center;
  }
  
  .seat-row {
    display: flex;
    align-items: center;
    gap: 5px;
  }
  
  .row-label {
    font-weight: bold;
    width: 20px;
    text-align: center;
  }
  
  .seat {
    width: 35px;
    height: 35px;
    border-radius: 5px;
    text-align: center;
    line-height: 35px;
    font-size: 14px;
    cursor: pointer;
    transition: transform 0.2s;
  }
  
  .seat.available {
    background-color: lightgray;
  }
  
  .seat.occupied {
    background-color: red;
    cursor: not-allowed;
  }
  
  .seat.selected {
    background-color: green;
    color: white;
    transform: scale(1.1);
  }
  
  /* Formulario de datos */
  .form-container {
    margin: 20px 0;
  }
  
  .form-container input {
    margin: 5px;
    padding: 8px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  
  /* Botón de compra */
  .buy-button-container {
    margin: 20px 0;
  }
  
  .buy-button {
    background-color: red;
    color: white;
    padding: 10px 20px;
    font-size: 18px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  
  .buy-button:hover {
    background-color: darkred;
  }
  
  .buy-button:disabled {
    background-color: gray;
    cursor: not-allowed;
  }
  
  /* Mensaje */
  .message {
    color: green;
    font-weight: bold;
    margin-top: 10px;
  }
  
  /* Footer */
  .footer {
    background-color: #a1a1a1;
    padding: 15px;
    text-align: center;
    color: black;
    font-weight: bold;
    margin-top: auto;
  }
  </style>
  