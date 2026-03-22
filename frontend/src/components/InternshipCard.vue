<script setup>
import { RouterLink } from 'vue-router'
import UiBadge from './ui/UiBadge.vue'

defineProps({
  internship: { type: Object, required: true },
})

const typeLabel = { remote: 'Télétravail', onsite: 'Sur site', hybrid: 'Hybride' }
</script>

<template>
  <article
    class="group flex h-full flex-col rounded-2xl border border-slate-200/80 bg-white p-5 shadow-card transition hover:border-brand-200 hover:shadow-soft"
  >
    <div class="flex items-start justify-between gap-2">
      <h3 class="text-lg font-semibold tracking-tight text-slate-900 group-hover:text-brand-700">
        {{ internship.title }}
      </h3>
      <UiBadge v-if="internship.type" variant="brand">{{ typeLabel[internship.type] || internship.type }}</UiBadge>
    </div>
    <p class="mt-2 text-sm text-slate-500">
      <span class="font-medium text-slate-700">{{ internship.company?.name }}</span>
      <span v-if="internship.location"> · {{ internship.location }}</span>
    </p>
    <p class="mt-3 line-clamp-3 flex-1 text-sm leading-relaxed text-slate-600">{{ internship.description }}</p>
    <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-4">
      <span
        v-if="internship.start_date"
        class="text-xs font-medium text-slate-500"
      >
        Début {{ internship.start_date }}
      </span>
    </div>
    <RouterLink
      :to="`/internships/${internship.id}`"
      class="mt-4 inline-flex items-center text-sm font-semibold text-brand-600 transition hover:text-brand-700"
    >
      Voir le détail
      <svg class="ml-1 h-4 w-4 transition group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
      </svg>
    </RouterLink>
  </article>
</template>
