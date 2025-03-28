<template>
    <div class="profile-container" v-if="user">
      <h1>Bienvenido, {{ user.name }}</h1>
      <p>Email: {{ user.email }}</p>
      
       <!-- Botón para desloguearse -->
    <button @click="logout" class="logout-button">Cerrar sesión</button>
  
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import { useRouter } from 'nuxt/app'
  
  const user = ref(null)
  const router = useRouter()
//   Funcion para obtener la informacion de usuario
  const fetchUser = async () => {
    const token = localStorage.getItem('auth_token')
    if (!token) {
      // Si no hay token, redirige al login
      router.push('/login')
      return
    }

    try {
      const response = await fetch('http://localhost:8000/api/user', {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`  // Envia el token en el header
        }
      })
  
      if (!response.ok) {
        throw new Error('No se pudo obtener la información del usuario')
      }
  
      user.value = await response.json()
    } catch (error) {
      console.error('Error al obtener el usuario:', error)
      // Si falla la consulta, redirige al login
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
    const response = await fetch('http://localhost:8000/api/logout', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      }
    })

    if (!response.ok) {
      throw new Error('Error al cerrar sesión')
    }

    // Elimina el token y redirige al login
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
  .profile-container {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  text-align: center;
}

.logout-button {
  background-color: red;
  color: white;
  padding: 10px 20px;
  margin-top: 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.logout-button:hover {
  background-color: darkred;
}
  </style>
  