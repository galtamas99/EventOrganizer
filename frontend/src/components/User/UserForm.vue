<script setup>
  import router from '@/router'
  import { userService } from '@/services/user.service'
  import { ref, onBeforeMount } from 'vue'

  const props = defineProps({
    userId: {
      type: String,
      default: '',
    },
  })

  const userData = ref({})
  const rolesOptions = ref(['admin', 'user', 'organizer'])

  const getUserData = () => {
    if (props.userId) {
      userService.getUserById(props.userId).then((response) => {
        if (response.status === 200) {
          userData.value = response.data.data
        }
      })
    }
  }

  const saveuser = () => {
    if (props.userId) {
      userService.updateUser(props.userId, userData.value).then((response) => {
        if (response.status === 200) {
          alert('User updated successfully')
        }
      })
    } else {
      userService.createUser(userData.value).then((response) => {
        if (response.status === 201) {
          router.push(`/users/${response.data.data.id}/edit`)
          alert('User created successfully')
        }
      })
    }
  }

  const deleteUser = () => {
    if (props.userId) {
      if (confirm('Are you sure you want to delete this user?')) {
        userService.deleteUser(props.userId).then((response) => {
          if (response.status === 204) {
            router.push('/users')
            alert('User deleted successfully')
          }
        })
      }
    }
  }

  onBeforeMount(() => {
    getUserData()
  })
</script>

<template>
  <div class="container p-4 w-full">
    <div class="flex items-center justify-between gap-6 mb-6">
      <div class="flex items-center gap-3">
        <button
          @click="$router.push('/users')"
          class="mb-4 px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400 transition hover:cursor-pointer"
        >
          Back
        </button>
        <h1 class="text-2xl font-bold mb-4">{{ userId ? 'Edit User' : 'Create User' }}</h1>
      </div>
      <div class="flex items-center gap-3">
        <button @click="saveuser" class="mb-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition hover:cursor-pointer">
          Save
        </button>
        <button @click="deleteUser" class="mb-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition hover:cursor-pointer">
          Delete
        </button>
      </div>
    </div>
    <div class="w-full flex justify-center">
      <div class="bg-white p-6 rounded-lg shadow-md w-1/2 grid grid-cols-2 gap-3">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
          <input
            v-model="userData.name"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            id="name"
            type="text"
            placeholder="User name"
          />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
          <input
            v-model="userData.email"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            id="email"
            placeholder="User email"
            type="email"
          />
        </div>
        <div class="mb-4" v-if="userData.roles && userData.roles.length > 0">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="roles">Roles</label>
          <select
            v-model="userData.roles[0]"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            id="roles"
            placeholder="User roles"
          >
            <option v-for="option in rolesOptions" :key="option" :value="option">{{ option }}</option>
          </select>
        </div>
      </div>
    </div>
  </div>
</template>
