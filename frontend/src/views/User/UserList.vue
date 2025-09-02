<script setup>
  import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
  import { onMounted, ref } from 'vue'
  import { userService } from '@/services/user.service'

  const usersData = ref([])
  const searchText = ref('')
  const searchTextPrev = ref('')
  const currentPage = ref(1)
  let debounce = null

  const getUsersData = () => {
    userService.getUsers(searchText.value, currentPage.value - 1).then((response) => {
      usersData.value = response.data.data
    })
  }

  const debounceSearch = () => {
    clearTimeout(debounce)
    debounce = setTimeout(() => {
      if (searchText.value != searchTextPrev.value) {
        currentPage.value = 1
        getUsersData()
      }
    }, 800)
  }

  onMounted(() => {
    getUsersData()
  })
</script>

<template>
  <LayoutAuthenticated>
    <div class="container p-4 w-full">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold mb-4">User List</h1>
        <div class="flex gap-3">
          <input @input="debounceSearch" v-model="searchText" type="text" placeholder="Search users..." class="border p-2 rounded w-64" />
          <button
            @click="$router.push('/users/create')"
            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition hover:cursor-pointer"
          >
            Create New User
          </button>
        </div>
      </div>
      <div
        v-for="user in usersData"
        :key="user.id"
        class="mb-4 p-4 border rounded-xl flex gap-3 mb-3 w-full hover:cursor-pointer hover:bg-stone-600"
        @click="$router.push(`/users/${user.id}/edit`)"
      >
        <div class="flex flex-col w-1/4">
          <p>Name</p>
          <h2 class="text-xl font-semibold">{{ user.name }}</h2>
        </div>
        <div class="flex flex-col w-1/4">
          <p>Email</p>
          <p class="">{{ user.email }}</p>
        </div>
        <div class="flex flex-col w-1/4">
          <p>Created at</p>
          <p class="">{{ user.created_at }}</p>
        </div>
        <div class="flex flex-col w-1/4">
          <p>Updated at</p>
          <p class="">{{ user.updated_at }}</p>
        </div>
      </div>
    </div>
  </LayoutAuthenticated>
</template>
