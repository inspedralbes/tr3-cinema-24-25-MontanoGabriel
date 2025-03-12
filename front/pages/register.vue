<template>
  <div class="register-container">
    <h2>Regístrate</h2>
    <form @submit.prevent="register">
      <div>
        <label for="name">Nombre</label>
        <input type="text" v-model="name" required />
      </div>
      <div>
        <label for="email">Correo electrónico</label>
        <input type="email" v-model="email" required />
      </div>
      <div>
        <label for="password">Contraseña</label>
        <input type="password" v-model="password" required />
      </div>
      <div>
        <label for="password_confirmation">Confirmar Contraseña</label>
        <input type="password" v-model="passwordConfirmation" required />
      </div>
      <button type="submit">Registrarse</button>
    </form>
    
    <!-- Mostrar mensaje de error -->
    <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>

    <h3>¿Ya tienes cuenta? <a href="/login">Inicia sesión aquí</a></h3>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router' // Usar 'vue-router' ya que estamos trabajando con Nuxt.js 3

const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const errorMessage = ref('')
const router = useRouter() // Usamos router para hacer la redirección después del registro

// Función de registro
const register = async () => {
  // Comprobamos si las contraseñas coinciden antes de enviar el formulario
  if (password.value !== passwordConfirmation.value) {
    errorMessage.value = "Las contraseñas no coinciden.";
    return;
  }

  try {
    const response = await fetch('http://localhost:8000/api/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: passwordConfirmation.value, // Añadimos la confirmación de la contraseña
      })
    });

    const data = await response.json();

    if (!response.ok) {
      // Accedemos a los errores y mostramos el mensaje apropiado
      if (data.errors && data.errors.password) {
        errorMessage.value = "Necesitas 6 caracteres para la contraseña."; // Mensaje personalizado
      } else {
        errorMessage.value = "Error al registrar el usuario";
      }
    } else {
      // Redirigimos al login si la respuesta es exitosa
      router.push('/login');
    }

  } catch (error) {
    errorMessage.value = "Error al registrar el usuario";
  }
};
</script>

<style scoped>
.error-message {
  color: red;
  font-size: 14px;
  margin-top: 10px;
}
</style>


  
  <style scoped>
  .register-container {
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
  