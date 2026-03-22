<script setup>
const props = defineProps({
  variant: {
    type: String,
    default: 'neutral',
    validator: (v) =>
      ['neutral', 'success', 'warning', 'danger', 'info', 'brand'].includes(v),
  },
})

const classes = {
  neutral: 'bg-slate-100 text-slate-700 ring-slate-500/10',
  success: 'bg-emerald-50 text-emerald-800 ring-emerald-600/15',
  warning: 'bg-amber-50 text-amber-900 ring-amber-600/15',
  danger: 'bg-rose-50 text-rose-800 ring-rose-600/15',
  info: 'bg-sky-50 text-sky-800 ring-sky-600/15',
  brand: 'bg-brand-50 text-brand-800 ring-brand-600/15',
}
</script>

<template>
  <span
    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset"
    :class="classes[props.variant] || classes.neutral"
  >
    <slot />
  </span>
</template>
