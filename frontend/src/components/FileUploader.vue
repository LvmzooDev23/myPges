<script setup>
import FileDropzone from './ui/FileDropzone.vue'

defineProps({
  label: { type: String, default: 'Fichier' },
  accept: { type: String, default: '.pdf,.doc,.docx' },
})

const emit = defineEmits(['selected'])
</script>

<template>
  <FileDropzone :label="label" :accept="accept" @selected="(f) => emit('selected', f)" />
</template>
