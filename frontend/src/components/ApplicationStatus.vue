<script setup>
const props = defineProps({
  status: { type: String, required: true },
})

const map = {
  pending: { label: 'En attente', class: 'bg-amber-100 text-amber-900' },
  accepted: { label: 'Acceptée', class: 'bg-emerald-100 text-emerald-900' },
  rejected: { label: 'Refusée', class: 'bg-rose-100 text-rose-900' },
  withdrawn: { label: 'Retirée', class: 'bg-slate-100 text-slate-700' },
}

const cfg = () => map[props.status] || { label: props.status, class: 'bg-slate-100 text-slate-700' }
</script>

<template>
  <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium" :class="cfg().class">
    {{ cfg().label }}
  </span>
</template>
