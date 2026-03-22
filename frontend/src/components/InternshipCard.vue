<script setup>
import { RouterLink } from 'vue-router'

defineProps({
  internship: { type: Object, required: true },
})
</script>

<template>
  <article
    class="flex flex-col rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md"
  >
    <h3 class="text-lg font-semibold text-slate-900">{{ internship.title }}</h3>
    <p class="mt-1 text-sm text-slate-500">
      {{ internship.company?.name }} · {{ internship.location || '—' }}
    </p>
    <p class="mt-3 line-clamp-3 text-sm text-slate-600">{{ internship.description }}</p>
    <div class="mt-4 flex flex-wrap gap-2 text-xs">
      <span class="rounded-full bg-slate-100 px-2 py-0.5 capitalize">{{ internship.type }}</span>
      <span
        v-if="internship.start_date"
        class="rounded-full bg-brand-50 px-2 py-0.5 text-brand-800"
      >
        Début {{ internship.start_date }}
      </span>
    </div>
    <RouterLink
      :to="`/internships/${internship.id}`"
      class="mt-4 inline-flex text-sm font-medium text-brand-600 hover:underline"
    >
      Voir l'offre →
    </RouterLink>
  </article>
</template>
