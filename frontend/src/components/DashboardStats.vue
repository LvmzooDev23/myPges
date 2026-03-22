<script setup>
defineProps({
  stats: { type: Array, default: () => [] },
})
</script>

<template>
  <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div
      v-for="s in stats"
      :key="s.label"
      class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-card transition hover:border-brand-200 hover:shadow-soft"
    >
      <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-brand-500/5 transition group-hover:bg-brand-500/10" />
      <p class="text-sm font-medium text-slate-500">{{ s.label }}</p>
      <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ s.value }}</p>
    </div>
  </div>
</template>
