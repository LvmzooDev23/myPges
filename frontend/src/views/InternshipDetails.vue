<script setup>
import { onMounted, ref } from 'vue'
import { RouterLink } from 'vue-router'
import client from '../api/client'
import { useAuthStore } from '../stores/auth'

const props = defineProps({ id: String })
const internship = ref(null)
const error = ref('')
const auth = useAuthStore()

onMounted(async () => {
  try {
    const { data } = await client.get(`/internships/${props.id}`)
    internship.value = data.data ?? data
  } catch {
    error.value = 'Offre introuvable'
  }
})
</script>

<template>
  <div v-if="internship" class="mx-auto max-w-3xl px-4 py-8">
    <h1 class="text-2xl font-bold">{{ internship.title }}</h1>
    <p class="mt-2 text-slate-600">{{ internship.company?.name }} · {{ internship.location }}</p>
    <p class="mt-6 whitespace-pre-wrap text-slate-800">{{ internship.description }}</p>
    <RouterLink
      v-if="auth.role === 'student'"
      :to="`/student/apply/${internship.id}`"
      class="mt-8 inline-flex rounded-lg bg-brand-600 px-4 py-2 text-white"
    >
      Postuler
    </RouterLink>
  </div>
  <p v-else-if="error" class="p-8 text-center text-rose-600">{{ error }}</p>
  <p v-else class="p-8 text-center">Chargement…</p>
</template>
