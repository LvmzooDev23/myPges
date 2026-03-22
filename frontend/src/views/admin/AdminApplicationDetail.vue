<script setup>
import { onMounted, ref } from 'vue'
import { RouterLink } from 'vue-router'
import client from '../../api/client'
import UiCard from '../../components/ui/UiCard.vue'
import ApplicationStatus from '../../components/ApplicationStatus.vue'

const loading = ref(true)
const error = ref('')
const app = ref(null)
const cvError = ref('')

const props = defineProps({
  id: { type: String, required: true },
})

async function load() {
  loading.value = true
  error.value = ''
  try {
    const { data } = await client.get(`/admin/applications/${props.id}`)
    app.value = data.data ?? data
  } catch (e) {
    error.value = e.response?.data?.message || 'Candidature introuvable.'
  } finally {
    loading.value = false
  }
}

onMounted(load)

async function downloadCv() {
  cvError.value = ''
  try {
    const res = await client.get(`/admin/applications/${props.id}/cv`, { responseType: 'blob' })
    const disposition = res.headers['content-disposition'] || ''
    let filename = 'cv-etudiant'
    const m = /filename\*?=(?:UTF-8'')?["']?([^"';]+)/i.exec(disposition)
    if (m) {
      try {
        filename = decodeURIComponent(m[1])
      } catch {
        filename = m[1]
      }
    } else if (app.value?.student?.cv_path) {
      const ext = app.value.student.cv_path.split('.').pop() || 'pdf'
      filename = `cv-etudiant.${ext}`
    }
    const url = window.URL.createObjectURL(new Blob([res.data]))
    const a = document.createElement('a')
    a.href = url
    a.download = filename
    document.body.appendChild(a)
    a.click()
    a.remove()
    window.URL.revokeObjectURL(url)
  } catch (e) {
    if (e.response?.status === 404) {
      cvError.value = 'Aucun CV disponible pour cet étudiant.'
    } else {
      cvError.value = e.response?.data?.message || 'Téléchargement impossible.'
    }
  }
}

function formatDate(d) {
  if (!d) return '—'
  try {
    return new Date(d).toLocaleString('fr-FR', { dateStyle: 'medium', timeStyle: 'short' })
  } catch {
    return d
  }
}
</script>

<template>
  <div class="mx-auto max-w-4xl space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
      <div>
        <p class="text-sm font-medium text-brand-600">Candidature</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Détail</h1>
      </div>
      <RouterLink
        to="/admin/applications"
        class="text-sm font-semibold text-brand-600 hover:text-brand-700"
      >
        ← Retour à la liste
      </RouterLink>
    </div>

    <div v-if="loading" class="flex justify-center py-16">
      <div class="h-10 w-10 animate-spin rounded-full border-2 border-brand-500 border-t-transparent" />
    </div>

    <p v-else-if="error" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">{{ error }}</p>

    <template v-else-if="app">
      <UiCard title="Statut" padding>
        <div class="flex flex-wrap items-center gap-3">
          <ApplicationStatus :status="app.status" />
          <span class="text-sm text-slate-600">Envoyée le {{ formatDate(app.applied_at) }}</span>
        </div>
      </UiCard>

      <UiCard title="Étudiant" padding>
        <dl class="grid gap-3 sm:grid-cols-2">
          <div>
            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Nom</dt>
            <dd class="mt-1 text-sm font-medium text-slate-900">{{ app.student?.user?.name }}</dd>
          </div>
          <div>
            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</dt>
            <dd class="mt-1 text-sm text-slate-700">{{ app.student?.user?.email }}</dd>
          </div>
          <div>
            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Université</dt>
            <dd class="mt-1 text-sm text-slate-700">{{ app.student?.university || '—' }}</dd>
          </div>
          <div>
            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Formation</dt>
            <dd class="mt-1 text-sm text-slate-700">{{ app.student?.degree || '—' }}</dd>
          </div>
          <div class="sm:col-span-2">
            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Compétences</dt>
            <dd class="mt-1 whitespace-pre-wrap text-sm text-slate-700">{{ app.student?.skills || '—' }}</dd>
          </div>
        </dl>
        <div class="mt-6 border-t border-slate-100 pt-6">
          <p class="text-sm font-semibold text-slate-900">CV</p>
          <p v-if="cvError" class="mt-2 text-sm text-amber-800">{{ cvError }}</p>
          <button
            type="button"
            class="mt-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800"
            @click="downloadCv"
          >
            Télécharger le CV
          </button>
        </div>
      </UiCard>

      <UiCard title="Entreprise" padding>
        <p class="text-lg font-semibold text-slate-900">{{ app.internship?.company?.name }}</p>
        <p v-if="app.internship?.company?.industry" class="mt-1 text-sm text-slate-600">
          Secteur : {{ app.internship.company.industry }}
        </p>
      </UiCard>

      <UiCard title="Stage" padding>
        <h3 class="text-lg font-semibold text-slate-900">{{ app.internship?.title }}</h3>
        <p v-if="app.internship?.location" class="mt-1 text-sm text-slate-600">{{ app.internship.location }}</p>
        <p class="mt-4 whitespace-pre-wrap text-sm leading-relaxed text-slate-700">{{ app.internship?.description }}</p>
      </UiCard>

      <UiCard title="Lettre de motivation" padding>
        <p class="whitespace-pre-wrap text-sm leading-relaxed text-slate-700">{{ app.cover_letter || '—' }}</p>
      </UiCard>
    </template>
  </div>
</template>
