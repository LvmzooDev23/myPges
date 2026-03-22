<script setup>
import { ref } from 'vue'
import client from '../../api/client'
import UiCard from '../../components/ui/UiCard.vue'

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
  <div class="mx-auto max-w-2xl space-y-6">
    <div>
      <p class="text-sm font-medium text-brand-600">Évaluation</p>
      <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Noter une candidature</h1>
    </div>

    <UiCard title="Critères" subtitle="Renseignez l’identifiant de la candidature acceptée">
      <form class="space-y-4" @submit.prevent="submit">
        <div>
          <label class="text-sm font-medium text-slate-700">ID candidature</label>
          <input
            v-model="applicationId"
            type="number"
            required
            class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"
          />
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">Note (1 à 5)</label>
          <input
            v-model.number="score"
            type="number"
            min="1"
            max="5"
            required
            class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm"
          />
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">Commentaires</label>
          <textarea
            v-model="comments"
            rows="5"
            class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"
          />
        </div>
        <button
          type="submit"
          class="w-full rounded-xl bg-brand-600 py-3 text-sm font-semibold text-white shadow-sm hover:bg-brand-500"
        >
          Enregistrer
        </button>
        <p v-if="message" class="text-center text-sm font-medium text-emerald-600">{{ message }}</p>
      </form>
    </UiCard>
  </div>
</template>
