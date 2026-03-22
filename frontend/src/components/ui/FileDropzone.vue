<script setup>
import { ref } from 'vue'

const props = defineProps({
  label: { type: String, default: 'Glissez-déposez un fichier' },
  hint: { type: String, default: 'PDF, DOC ou DOCX — max 10 Mo' },
  accept: { type: String, default: '.pdf,.doc,.docx' },
})

const emit = defineEmits(['selected'])
const fileName = ref('')
const isDragging = ref(false)

function onFile(e) {
  const f = e.target.files?.[0]
  if (f) {
    fileName.value = f.name
    emit('selected', f)
  }
}

function onDrop(e) {
  e.preventDefault()
  isDragging.value = false
  const f = e.dataTransfer?.files?.[0]
  if (f) {
    fileName.value = f.name
    emit('selected', f)
  }
}
</script>

<template>
  <div>
    <label v-if="label" class="mb-2 block text-sm font-medium text-slate-700">{{ label }}</label>
    <div
      class="relative flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed px-4 py-10 transition"
      :class="
        isDragging
          ? 'border-brand-500 bg-brand-50/50'
          : 'border-slate-200 bg-slate-50/50 hover:border-brand-300 hover:bg-brand-50/30'
      "
      @dragover.prevent="isDragging = true"
      @dragleave.prevent="isDragging = false"
      @drop.prevent="onDrop"
    >
      <input type="file" :accept="accept" class="absolute inset-0 cursor-pointer opacity-0" @change="onFile" />
      <svg
        class="mb-3 h-10 w-10 text-slate-400"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
        aria-hidden="true"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="1.5"
          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
        />
      </svg>
      <p class="text-sm font-medium text-slate-700">{{ label }}</p>
      <p class="mt-1 text-xs text-slate-500">{{ hint }}</p>
      <p v-if="fileName" class="mt-3 rounded-lg bg-white px-3 py-1.5 text-xs font-medium text-brand-700 shadow-sm">
        {{ fileName }}
      </p>
    </div>
  </div>
</template>
