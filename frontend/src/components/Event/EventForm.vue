<script setup>
  import router from '@/router'
  import { eventService } from '@/services/event.service'
  import { ref, onBeforeMount } from 'vue'

  const props = defineProps({
    eventId: {
      type: String,
      default: '',
    },
  })

  const user = JSON.parse(localStorage.getItem('user'))
  const eventData = ref({ organizer_id: user.id })
  const statusOptions = ref(['draft', 'published', 'cancelled'])

  const getEventData = () => {
    if (props.eventId) {
      eventService.getEventById(props.eventId).then((response) => {
        if (response.status === 200) {
          eventData.value = response.data.data
          eventData.value.organizer_id = user.id
        }
      })
    }
  }

  const saveEvent = () => {
    if (props.eventId) {
      eventService.updateEvent(props.eventId, eventData.value).then((response) => {
        if (response.status === 200) {
          alert('Event updated successfully')
        }
      })
    } else {
      eventService.createEvent(eventData.value).then((response) => {
        if (response.status === 201) {
          router.push(`/events/${response.data.data.id}/edit`)
          alert('Event created successfully')
        }
      })
    }
  }

  const deleteEvent = () => {
    if (props.eventId) {
      if (confirm('Are you sure you want to delete this event?')) {
        eventService.deleteEvent(props.eventId).then((response) => {
          if (response.status === 204) {
            router.push('/events')
            alert('Event deleted successfully')
          }
        })
      }
    }
  }

  onBeforeMount(() => {
    getEventData()
  })
</script>

<template>
  <div class="container p-4 w-full">
    <div class="flex items-center justify-between gap-6 mb-6">
      <div class="flex items-center gap-3">
        <button
          @click="$router.push('/events')"
          class="mb-4 px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400 transition hover:cursor-pointer"
        >
          Back
        </button>
        <h1 class="text-2xl font-bold mb-4">{{ eventId ? 'Edit Event' : 'Create Event' }}</h1>
      </div>
      <div class="flex items-center gap-3">
        <button
          @click="saveEvent"
          class="mb-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition hover:cursor-pointer"
        >
          Save
        </button>
        <button @click="deleteEvent" class="mb-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition hover:cursor-pointer">
          Delete
        </button>
      </div>
    </div>
    <div class="w-full flex justify-center">
      <div class="bg-white p-6 rounded-lg shadow-md w-1/2 grid grid-cols-2 gap-3">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Title</label>
          <input
            v-model="eventData.title"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            type="text"
            placeholder="Event Title"
          />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
          <textarea
            v-model="eventData.description"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            placeholder="Event Description"
          ></textarea>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Starts at</label>
          <input
            v-model="eventData.starts_at"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            placeholder="Event starts at"
            type="datetime-local"
          />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Location</label>
          <input
            v-model="eventData.location"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            placeholder="Event location"
            type="text"
          />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Capacity</label>
          <input
            v-model="eventData.capacity"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            placeholder="Event capacity"
            type="number"
          />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Ticket price</label>
          <input
            v-model="eventData.ticket_price"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            placeholder="Event ticket price"
            type="number"
          />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Category</label>
          <input
            v-model="eventData.category"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            placeholder="Event category"
          />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
          <select
            v-model="eventData.status"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            placeholder="Event status"
          >
            <option v-for="option in statusOptions" :key="option" :value="option">{{ option }}</option>
          </select>
        </div>
      </div>
    </div>
  </div>
</template>
