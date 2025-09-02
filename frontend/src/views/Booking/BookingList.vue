<script setup>
  import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
  import { onMounted, ref } from 'vue'
  import { bookingService } from '@/services/booking.service'

  const bookingData = ref([])
  const searchText = ref('')
  const searchTextPrev = ref('')
  const currentPage = ref(1)
  let debounce = null

  const getBookingsData = () => {
    bookingService.getBookings(searchText.value, currentPage.value - 1).then((response) => {
      bookingData.value = response.data.data
    })
  }

  const debounceSearch = () => {
    clearTimeout(debounce)
    debounce = setTimeout(() => {
      if (searchText.value != searchTextPrev.value) {
        currentPage.value = 1
        getBookingsData()
      }
    }, 800)
  }

  onMounted(() => {
    getBookingsData()
  })
</script>

<template>
  <LayoutAuthenticated>
    <div class="container p-4 w-full">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold mb-4">Booking List</h1>
        <div class="flex gap-3">
          <input
            @input="debounceSearch"
            v-model="searchText"
            type="text"
            placeholder="Search bookings..."
            class="border p-2 rounded w-64"
          />
        </div>
      </div>
      <div
        v-for="booking in bookingData"
        :key="booking.id"
        class="mb-4 p-4 border rounded-xl flex gap-3 mb-3 w-full hover:cursor-pointer hover:bg-stone-600"
        @click="$router.push(`/bookings/${booking.id}/edit`)"
      >
        <div class="flex flex-col w-1/4">
          <p>User</p>
          <h2 class="text-xl font-semibold">{{ booking.user.email }}</h2>
        </div>
        <div class="flex flex-col w-1/4">
          <p>Event</p>
          <p class="">{{ booking.event.title }}</p>
        </div>
        <div class="flex flex-col w-1/4">
          <p>Quantity</p>
          <p class="">{{ booking.quantity }}</p>
        </div>
        <div class="flex flex-col w-1/4">
          <p>Total price</p>
          <p class="">{{ booking.total_price }}</p>
        </div>
      </div>
    </div>
  </LayoutAuthenticated>
</template>
