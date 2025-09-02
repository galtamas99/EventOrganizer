<script setup>
  import EventForm from '@/components/Event/EventForm.vue'
  import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
  import { ref } from 'vue'

  const props = defineProps({
    id: {
      type: String,
      default: '',
    },
  })

  const eventId = ref(props.id)
</script>

<template>
  <LayoutAuthenticated>
    <EventForm :eventId="eventId" />
  </LayoutAuthenticated>
</template>
