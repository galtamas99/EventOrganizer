<script setup>
  import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
  import { onMounted, ref, computed } from 'vue'
  import { eventService } from '@/services/event.service'
  import BookingModal from '@/components/Booking/BookingModal.vue'
  import router from '@/router'
  import { bookingService } from '@/services/booking.service'

  const eventData = ref([])
  const searchText = ref('')
  const searchTextPrev = ref('')
  const currentPage = ref(1)
  const pageSize = ref(10)
  const meta = ref({ current_page: 1, last_page: 1, per_page: 10, total: 0, from: 0, to: 0 })
  let debounce = null
  const user = JSON.parse(localStorage.getItem('user'))
  const bookingModalOpen = ref(false)
  const selectedEvent = ref({})

  const getEventData = () => {
    eventService.getEvents(searchText.value, currentPage.value - 1, pageSize.value).then((response) => {
      eventData.value = response.data.data
      meta.value = response.data.meta
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

  const openBookingModal = (event) => {
    selectedEvent.value = event
    bookingModalOpen.value = true
  }

  const navigateToEventEdit = (eventId) => {
    if (user.roles[0] === 'admin' || user.roles[0] === 'organizer') {
      router.push(`/events/${eventId}/edit`)
    }
  }

  const saveBooking = (quantity) => {
    bookingService
      .createBooking({
        event_id: selectedEvent.value.id,
        quantity: quantity,
        user_id: user.id,
      })
      .then((response) => {
        if (response.status === 201) {
          bookingModalOpen.value = false
          alert('Booking created successfully')
        }
      })
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
    getEventData()
  }
  const prevPage = () => gotoPage((currentPage.value || 1) - 1)
  const nextPage = () => gotoPage((currentPage.value || 1) + 1)

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
            v-if="user.roles[0] === 'admin' || user.roles[0] === 'organizer'"
            @click="router.push('/events/create')"
            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition hover:cursor-pointer"
          >
            Create New Event
          </button>
        </div>
      </div>
      <div v-if="Object.keys(eventData).length > 0">
        <div
          v-for="event in eventData"
          :key="event.id"
          class="mb-4 p-4 border rounded-xl flex gap-3 mb-3 w-full hover:cursor-pointer hover:bg-stone-600"
          @click="navigateToEventEdit(event.id)"
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
          <div v-if="user.roles[0] === 'user'" class="flex items-center">
            <button
              @click="openBookingModal(event)"
              class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition hover:cursor-pointer"
            >
              Book
            </button>
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
      <div v-else class="text-center text-gray-300 text-xl">No events found.</div>
    </div>
    <BookingModal v-if="bookingModalOpen" :eventTitle="selectedEvent.title" @cancel="bookingModalOpen = false" @confirm="saveBooking" />
  </LayoutAuthenticated>
</template>
