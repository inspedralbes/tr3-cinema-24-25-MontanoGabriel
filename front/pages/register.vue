<template>
  <div class="register-container">
    <h2>Regístrate</h2>
    <form @submit.prevent="register">
      <div class="input-group">
        <label for="name">Nombre</label>
        <input type="text" v-model="name" required />
      </div>
      <div class="input-group">
        <label for="email">Correo electrónico</label>
        <input type="email" v-model="email" required />
      </div>
      <div class="input-group">
        <label for="password">Contraseña</label>
        <input type="password" v-model="password" required />
      </div>
      <div class="input-group">
        <label for="password_confirmation">Confirmar Contraseña</label>
        <input type="password" v-model="passwordConfirmation" required />
      </div>
      <button type="submit" class="register-button">Registrarse</button>
    </form>

    <!-- Mostrar mensaje de error -->
    <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>

    <h3>¿Ya tienes cuenta? <a href="/login">Inicia sesión aquí</a></h3>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const errorMessage = ref('')
const router = useRouter()

definePageMeta({
  layout: false,
})

// Función de registro
const register = async () => {
  if (password.value !== passwordConfirmation.value) {
    errorMessage.value = "Las contraseñas no coinciden.";
    return;
  }

  try {
    const response = await fetch('http://cinemix.daw.inspedralbes.cat/laravel/public/api/register', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: passwordConfirmation.value,
      })
    });

    const data = await response.json();

    if (!response.ok) {
      errorMessage.value = data.errors?.password 
        ? "Necesitas 6 caracteres para la contraseña." 
        : "Error al registrar el usuario";
    } else {
      router.push('/login');
    }
  } catch (error) {
    errorMessage.value = "Error al registrar el usuario";
  }
};
</script>

<style scoped>
.register-container {
  max-width: 400px;
  margin: 50px auto;
  padding: 30px;
  background: white;
  border-radius: 10px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
  text-align: center;
}

h2 {
  color: #0d47a1;
  margin-bottom: 20px;
}

.input-group {
  display: flex;
  flex-direction: column;
  text-align: left;
  margin-bottom: 15px;
}

label {
  font-weight: bold;
  color: #0d47a1;
  margin-bottom: 5px;
}

input {
  padding: 10px;
  border: 2px solid #1976d2;
  border-radius: 5px;
  font-size: 16px;
  outline: none;
  transition: border 0.3s ease;
}

input:focus {
  border-color: #0d47a1;
}

.register-button {
  background: #1976d2;
  color: white;
  border: none;
  padding: 12px;
  font-size: 16px;
  border-radius: 5px;
  cursor: pointer;
  width: 100%;
  transition: background 0.3s;
}

.register-button:hover {
  background: #0d47a1;
}

.error-message {
  color: red;
  font-size: 14px;
  margin-top: 10px;
  font-weight: bold;
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
