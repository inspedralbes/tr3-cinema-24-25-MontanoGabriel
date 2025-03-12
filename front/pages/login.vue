<template>
  <div class="login-container">
    <h2>Iniciar sesión</h2>
    <form @submit.prevent="login">
      <div>
        <label for="email">Correo electrónico</label>
        <input type="email" v-model="email" required />
      </div>
      <div>
        <label for="password">Contraseña</label>
        <input type="password" v-model="password" required />
      </div>
      <button type="submit">Iniciar sesión</button>
    </form>
    <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>

    <h3>¿No tienes cuenta? <a href="/register">Regístrate aquí</a></h3>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'nuxt/app'

const email = ref('')
const password = ref('')
const errorMessage = ref('')
const router = useRouter()


// Ejemplo con Nuxt.js usando fetch

const login = async () => {
  try {
    const response = await fetch('http://localhost:8000/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        email: email.value,
        password: password.value,
      }),
    });

    const data = await response.json();

    if (response.ok) {
      // Guardar el token en el localStorage
      localStorage.setItem('auth_token', data.token);
      // Redirigir al usuario a la página principal
      router.push('/');
    } else {
      errorMessage.value = data.message; // Mostrar mensaje de error
    }
  } catch (error) {
    errorMessage.value = 'Error al iniciar sesión';
  }
};

</script>

<style scoped>
.login-container {
  max-width: 400px;
  margin: 0 auto;
  padding: 20px;
  text-align: center;
}

.error-message {
  color: red;
  font-size: 14px;
}
</style>
