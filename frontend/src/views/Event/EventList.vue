<script setup>
  import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
  import { onMounted, ref } from 'vue'
  import { eventService } from '@/services/event.service'

  const eventData = ref([])
  const searchText = ref('')
  const searchTextPrev = ref('')
  const currentPage = ref(1)
  let debounce = null

  const getEventData = () => {
    eventService.getEvents(searchText.value, currentPage.value - 1).then((response) => {
      eventData.value = response.data.data
    })
  }

  const debounceSearch = () => {
    clearTimeout(debounce)
    debounce = setTimeout(() => {
      if (searchText.value != searchTextPrev.value) {
        currentPage.value = 1
        getEventData()
      }
    }, 800)
  }

  onMounted(() => {
    getEventData()
  })
</script>

<template>
  <LayoutAuthenticated>
    <div class="container p-4 w-full">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold mb-4">Event List</h1>
        <div class="flex gap-3">
          <input @input="debounceSearch" v-model="searchText" type="text" placeholder="Search events..." class="border p-2 rounded w-64" />
          <button
            @click="$router.push('/events/create')"
            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition hover:cursor-pointer"
          >
            Create New Event
          </button>
        </div>
      </div>
      <div
        v-for="event in eventData"
        :key="event.id"
        class="mb-4 p-4 border rounded-xl flex gap-3 mb-3 w-full hover:cursor-pointer hover:bg-stone-600"
        @click="$router.push(`/events/${event.id}/edit`)"
      >
        <div class="flex flex-col w-1/3">
          <p>Title</p>
          <h2 class="text-xl font-semibold">{{ event.title }}</h2>
        </div>
        <div class="flex flex-col w-1/3">
          <p>Stars at</p>
          <p class="">{{ event.starts_at }}</p>
        </div>
        <div class="flex flex-col w-1/3">
          <p>Description</p>
          <p class="">{{ event.description }}</p>
        </div>
      </div>
    </div>
  </LayoutAuthenticated>
</template>
