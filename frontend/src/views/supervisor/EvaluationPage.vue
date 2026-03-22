<script setup>
import { ref } from 'vue'
import client from '../../api/client'

const applicationId = ref('')
const score = ref(4)
const comments = ref('')
const message = ref('')

async function submit() {
  message.value = ''
  await client.post('/supervisor/evaluations', {
    application_id: Number(applicationId.value),
    score: score.value,
    comments: comments.value,
  })
  message.value = 'Évaluation enregistrée.'
}
</script>

<template>
  <div class="max-w-lg">
    <h1 class="text-xl font-bold">Évaluation étudiant</h1>
    <form class="mt-6 space-y-4" @submit.prevent="submit">
      <div>
        <label class="text-sm font-medium">ID candidature</label>
        <input v-model="applicationId" type="number" required class="mt-1 w-full rounded border px-3 py-2" />
      </div>
      <div>
        <label class="text-sm font-medium">Note (1–5)</label>
        <input v-model.number="score" type="number" min="1" max="5" required class="mt-1 w-full rounded border px-3 py-2" />
      </div>
      <div>
        <label class="text-sm font-medium">Commentaires</label>
        <textarea v-model="comments" rows="4" class="mt-1 w-full rounded border px-3 py-2" />
      </div>
      <button type="submit" class="rounded-lg bg-brand-600 px-4 py-2 text-white">Envoyer</button>
      <p v-if="message" class="text-sm text-emerald-600">{{ message }}</p>
    </form>
  </div>
</template>
