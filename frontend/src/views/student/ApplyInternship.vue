<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import client from '../../api/client'

const props = defineProps({ internshipId: String })
const cover = ref('')
const error = ref('')
const router = useRouter()

async function submit() {
  error.value = ''
  try {
    await client.post(`/student/internships/${props.internshipId}/apply`, {
      cover_letter: cover.value,
    })
    router.push('/student/applications')
  } catch (e) {
    error.value = e.response?.data?.message || 'Erreur'
  }
}
</script>

<template>
  <div class="max-w-xl">
    <h1 class="text-xl font-bold">Candidature</h1>
    <form class="mt-6 space-y-4" @submit.prevent="submit">
      <div>
        <label class="text-sm font-medium">Lettre de motivation</label>
        <textarea v-model="cover" rows="6" class="mt-1 w-full rounded-lg border px-3 py-2" />
      </div>
      <p v-if="error" class="text-sm text-rose-600">{{ error }}</p>
      <button type="submit" class="rounded-lg bg-brand-600 px-4 py-2 text-white">Envoyer</button>
    </form>
  </div>
</template>
