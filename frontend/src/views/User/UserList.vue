<script setup>
  import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
  import { onMounted, ref, computed } from 'vue'
  import { userService } from '@/services/user.service'

  const usersData = ref([])
  const searchText = ref('')
  const searchTextPrev = ref('')
  const currentPage = ref(1)
  const pageSize = ref(10)
  const meta = ref({ current_page: 1, last_page: 1, per_page: 10, total: 0, from: 0, to: 0 })
  let debounce = null

  const getUsersData = () => {
    userService.getUsers(searchText.value, currentPage.value - 1, pageSize.value).then((response) => {
      usersData.value = response.data.data
      meta.value = response.data.meta
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

  const pages = computed(() => {
    const total = meta.value.last_page || 1
    const cur = meta.value.current_page || 1
    const delta = 2
    const left = Math.max(1, cur - delta)
    const right = Math.min(total, cur + delta)
    const range = []
    for (let i = left; i <= right; i++) range.push(i)
    if (left > 1) range.unshift(1)
    if (left > 2) range.splice(1, 0, '...')
    if (right < total - 1) range.push('...')
    if (right < total) range.push(total)
    return range
  })

  const gotoPage = (p) => {
    if (p === '...') return
    if (p < 1 || p > (meta.value.last_page || 1)) return
    currentPage.value = p
    getUsersData()
  }
  const prevPage = () => gotoPage((currentPage.value || 1) - 1)
  const nextPage = () => gotoPage((currentPage.value || 1) + 1)

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
        </div>
      </div>
      <div v-if="Object.keys(usersData).length > 0">
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
        <div class="mt-6 flex items-center justify-between">
          <div class="text-sm text-gray-300">Showing {{ meta.from || 0 }}–{{ meta.to || 0 }} of {{ meta.total || 0 }}</div>

          <nav class="inline-flex items-center gap-1">
            <button
              class="px-3 py-1 rounded border hover:bg-gray-700 disabled:opacity-50"
              :disabled="(meta.current_page || 1) <= 1"
              @click="prevPage"
            >
              Prev
            </button>

            <button
              v-for="p in pages"
              :key="p + ''"
              class="px-3 py-1 rounded border hover:bg-gray-700 disabled:opacity-50"
              :class="p === meta.current_page ? 'bg-gray-700' : ''"
              :disabled="p === '...'"
              @click="gotoPage(p)"
            >
              {{ p }}
            </button>

            <button
              class="px-3 py-1 rounded border hover:bg-gray-700 disabled:opacity-50"
              :disabled="(meta.current_page || 1) >= (meta.last_page || 1)"
              @click="nextPage"
            >
              Next
            </button>
          </nav>
        </div>
      </div>
      <div v-else class="text-center text-gray-300 text-xl">No users found.</div>
    </div>
  </LayoutAuthenticated>
</template>
