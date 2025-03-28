<template>
  <div class="container">
    <!-- Navbar -->
    <nav class="navbar">
      <button class="nav-button" @click="goBack">⬅ Volver</button>
      <h1>Compra de Entradas</h1>
    </nav>

    <!-- Información de la Película -->
    <div v-if="movie" class="movie-details">
      <img class="poster" :src="movie.poster_url" :alt="movie.title" />
      <div class="info">
        <h2>{{ movie.title }}</h2>
        <p>{{ movie.description }}</p>
        <h3>Duración: {{ movie.duration }} minutos</h3>
      </div>
    </div>

    <!-- Selección de Horario -->
    <p v-if="selectedHour" class="selected-hour">
      Horario seleccionado: {{ selectedHour }}
    </p>

    <!-- Mapa de Butacas -->
    <div class="seat-selection">
      <h3>Selecciona tus asientos (máx. 10):</h3>
      <div class="screen">🎬 Pantalla</div>
      <div class="seating-chart">
        <div v-for="(row, rowIndex) in seats" :key="rowIndex" class="seat-row">
          <span class="row-label">{{ rowLabels[rowIndex] }}</span>
          <div
            v-for="(seat, seatIndex) in row"
            :key="seatIndex"
            :class="[
              'seat',
              seat.status,
              seat.type,
              { occupied: seat.status === 'occupied' },
            ]"
            @click="toggleSeat(rowIndex, seatIndex)"
          >
            <!-- Asiento Ocupado (Rojo) -->
            <div v-if="seat.status === 'occupied'">
              <img
                src="/public/Silla-roja.svg"
                height="30px"
                width="30px"
                alt="Asiento Ocupado"
              />
            </div>

            <!-- Asiento Seleccionado -->
            <div v-else-if="seat.status === 'selected'">
              <img
                src="/public/Silla-verde.svg"
                height="30px"
                width="30px"
                alt="Asiento Seleccionado"
              />
            </div>

            <!-- Asiento VIP (Dorado) -->
            <div v-else-if="seat.type === 'vip'">
              <img src="/public/VIP.png" height="30px" width="30px" alt="" />
            </div>

            <!-- Asiento Normal (Gris) -->
            <div v-else>
              <img
                src="/public/Silla-normal.svg"
                height="30px"
                width="30px"
                alt="Asiento Normal"
              />
            </div>
          </div>
        </div>
      </div>
      <p v-if="selectedSeats.length" class="summary">
        Asientos: {{ selectedSeats.join(", ") }}
      </p>
      <p v-if="selectedSeats.length" class="summary">
        Precio Total: {{ totalPrice.toFixed(2) }} €
      </p>
    </div>

    <!-- Opciones de compra -->
    <div class="purchase-options">
      <button class="purchase-button" @click="isQuickPurchase = true">
        Comprar Rápido
      </button>
      <button class="purchase-button" @click="isQuickPurchase = false">
        Registrarse y Comprar
      </button>
    </div>

    <!-- Formulario de Compra Rápida -->
    <div v-if="isQuickPurchase">
      <h3>Compra Rápida</h3>
      <input v-model="quickPurchaseData.name" placeholder="Nombre" />
      <input v-model="quickPurchaseData.surname" placeholder="Apellido" />
      <input v-model="quickPurchaseData.email" placeholder="Correo" />
      <button @click="finalizarCompraRapida">Comprar Ahora</button>
    </div>

    <!-- Formulario de Registro y Compra -->
    <div v-else-if="!isRegistered">
      <h3>Registrarse y Comprar</h3>
      <input v-model="user.name" placeholder="Nombre" />
      <input v-model="user.surname" placeholder="Apellido" />
      <input v-model="user.email" placeholder="Correo" />
      <button @click="registrarUsuario">Registrarse y Comprar</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from "vue";
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();
definePageMeta({
  layout: false,
});

// Estados para compra rápida y registro
const isQuickPurchase = ref(false);
const isRegistered = ref(false);

// Datos para compra rápida y para usuario autenticado
const quickPurchaseData = ref({ name: "", surname: "", email: "" });
const user = ref({ name: "", surname: "", email: "", id: null });

// Datos de la película
const movie = ref(null);
const selectedHour = ref(route.query.hora || "");
// estado de los asientos seleccionados
const selectedSeats = ref([]);
// nomero de asientos permitidos para seleccionar
const maxSeats = 10;

// Configuración del mapa de butacas
const rowLabels = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L"];
const seats = ref(
  Array.from({ length: 12 }, (_, rowIndex) =>
    Array.from({ length: 10 }, () => ({
      status: "available",
      // Fila 6 (índice 5) es VIP
      type: rowIndex === 5 ? "vip" : "normal",
    }))
  )
);

// Calcular el precio total
const totalPrice = computed(() => {
  return selectedSeats.value.reduce((sum, seatLabel) => {
    const row = rowLabels.indexOf(seatLabel.charAt(0));
    // Si la fila es VIP, precio 8; de lo contrario, 6
    const price = row === 5 ? 8 : 6;
    return sum + price;
  }, 0);
});

// Función para cargar la película
onMounted(async () => {
  try {
    const response = await fetch(
      `http://localhost:8000/api/movie/${route.params.id}`
    );
    if (!response.ok) throw new Error("Error al obtener la película");
    const data = await response.json();
    movie.value = {
      title: data.titulo,
      description: data.descripcion,
      duration: data.duracion,
      poster_url:
        data.url_poster || "https://via.placeholder.com/200x300?text=No+Image",
    };
  } catch (error) {
    console.error(error);
  }
});

// Función para regresar
const goBack = () => router.go(-1);

// Alternar selección de asientos
const toggleSeat = (rowIndex, seatIndex) => {
  const seat = seats.value[rowIndex][seatIndex];
  const seatLabel = `${rowLabels[rowIndex]}${seatIndex + 1}`;

  // Validar si el asiento realmente existe
  if (!seats.value[rowIndex] || !seats.value[rowIndex][seatIndex]) {
    console.error(
      `⚠️ Error: Asiento en fila ${rowIndex}, columna ${seatIndex} no existe.`
    );
    return;
  }
  // No permite seleccionar asientos ocupados
  if (seat.status === "occupied") return;

  // Alternar entre 'selected' y 'available'
  if (seat.status === "selected") {
    seat.status = "available";
    selectedSeats.value = selectedSeats.value.filter((s) => s !== seatLabel);
  } else if (selectedSeats.value.length < maxSeats) {
    seat.status = "selected";
    selectedSeats.value.push(seatLabel);
  }

  // 🔥 Forzar la reactividad en Vue 🔥
  seats.value = [...seats.value];
};

// Guardar asientos seleccionados en localStorage (opcional)
watch(selectedSeats, (newSeats) => {
  localStorage.setItem("selectedSeats", JSON.stringify(newSeats));
});

// Funciones de compra
const validarCompra = (datosUsuario) => {
  if (!datosUsuario.name || !datosUsuario.surname || !datosUsuario.email) {
    alert("Completa todos los campos.");
    return false;
  }
  if (selectedSeats.value.length === 0) {
    alert("Selecciona al menos un asiento.");
    return false;
  }
  if (!selectedHour.value) {
    alert("Selecciona un horario.");
    return false;
  }
  return true;
};

const finalizarCompra = async (datosUsuario) => {
  if (!validarCompra(datosUsuario)) return;

  // Formatear los asientos: { row: "A", seat: 1 }
  const selectedSeatsFormatted = selectedSeats.value.map((label) => ({
    row: label.charAt(0),
    seat: parseInt(label.substring(1), 10),
  }));

  // Construir el cuerpo de la solicitud
  const requestBody = {
    session_movie_id: route.params.id,
    seats: selectedSeatsFormatted,
    user_id: datosUsuario.id, // Para compra normal el usuario ya estará autenticado
    name: quickPurchaseData.value.name,
    surname: quickPurchaseData.value.surname,
    email: quickPurchaseData.value.email,
    total_price: totalPrice.value,
  };

  console.log("Enviando datos:", requestBody);

  try {
    const response = await fetch("http://localhost:8000/api/compra", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(requestBody),
    });
    if (response.ok) {
      const data = await response.json();
      alert("Compra realizada con éxito.");
    } else {
      const errorData = await response.json();
      console.error("Error de la API:", errorData);
      alert("Error al procesar la compra.");
    }
  } catch (error) {
    console.error("Error en la compra:", error);
    alert("Hubo un problema con la compra.");
  }
};

const finalizarCompraRapida = async () => {
  if (!validarCompra(quickPurchaseData.value)) return;
  // Para la compra rápida, no se requiere user_id
  const selectedSeatsFormatted = selectedSeats.value.map((label) => ({
    row: label.charAt(0),
    seat: parseInt(label.substring(1), 10),
  }));

  const requestBody = {
    session_movie_id: route.params.id,
    seats: selectedSeatsFormatted,
    user_id: null, // No hay usuario registrado
    name: quickPurchaseData.value.name,
    surname: quickPurchaseData.value.surname,
    email: quickPurchaseData.value.email,
    total_price: totalPrice.value,
  };

  console.log("Enviando datos (compra rápida):", requestBody);

  try {
    const response = await fetch("http://localhost:8000/api/compra", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(requestBody),
    });
    console.log("Enviando datos (compra rápida):", requestBody);

    if (response.ok) {
      const data = await response.json();
      alert("Compra realizada con éxito.");
    } else {
      const errorData = await response.json();
      console.error("Error de la API:", errorData);
      alert("Error al procesar la compra.");
    }
  } catch (error) {
    console.error("Error en la compra rápida:", error);
    alert("Hubo un problema con la compra rápida.");
  }
};

const registrarUsuario = async () => {
  if (!validarCompra(user.value)) return;
  try {
    const response = await fetch("http://localhost:8000/api/registro", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(user.value),
    });
    if (response.ok) {
      const data = await response.json();
      user.value.id = data.user.id;
      isRegistered.value = true;
      // Luego realizar la compra
      finalizarCompra(user.value);
    } else {
      const errorData = await response.json();
      console.error("Error en el registro:", errorData);
      alert("Error al registrar el usuario.");
    }
  } catch (error) {
    console.error("Error en el registro:", error);
    alert("Hubo un problema con el registro.");
  }
};
const message = ref("");

// Solicitud para obtener los asientos ocupados
onMounted(async () => {
  try {
    const response = await fetch(
      `http://localhost:8000/api/asientos-ocupados/${route.params.id}`
    );
    if (!response.ok) throw new Error("Error al obtener los asientos ocupados");
    const data = await response.json();

    // Almacenar los asientos ocupados
    const asientosOcupados = data.asientosOcupados || [];

    // Marcar los asientos ocupados en el mapa de butacas
    for (const rowIndex in seats.value) {
      for (const seatIndex in seats.value[rowIndex]) {
        const seatLabel = `${rowLabels[rowIndex]}${parseInt(seatIndex) + 1}`;
        if (asientosOcupados.includes(seatLabel)) {
          seats.value[rowIndex][seatIndex].status = "occupied";
        }
      }
    }
  } catch (error) {
    console.error(error);
  }
});
</script>

<style scoped>
.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  min-height: 100vh;
  background-color: #f3f4f6;
  text-align: center;
  padding: 20px;
}

.navbar {
  background-color: #0d47a1;
  padding: 15px;
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: white;
}

.nav-button {
  background-color: #1976d2;
  border: none;
  padding: 10px 15px;
  cursor: pointer;
  border-radius: 5px;
  font-weight: bold;
}

.movie-details {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  max-width: 900px;
  background: white;
  border-radius: 10px;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
}

.poster {
  width: 200px;
  border-radius: 8px;
}

.info {
  flex: 1;
  padding: 0 20px;
  text-align: left;
}

.screen {
  background-color: #374151;
  color: white;
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
  width: 40px;
  height: 40px;
  border-radius: 50%; /* Hace que los asientos sean circulares */
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  cursor: pointer;
  transition: transform 0.2s;
}

.seat.available {
}

.seat.occupied {
  cursor: not-allowed;
}

.seat.vip {
}

.seat.vip.occupied {
}

.seat.selected {
  transform: scale(1.1);
  color: white;
}

.summary {
  font-size: 18px;
  font-weight: bold;
  color: #374151;
}

.purchase-options {
  display: flex;
  gap: 15px;
  margin-top: 20px;
}

.purchase-button {
  background-color: #2563eb;
  color: white;
  padding: 10px 20px;
  font-size: 18px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.purchase-button:hover {
  background-color: #1e40af;
}
</style>
