<script setup>
defineProps({
  title: { type: String, default: '' },
  subtitle: { type: String, default: '' },
  padding: { type: Boolean, default: true },
})
</script>

<template>
  <section
    class="rounded-2xl border border-slate-200/80 bg-white shadow-card"
    :class="padding ? 'p-5 sm:p-6' : ''"
  >
    <header v-if="title || subtitle || $slots.header" class="mb-4 flex flex-wrap items-start justify-between gap-3">
      <div>
        <slot name="header">
          <h2 v-if="title" class="text-lg font-semibold tracking-tight text-slate-900">{{ title }}</h2>
          <p v-if="subtitle" class="mt-0.5 text-sm text-slate-500">{{ subtitle }}</p>
        </slot>
      </div>
      <div v-if="$slots.actions" class="flex shrink-0 items-center gap-2">
        <slot name="actions" />
      </div>
    </header>
    <slot />
  </section>
</template>
