<script setup>
defineProps({
  stats: { type: Array, default: () => [] },
})
</script>

<template>
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div
      v-for="s in stats"
      :key="s.label"
      class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm"
    >
      <p class="text-sm text-slate-500">{{ s.label }}</p>
      <p class="mt-1 text-2xl font-bold text-slate-900">{{ s.value }}</p>
    </div>
  </div>
</template>
