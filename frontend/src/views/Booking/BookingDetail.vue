<script setup>
  import BookingForm from '@/components/Booking/BookingForm.vue'
  import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
  import { ref } from 'vue'

  const props = defineProps({
    id: {
      type: String,
      default: '',
    },
  })

  const bookingId = ref(props.id)
</script>

<template>
  <LayoutAuthenticated>
    <BookingForm :bookingId="bookingId" />
  </LayoutAuthenticated>
</template>
