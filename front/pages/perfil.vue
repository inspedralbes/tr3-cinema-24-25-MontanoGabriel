<template>
  <div class="profile-container" v-if="user">
    <div class="profile-card">
      <h1>Bienvenido, <span class="user-name">{{ user.name }}</span></h1>
      <p class="user-email">📧 {{ user.email }}</p>

      <!-- Botón para cerrar sesión -->
      <button @click="logout" class="logout-button">Cerrar sesión</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'nuxt/app'

const user = ref(null)
const router = useRouter()

// Función para obtener la información del usuario
const fetchUser = async () => {
  const token = localStorage.getItem('auth_token')
  if (!token) {
    router.push('/login')
    return
  }

  try {
    const response = await fetch('http://cinemix.daw.inspedralbes.cat/laravel/public/api/user', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      }
    })

    if (!response.ok) {
      throw new Error('No se pudo obtener la información del usuario')
    }

    user.value = await response.json()
  } catch (error) {
    console.error('Error al obtener el usuario:', error)
    router.push('/login')
  }
}

// Función para cerrar sesión
const logout = async () => {
  const token = localStorage.getItem('auth_token')
  if (!token) {
    router.push('/login')
    return
  }

  try {
    const response = await fetch('http://cinemix.daw.inspedralbes.cat/laravel/public/api/logout', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      }
    })

    if (!response.ok) {
      throw new Error('Error al cerrar sesión')
    }

    // Elimina el token y redirige al inicio
    localStorage.removeItem('auth_token')
    router.push('/')
  } catch (error) {
    console.error('Error al cerrar sesión:', error)
  }
}

onMounted(() => {
  fetchUser()
})
</script>

<style scoped>
/* Contenedor principal */
.profile-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background: linear-gradient(to right, #0d47a1, #1976d2); /* Fondo con degradado azul */
}

/* Tarjeta de perfil */
.profile-card {
  background: white;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
  text-align: center;
  width: 350px;
  transition: transform 0.3s ease;
}

.profile-card:hover {
  transform: scale(1.02); /* Efecto de agrandar al pasar el mouse */
}

/* Nombre del usuario */
.user-name {
  color: #0d47a1;
  font-weight: bold;
}

/* Email del usuario */
.user-email {
  font-size: 16px;
  color: #555;
  margin-bottom: 20px;
}

/* Botón de cerrar sesión */
.logout-button {
  background-color: #d32f2f; /* Rojo fuerte */
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.logout-button:hover {
  background-color: #b71c1c; /* Rojo más oscuro */
  transform: scale(1.05);
}
</style>
