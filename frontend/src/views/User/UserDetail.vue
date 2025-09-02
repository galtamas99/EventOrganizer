<script setup>
  import UserForm from '@/components/User/UserForm.vue'
  import LayoutAuthenticated from '@/layouts/LayoutAuthenticated.vue'
  import { ref } from 'vue'

  const props = defineProps({
    id: {
      type: String,
      default: '',
    },
  })

  const userId = ref(props.id)
</script>

<template>
  <LayoutAuthenticated>
    <UserForm :userId="userId" />
  </LayoutAuthenticated>
</template>
