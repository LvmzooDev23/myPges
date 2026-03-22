<script setup>
import UiBadge from './ui/UiBadge.vue'

const props = defineProps({
  status: { type: String, required: true },
})

const map = {
  pending: { label: 'En attente', variant: 'warning' },
  accepted: { label: 'Acceptée', variant: 'success' },
  rejected: { label: 'Refusée', variant: 'danger' },
  withdrawn: { label: 'Retirée', variant: 'neutral' },
}

function cfg() {
  return map[props.status] || { label: props.status, variant: 'neutral' }
}
</script>

<template>
  <UiBadge :variant="cfg().variant">{{ cfg().label }}</UiBadge>
</template>
