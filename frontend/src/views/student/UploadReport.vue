<script setup>
import { onMounted, ref } from 'vue'
import client from '../../api/client'
import FileDropzone from '../../components/ui/FileDropzone.vue'
import UiCard from '../../components/ui/UiCard.vue'

const applications = ref([])
const applicationId = ref('')
const title = ref('')
const file = ref(null)
const message = ref('')

onMounted(async () => {
  const { data } = await client.get('/student/applications')
  const list = data.data || data
  applications.value = list.filter((a) => a.status === 'accepted' && !a.report)
})

function onFile(f) {
  file.value = f
}

async function submit() {
  message.value = ''
  const fd = new FormData()
  if (title.value) fd.append('title', title.value)
  fd.append('file', file.value)
  await client.post(`/student/applications/${applicationId.value}/report`, fd, {
    headers: { 'Content-Type': 'multipart/form-data' },
  })
  message.value = 'Rapport envoyé avec succès.'
}
</script>

<template>
  <div class="mx-auto max-w-2xl space-y-6">
    <div>
      <p class="text-sm font-medium text-brand-600">Stage</p>
      <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Déposer un rapport</h1>
      <p class="mt-2 text-sm text-slate-500">Formats acceptés : PDF, Word (max 10 Mo).</p>
    </div>

    <UiCard title="Envoi du document" subtitle="Choisissez la candidature acceptée concernée">
      <div class="space-y-6">
        <div>
          <label class="text-sm font-medium text-slate-700">Candidature</label>
          <select
            v-model="applicationId"
            class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"
          >
            <option value="" disabled>Sélectionner…</option>
            <option v-for="a in applications" :key="a.id" :value="a.id">
              {{ a.internship?.title }} (#{{ a.id }})
            </option>
          </select>
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">Titre du rapport (optionnel)</label>
          <input v-model="title" class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20" />
        </div>
        <FileDropzone label="Glissez votre fichier ici" @selected="onFile" />
        <button
          type="button"
          class="w-full rounded-xl bg-brand-600 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500 disabled:cursor-not-allowed disabled:opacity-50"
          :disabled="!applicationId || !file"
          @click="submit"
        >
          Envoyer le rapport
        </button>
        <p v-if="message" class="text-center text-sm font-medium text-emerald-600">{{ message }}</p>
      </div>
    </UiCard>
  </div>
</template>
