<script setup>
import { onMounted, ref } from 'vue'
import client from '../api/client'

const open = ref(false)
const items = ref([])
const loading = ref(false)

async function load() {
  loading.value = true
  try {
    const { data } = await client.get('/notifications')
    items.value = data.data ?? data
  } finally {
    loading.value = false
  }
}

onMounted(load)

async function markRead(id) {
  await client.patch(`/notifications/${id}/read`)
  await load()
}
</script>

<template>
  <div class="relative">
    <button
      type="button"
      class="rounded-lg border border-slate-200 px-2 py-1 text-xs hover:bg-slate-50"
      @click="open = !open; load()"
    >
      Notifications
    </button>
    <div
      v-if="open"
      class="absolute right-0 z-50 mt-2 w-80 rounded-lg border border-slate-200 bg-white shadow-lg"
    >
      <div class="max-h-72 overflow-auto p-2 text-xs">
        <p v-if="loading" class="p-2 text-slate-500">Chargement…</p>
        <template v-else-if="(items || []).length">
          <div
            v-for="n in items"
            :key="n.id"
            class="border-b border-slate-100 p-2 last:border-0"
          >
            <p class="font-medium text-slate-800">{{ n.title }}</p>
            <p class="text-slate-500">{{ n.body }}</p>
            <button
              v-if="!n.read_at"
              type="button"
              class="mt-1 text-brand-600 hover:underline"
              @click="markRead(n.id)"
            >
              Marquer lu
            </button>
          </div>
        </template>
        <p v-else class="p-2 text-slate-500">Aucune notification</p>
      </div>
    </div>
  </div>
</template>
