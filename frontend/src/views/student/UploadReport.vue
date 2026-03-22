<script setup>
import { onMounted, ref } from 'vue'
import client from '../../api/client'
import FileUploader from '../../components/FileUploader.vue'

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
  message.value = 'Rapport envoyé.'
}
</script>

<template>
  <div class="max-w-lg">
    <h1 class="text-xl font-bold">Rapport de stage</h1>
    <div class="mt-6 space-y-4">
      <div>
        <label class="text-sm font-medium">Candidature acceptée</label>
        <select v-model="applicationId" class="mt-1 w-full rounded-lg border px-3 py-2">
          <option value="" disabled>Sélectionner</option>
          <option v-for="a in applications" :key="a.id" :value="a.id">
            {{ a.internship?.title }} (#{{ a.id }})
          </option>
        </select>
      </div>
      <div>
        <label class="text-sm font-medium">Titre (optionnel)</label>
        <input v-model="title" class="mt-1 w-full rounded-lg border px-3 py-2" />
      </div>
      <FileUploader label="Document PDF / Word" @selected="onFile" />
      <button
        type="button"
        class="rounded-lg bg-brand-600 px-4 py-2 text-white disabled:opacity-50"
        :disabled="!applicationId || !file"
        @click="submit"
      >
        Envoyer
      </button>
      <p v-if="message" class="text-sm text-emerald-600">{{ message }}</p>
    </div>
  </div>
</template>
