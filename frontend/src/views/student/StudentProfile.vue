<script setup>
import { onMounted, ref } from 'vue'
import client from '../../api/client'
import UiCard from '../../components/ui/UiCard.vue'
import FileDropzone from '../../components/ui/FileDropzone.vue'

const loading = ref(true)
const saving = ref(false)
const uploading = ref(false)
const success = ref('')
const error = ref('')
const form = ref({
  first_name: '',
  last_name: '',
  phone: '',
  university: '',
  degree: '',
  skills: '',
  linkedin_url: '',
  student_number: '',
  bio: '',
})

async function load() {
  loading.value = true
  try {
    const { data } = await client.get('/student/profile')
    const s = data.data ?? data
    form.value = {
      first_name: s.first_name || '',
      last_name: s.last_name || '',
      phone: s.phone || '',
      university: s.university || '',
      degree: s.degree || '',
      skills: s.skills || '',
      linkedin_url: s.linkedin_url || '',
      student_number: s.student_number || '',
      bio: s.bio || '',
    }
  } finally {
    loading.value = false
  }
}

onMounted(load)

async function saveProfile() {
  saving.value = true
  success.value = ''
  error.value = ''
  try {
    await client.put('/student/profile', form.value)
    success.value = 'Profil mis à jour.'
    await load()
  } catch (e) {
    error.value = e.response?.data?.message || 'Erreur de sauvegarde.'
    if (e.response?.data?.errors) {
      error.value += ' ' + JSON.stringify(e.response.data.errors)
    }
  } finally {
    saving.value = false
  }
}

async function onCvFile(f) {
  if (!f) return
  uploading.value = true
  success.value = ''
  error.value = ''
  const fd = new FormData()
  fd.append('file', f)
  try {
    await client.post('/student/upload-cv', fd, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    success.value = 'CV téléversé avec succès.'
    await load()
  } catch (e) {
    error.value = e.response?.data?.message || 'Échec du téléversement du CV.'
  } finally {
    uploading.value = false
  }
}
</script>

<template>
  <div class="mx-auto max-w-3xl space-y-6">
    <div>
      <p class="text-sm font-medium text-brand-600">Profil</p>
      <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Mon profil étudiant</h1>
    </div>

    <div
      v-if="success"
      class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800"
    >
      {{ success }}
    </div>
    <div
      v-if="error"
      class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800"
    >
      {{ error }}
    </div>

    <div v-if="loading" class="flex justify-center py-16">
      <div class="h-10 w-10 animate-spin rounded-full border-2 border-brand-500 border-t-transparent" />
    </div>

    <template v-else>
      <UiCard title="Identité" subtitle="Coordonnées utilisées pour vos candidatures">
        <form class="grid gap-4 sm:grid-cols-2" @submit.prevent="saveProfile">
          <div>
            <label class="text-sm font-medium text-slate-700">Prénom</label>
            <input v-model="form.first_name" class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm" />
          </div>
          <div>
            <label class="text-sm font-medium text-slate-700">Nom</label>
            <input v-model="form.last_name" class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm" />
          </div>
          <div>
            <label class="text-sm font-medium text-slate-700">Téléphone</label>
            <input v-model="form.phone" class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm" />
          </div>
          <div>
            <label class="text-sm font-medium text-slate-700">N° étudiant</label>
            <input v-model="form.student_number" class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm" />
          </div>
          <div class="sm:col-span-2">
            <label class="text-sm font-medium text-slate-700">Université</label>
            <input v-model="form.university" class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm" />
          </div>
          <div class="sm:col-span-2">
            <label class="text-sm font-medium text-slate-700">Formation / diplôme visé</label>
            <input v-model="form.degree" class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm" />
          </div>
          <div class="sm:col-span-2">
            <label class="text-sm font-medium text-slate-700">Compétences</label>
            <textarea v-model="form.skills" rows="4" class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm" placeholder="Ex. PHP, Vue.js, travail en équipe…" />
          </div>
          <div class="sm:col-span-2">
            <label class="text-sm font-medium text-slate-700">Profil LinkedIn</label>
            <input
              v-model="form.linkedin_url"
              type="url"
              placeholder="https://www.linkedin.com/in/…"
              class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm"
            />
          </div>
          <div class="sm:col-span-2">
            <label class="text-sm font-medium text-slate-700">Bio (optionnel)</label>
            <textarea v-model="form.bio" rows="3" class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm" />
          </div>
          <div class="sm:col-span-2">
            <button
              type="submit"
              :disabled="saving"
              class="rounded-xl bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-500 disabled:opacity-50"
            >
              {{ saving ? 'Enregistrement…' : 'Mettre à jour le profil' }}
            </button>
          </div>
        </form>
      </UiCard>

      <UiCard title="Curriculum vitae" subtitle="PDF ou DOCX — max 10 Mo">
        <FileDropzone
          label="Glissez votre CV ou cliquez pour parcourir"
          hint="Formats acceptés : PDF, DOCX"
          accept=".pdf,.docx"
          @selected="onCvFile"
        />
        <p v-if="uploading" class="mt-3 text-sm text-slate-500">Téléversement en cours…</p>
      </UiCard>
    </template>
  </div>
</template>
