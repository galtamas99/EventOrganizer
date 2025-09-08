<script setup>
  import { ref } from 'vue'
  import { api } from '@/services/api'
  import router from '@/router/index'

  const loginForm = ref({
    email: '',
    password: '',
  })

  const login = () => {
    api
      .post('/api/auth/login', { email: loginForm.value.email, password: loginForm.value.password })
      .then((response) => {
        if (response.status === 200 && response.data.access_token) {
          localStorage.setItem('access_token', response.data.access_token)
          localStorage.setItem('user', JSON.stringify(response.data.user))
          router.push('/dashboard')
        }
      })
      .catch((error) => {
        console.error('Login failed:', error)
      })
  }
</script>

<template>
  <div class="flex flex-col items-center justify-center min-h-screen bg-gray-300">
    <div class="flex flex-col items-center bg-white p-10 rounded-lg shadow-md space-y-4">
      <h1 class="text-2xl text-black font-bold">Login</h1>
      <input
        v-model="loginForm.email"
        type="email"
        placeholder="Email"
        class="bg-gray-300 p-3 rounded-lg text-black border border-gray-500"
      />
      <input
        v-model="loginForm.password"
        type="password"
        placeholder="Password"
        class="bg-gray-300 p-3 rounded-lg text-black border border-gray-500"
      />
      <button
        class="flex items-center justify-center h-10 p-3 w-20 bg-gray-200 text-black hover:cursor-pointer rounded-lg hover:bg-gray-300 border border-gray-500"
        @click="login"
      >
        Login
      </button>
    </div>
  </div>
</template>
