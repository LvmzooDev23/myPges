<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import client from '../../api/client'
import UiCard from '../../components/ui/UiCard.vue'

const route = useRoute()
const router = useRouter()
const loading = ref(true)
const saving = ref(false)
const error = ref('')

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
  duration: '',
  stipend: null,
  required_skills: '',
  deadline: '',
})

async function load() {
  loading.value = true
  error.value = ''
  try {
    const { data } = await client.get(`/company/internships/${route.params.id}`)
    const i = data.data ?? data
    form.value = {
      title: i.title || '',
      description: i.description || '',
      location: i.location || '',
      type: i.type || 'onsite',
      start_date: i.start_date ? String(i.start_date).slice(0, 10) : '',
      end_date: i.end_date ? String(i.end_date).slice(0, 10) : '',
      slots: i.slots ?? 1,
      status: i.status || 'draft',
      requirements: i.requirements || '',
      duration: i.duration || '',
      stipend: i.stipend != null ? Number(i.stipend) : null,
      required_skills: i.required_skills || '',
      deadline: i.deadline ? String(i.deadline).slice(0, 10) : '',
    }
  } catch (e) {
    error.value = e.response?.data?.message || 'Offre introuvable.'
  } finally {
    loading.value = false
  }
}

onMounted(load)

async function submit() {
  saving.value = true
  error.value = ''
  try {
    const payload = { ...form.value }
    if (payload.stipend === '' || payload.stipend === null) {
      payload.stipend = null
    }
    await client.put(`/company/internships/${route.params.id}`, payload)
    router.push('/company/internships')
  } catch (e) {
    error.value = e.response?.data?.message || 'Erreur lors de l’enregistrement.'
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <div class="mx-auto max-w-3xl space-y-6">
    <div>
      <p class="text-sm font-medium text-brand-600">Modification</p>
      <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Modifier l’offre</h1>
    </div>

    <div v-if="loading" class="flex justify-center py-16">
      <div class="h-10 w-10 animate-spin rounded-full border-2 border-brand-500 border-t-transparent" />
    </div>

    <UiCard v-else title="Informations générales" subtitle="Mettez à jour le contenu de l’offre">
      <form class="space-y-4" @submit.prevent="submit">
        <div>
          <label class="text-sm font-medium text-slate-700">Intitulé du poste</label>
          <input v-model="form.title" required class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm" />
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">Description</label>
          <textarea
            v-model="form.description"
            rows="6"
            required
            class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm"
          />
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label class="text-sm font-medium text-slate-700">Lieu</label>
            <input v-model="form.location" class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm" />
          </div>
          <div>
            <label class="text-sm font-medium text-slate-700">Mode</label>
            <select v-model="form.type" class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm">
              <option value="remote">Télétravail</option>
              <option value="onsite">Sur site</option>
              <option value="hybrid">Hybride</option>
            </select>
          </div>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label class="text-sm font-medium text-slate-700">Durée (ex. 6 mois)</label>
            <input v-model="form.duration" class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm" />
          </div>
          <div>
            <label class="text-sm font-medium text-slate-700">Indemnité (€ / mois)</label>
            <input
              v-model.number="form.stipend"
              type="number"
              min="0"
              step="0.01"
              class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm"
            />
          </div>
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">Compétences requises</label>
          <textarea v-model="form.required_skills" rows="3" class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm" />
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">Date limite de candidature</label>
          <input v-model="form.deadline" type="date" class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm" />
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label class="text-sm font-medium text-slate-700">Date de début</label>
            <input v-model="form.start_date" type="date" class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm" />
          </div>
          <div>
            <label class="text-sm font-medium text-slate-700">Date de fin</label>
            <input v-model="form.end_date" type="date" class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm" />
          </div>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label class="text-sm font-medium text-slate-700">Nombre de places</label>
            <input v-model.number="form.slots" type="number" min="1" class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm" />
          </div>
          <div>
            <label class="text-sm font-medium text-slate-700">Visibilité</label>
            <select v-model="form.status" class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm">
              <option value="draft">Brouillon</option>
              <option value="published">Publié</option>
              <option value="closed">Fermé</option>
            </select>
          </div>
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">Prérequis</label>
          <textarea v-model="form.requirements" rows="3" class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm" />
        </div>
        <p v-if="error" class="text-sm font-medium text-rose-600">{{ error }}</p>
        <div class="flex flex-wrap gap-3">
          <button
            type="submit"
            :disabled="saving"
            class="rounded-xl bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-500 disabled:opacity-50"
          >
            {{ saving ? 'Enregistrement…' : 'Enregistrer' }}
          </button>
          <button
            type="button"
            class="rounded-xl border border-slate-200 px-6 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            @click="router.push('/company/internships')"
          >
            Annuler
          </button>
        </div>
      </form>
    </UiCard>
  </div>
</template>
