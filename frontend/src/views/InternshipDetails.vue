<script setup>
import { onMounted, ref } from 'vue'
import { RouterLink } from 'vue-router'
import client from '../api/client'
import { useAuthStore } from '../stores/auth'
import UiCard from '../components/ui/UiCard.vue'
import UiBadge from '../components/ui/UiBadge.vue'

const props = defineProps({ id: String })
const internship = ref(null)
const error = ref('')
const auth = useAuthStore()

const typeLabel = { remote: 'Télétravail', onsite: 'Sur site', hybrid: 'Hybride' }

onMounted(async () => {
  try {
    const { data } = await client.get(`/internships/${props.id}`)
    internship.value = data.data ?? data
  } catch {
    error.value = 'Offre introuvable'
  }
})
</script>

<template>
  <div v-if="internship" class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="flex flex-wrap items-start justify-between gap-4">
      <div>
        <div class="flex flex-wrap items-center gap-2">
          <UiBadge v-if="internship.type" variant="brand">{{ typeLabel[internship.type] || internship.type }}</UiBadge>
          <UiBadge v-if="internship.status === 'published'" variant="success">Publié</UiBadge>
        </div>
        <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ internship.title }}</h1>
        <p class="mt-2 text-lg text-slate-600">
          <span class="font-semibold text-slate-800">{{ internship.company?.name }}</span>
          <span v-if="internship.location"> · {{ internship.location }}</span>
        </p>
      </div>
      <RouterLink
        v-if="auth.role === 'student'"
        :to="`/student/apply/${internship.id}`"
        class="inline-flex items-center rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500"
      >
        Postuler
      </RouterLink>
    </div>

    <UiCard class="mt-8" title="Description du poste" padding>
      <div class="prose prose-slate max-w-none">
        <p class="whitespace-pre-wrap text-sm leading-relaxed text-slate-700">{{ internship.description }}</p>
      </div>
      <div v-if="internship.requirements" class="mt-6 border-t border-slate-100 pt-6">
        <h3 class="text-sm font-semibold text-slate-900">Prérequis</h3>
        <p class="mt-2 whitespace-pre-wrap text-sm text-slate-600">{{ internship.requirements }}</p>
      </div>
      <dl class="mt-6 grid gap-4 border-t border-slate-100 pt-6 sm:grid-cols-2">
        <div>
          <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Début</dt>
          <dd class="mt-1 text-sm font-medium text-slate-900">{{ internship.start_date || '—' }}</dd>
        </div>
        <div>
          <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Fin</dt>
          <dd class="mt-1 text-sm font-medium text-slate-900">{{ internship.end_date || '—' }}</dd>
        </div>
        <div>
          <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Places</dt>
          <dd class="mt-1 text-sm font-medium text-slate-900">{{ internship.slots }}</dd>
        </div>
      </dl>
    </UiCard>
  </div>
  <UiCard v-else-if="error" class="mx-auto mt-12 max-w-lg" :title="error" />
  <div v-else class="flex min-h-[40vh] items-center justify-center">
    <div class="h-10 w-10 animate-spin rounded-full border-2 border-brand-500 border-t-transparent" />
  </div>
</template>
