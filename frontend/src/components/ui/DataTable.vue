<script setup>
defineProps({
  striped: { type: Boolean, default: true },
})
</script>

<template>
  <div class="overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-card">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[32rem] text-left text-sm">
        <thead>
          <tr
            class="border-b border-slate-200 bg-slate-50/80 text-xs font-semibold uppercase tracking-wide text-slate-500"
          >
            <slot name="head" />
          </tr>
        </thead>
        <tbody>
          <slot name="body" />
        </tbody>
      </table>
    </div>
    <div v-if="$slots.footer" class="border-t border-slate-100 bg-slate-50/50 px-4 py-3">
      <slot name="footer" />
    </div>
  </div>
</template>
