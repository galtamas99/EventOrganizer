<script setup>
  import router from '@/router'
  import { bookingService } from '@/services/booking.service'
  import { ref, onBeforeMount } from 'vue'

  const props = defineProps({
    bookingId: {
      type: String,
      default: '',
    },
  })

  const bookingData = ref({})

  const getBookingDara = () => {
    if (props.bookingId) {
      bookingService.getBookingById(props.bookingId).then((response) => {
        if (response.status === 200) {
          bookingData.value = response.data.data
        }
      })
    }
  }

  const saveBooking = () => {
    if (props.bookingId) {
      bookingService.updateBooking(props.bookingId, bookingData.value).then((response) => {
        if (response.status === 200) {
          alert('Booking updated successfully')
        }
      })
    }
  }

  const deleteBooking = () => {
    if (props.bookingId) {
      if (confirm('Are you sure you want to delete this booking?')) {
        bookingService.deleteBooking(props.bookingId).then((response) => {
          if (response.status === 204) {
            router.push('/bookings')
            alert('Booking deleted successfully')
          }
        })
      }
    }
  }

  const calculateTotalPrice = () => {
    if (bookingData.value.event && bookingData.value.quantity) {
      bookingData.value.total_price = bookingData.value.event.ticket_price * bookingData.value.quantity
    } else {
      bookingData.value.total_price = 0
    }
  }

  onBeforeMount(() => {
    getBookingDara()
  })
</script>

<template>
  <div class="container p-4 w-full">
    <div class="flex items-center justify-between gap-6 mb-6">
      <div class="flex items-center gap-3">
        <button
          @click="$router.push('/bookings')"
          class="mb-4 px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400 transition hover:cursor-pointer"
        >
          Back
        </button>
        <h1 class="text-2xl font-bold mb-4">{{ 'Edit Booking' }}</h1>
      </div>
      <div class="flex items-center gap-3">
        <button
          @click="saveBooking"
          class="mb-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition hover:cursor-pointer"
        >
          Save
        </button>
        <button
          @click="deleteBooking"
          class="mb-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition hover:cursor-pointer"
        >
          Delete
        </button>
      </div>
    </div>
    <div class="w-full flex justify-center">
      <div class="bg-white p-6 rounded-lg shadow-md w-1/2 grid grid-cols-2 gap-3">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="name">User email</label>
          <input
            v-if="bookingData.user"
            v-model="bookingData.user.email"
            class="border rounded w-full py-2 px-3 text-gray-700 bg-gray-200"
            id="name"
            type="text"
            disabled
          />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Event title</label>
          <input
            v-if="bookingData.event"
            v-model="bookingData.event.title"
            class="border rounded w-full py-2 px-3 text-gray-700 bg-gray-200"
            id="email"
            type="email"
            disabled
          />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Quantity</label>
          <input
            v-model="bookingData.quantity"
            @input="calculateTotalPrice"
            class="border rounded w-full py-2 px-3 text-gray-700"
            id="email"
            type="text"
          />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Total price</label>
          <input
            v-model="bookingData.total_price"
            class="border rounded w-full py-2 px-3 text-gray-700 bg-gray-200"
            id="email"
            type="text"
            disabled
          />
        </div>
      </div>
    </div>
  </div>
</template>
