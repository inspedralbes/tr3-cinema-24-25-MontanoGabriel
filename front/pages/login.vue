<template>
  <div class="login-container">
    <h2>Iniciar sesión</h2>
    <form @submit.prevent="login">
      <div class="input-group">
        <label for="email">Correo electrónico</label>
        <input type="email" v-model="email" required />
      </div>
      <div class="input-group">
        <label for="password">Contraseña</label>
        <input type="password" v-model="password" required />
      </div>
      <button type="submit" class="login-button">Iniciar sesión</button>
    </form>
    <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
    <h3>¿No tienes cuenta? <a href="/register">Regístrate aquí</a></h3>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'nuxt/app';

const email = ref('');
const password = ref('');
const errorMessage = ref('');
const router = useRouter();
definePageMeta({
  layout: false,
})

const login = async () => {
  try {
    const response = await fetch('http://cinemix.daw.inspedralbes.cat/laravel/public/api/login', {
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
      localStorage.setItem('auth_token', data.token);
      router.push('/');
    } else {
      errorMessage.value = data.message;
    }
  } catch (error) {
    errorMessage.value = 'Error al iniciar sesión';
  }
};
</script>

<style scoped>
.login-container {
  max-width: 400px;
  margin: 50px auto;
  padding: 20px;
  background: #ffffff;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  text-align: center;
}

h2 {
  color: #0d47a1;
  margin-bottom: 20px;
}

.input-group {
  margin-bottom: 15px;
  text-align: left;
}

label {
  display: block;
  font-weight: bold;
  color: #0d47a1;
  margin-bottom: 5px;
}

input {
  width: 100%;
  padding: 10px;
  border: 1px solid #1976d2;
  border-radius: 5px;
  font-size: 16px;
  outline: none;
}

input:focus {
  border-color: #0d47a1;
  box-shadow: 0 0 5px rgba(13, 71, 161, 0.5);
}

.login-button {
  width: 100%;
  padding: 10px;
  background: #0d47a1;
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
  transition: background 0.3s;
}

.login-button:hover {
  background: #1976d2;
}

.error-message {
  color: red;
  font-size: 14px;
  margin-top: 10px;
}

h3 {
  margin-top: 15px;
  font-size: 14px;
}

a {
  color: #1976d2;
  text-decoration: none;
  font-weight: bold;
}

a:hover {
  text-decoration: underline;
}
</style>
