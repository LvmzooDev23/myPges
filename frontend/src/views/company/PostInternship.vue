<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import client from '../../api/client'
import UiCard from '../../components/ui/UiCard.vue'

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
  <div class="mx-auto max-w-3xl space-y-6">
    <div>
      <p class="text-sm font-medium text-brand-600">Publication</p>
      <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Nouvelle offre de stage</h1>
    </div>

    <UiCard title="Informations générales" subtitle="Décrivez le poste et le contexte">
      <form class="space-y-4" @submit.prevent="submit">
        <div>
          <label class="text-sm font-medium text-slate-700">Intitulé du poste</label>
          <input v-model="form.title" required class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20" />
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">Description</label>
          <textarea
            v-model="form.description"
            rows="6"
            required
            class="mt-1.5 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"
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
        <button
          type="submit"
          class="w-full rounded-xl bg-brand-600 py-3 text-sm font-semibold text-white shadow-sm hover:bg-brand-500 sm:w-auto sm:px-8"
        >
          Enregistrer l’offre
        </button>
      </form>
    </UiCard>
  </div>
</template>
