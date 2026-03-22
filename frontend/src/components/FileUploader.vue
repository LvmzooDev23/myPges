<script setup>
import { ref } from 'vue'

const props = defineProps({
  label: { type: String, default: 'Fichier' },
  accept: { type: String, default: '.pdf,.doc,.docx' },
})

const emit = defineEmits(['selected'])
const fileName = ref('')

function onFile(e) {
  const f = e.target.files?.[0]
  fileName.value = f?.name || ''
  emit('selected', f)
}
</script>

<template>
  <div>
    <label class="block text-sm font-medium text-slate-700">{{ label }}</label>
    <div
      class="mt-1 flex items-center justify-center rounded-lg border-2 border-dashed border-slate-300 px-4 py-6"
    >
      <input type="file" :accept="accept" class="sr-only" id="fu" @change="onFile" />
      <label for="fu" class="cursor-pointer text-sm text-brand-600 hover:underline">
        Choisir un fichier
      </label>
    </div>
    <p v-if="fileName" class="mt-2 text-xs text-slate-500">{{ fileName }}</p>
  </div>
</template>
