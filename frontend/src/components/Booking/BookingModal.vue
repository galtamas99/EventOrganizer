<script setup>
  import { ref } from 'vue'

  const props = defineProps({
    eventTitle: {
      type: String,
      required: true,
    },
  })

  const emit = defineEmits(['cancel', 'confirm'])

  const quantity = ref(1)

  const overlayClick = () => {
    emit('cancel')
  }

  const confirmBooking = () => {
    emit('confirm', quantity.value)
  }
</script>

<template>
  <div class="flex z-50 items-center flex-col justify-center overflow-x-hidden fixed inset-0">
    <transition
      enter-active-class="transition duration-150 ease-in"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        class="absolute inset-0 bg-gradient-to-tr opacity-90 dark:from-gray-700 dark:via-gray-900 dark:to-gray-700"
        @click="overlayClick"
      />
    </transition>
    <transition
      enter-active-class="transition duration-100 ease-out"
      enter-from-class="transform scale-95 opacity-0"
      enter-to-class="transform scale-100 opacity-100"
      leave-active-class="animate-fade-out"
    >
      <div class="bg-gray-500 rounded-lg shadow-lg p-6 max-w-lg w-full mx-4 relative">
        <div class="absolute top-2 right-4">
          <button @click="overlayClick" class="text-white hover:text-gray-300 text-2xl font-bold">&times;</button>
        </div>
        <div class="flex justify-between mb-4">
          <h2 class="text-2xl font-bold mb-4 text-white">Book Event ({{ eventTitle }})</h2>
        </div>
        <label class="block text-white text-sm font-bold mb-2" for="quantity">Quantity</label>
        <input type="text" placeholder="Quantity" v-model="quantity" class="border rounded w-full py-2 px-3 text-gray-700 bg-gray-200" />
        <div class="mt-6 flex justify-end gap-3">
          <button
            @click="confirmBooking"
            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition hover:cursor-pointer"
          >
            Confirm
          </button>
          <button @click="overlayClick" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition hover:cursor-pointer">
            Cancel
          </button>
        </div>
      </div>
    </transition>
  </div>
</template>
