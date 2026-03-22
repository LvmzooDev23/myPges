<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import client from '../../api/client'

const form = ref({
  title: '',
  description: '',
  location: '',
  type: 'onsite',
  start_date: '',
  end_date: '',
  slots: 1,
  status: 'published',
  requirements: '',
})
const error = ref('')
const router = useRouter()

async function submit() {
  error.value = ''
  try {
    await client.post('/company/internships', form.value)
    router.push('/company/dashboard')
  } catch (e) {
    error.value = e.response?.data?.message || 'Erreur'
  }
}
</script>

<template>
  <div class="max-w-2xl">
    <h1 class="text-xl font-bold">Publier une offre</h1>
    <form class="mt-6 space-y-3" @submit.prevent="submit">
      <input v-model="form.title" placeholder="Titre" required class="w-full rounded border px-3 py-2" />
      <textarea v-model="form.description" rows="5" placeholder="Description" required class="w-full rounded border px-3 py-2" />
      <input v-model="form.location" placeholder="Lieu" class="w-full rounded border px-3 py-2" />
      <select v-model="form.type" class="w-full rounded border px-3 py-2">
        <option value="remote">Télétravail</option>
        <option value="onsite">Sur site</option>
        <option value="hybrid">Hybride</option>
      </select>
      <div class="flex gap-2">
        <input v-model="form.start_date" type="date" class="rounded border px-3 py-2" />
        <input v-model="form.end_date" type="date" class="rounded border px-3 py-2" />
      </div>
      <input v-model.number="form.slots" type="number" min="1" class="w-full rounded border px-3 py-2" />
      <select v-model="form.status" class="w-full rounded border px-3 py-2">
        <option value="draft">Brouillon</option>
        <option value="published">Publié</option>
        <option value="closed">Fermé</option>
      </select>
      <textarea v-model="form.requirements" placeholder="Prérequis" class="w-full rounded border px-3 py-2" />
      <p v-if="error" class="text-sm text-rose-600">{{ error }}</p>
      <button type="submit" class="rounded-lg bg-brand-600 px-4 py-2 text-white">Enregistrer</button>
    </form>
  </div>
</template>
