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
const student = ref(null)
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
    student.value = s
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

async function viewCv() {
  try {
    const response = await client.get('/student/cv', {
      responseType: 'blob'
    })
    const url = window.URL.createObjectURL(new Blob([response.data]))
    window.open(url, '_blank')
    window.URL.revokeObjectURL(url)
  } catch (e) {
    error.value = e.response?.data?.message || 'Impossible de visualiser le CV.'
  }
}

async function downloadCv() {
  try {
    const response = await client.get('/student/cv/download', {
      responseType: 'blob'
    })
    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', 'CV.' + (student.value.cv_path?.split('.').pop() || 'pdf'))
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    window.URL.revokeObjectURL(url)
  } catch (e) {
    error.value = e.response?.data?.message || 'Impossible de télécharger le CV.'
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
      <UiCard title="Profil" subtitle="Informations personnelles">
        <div class="mb-6">
          <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-slate-700">Complétion du profil</span>
            <span class="text-sm font-semibold text-brand-600">{{ student?.profile_completion || 0 }}%</span>
          </div>
          <div class="w-full bg-slate-200 rounded-full h-2">
            <div 
              class="bg-brand-600 h-2 rounded-full transition-all duration-300"
              :style="{ width: `${student?.profile_completion || 0}%` }"
            ></div>
          </div>
          <div v-if="student?.missing_profile_fields?.length" class="mt-3">
            <p class="text-sm font-medium text-slate-700 mb-2">Champs manquants:</p>
            <div class="flex flex-wrap gap-2">
              <span 
                v-for="field in student.missing_profile_fields" 
                :key="field"
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800"
              >
                {{ field }}
              </span>
            </div>
          </div>
        </div>
      </UiCard>

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
        <div class="space-y-4">
          <FileDropzone
            label="Glissez votre CV ou cliquez pour parcourir"
            hint="Formats acceptés : PDF, DOCX"
            accept=".pdf,.docx"
            @selected="onCvFile"
          />
          
          <div v-if="student?.has_cv" class="flex gap-3">
            <button
              @click="viewCv"
              class="rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-500"
            >
              Voir le CV
            </button>
            <button
              @click="downloadCv"
              class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
            >
              Télécharger le CV
            </button>
            <button
              @click="() => document.querySelector('input[type=file]')?.click()"
              class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-2.5 text-sm font-semibold text-amber-700 shadow-sm hover:bg-amber-100"
            >
              Remplacer le CV
            </button>
          </div>
          
          <p v-if="uploading" class="mt-3 text-sm text-slate-500">Téléversement en cours…</p>
          <p v-if="success" class="mt-3 text-sm text-emerald-500">{{ success }}</p>
        </div>
      </UiCard>
    </template>
  </div>
</template>
