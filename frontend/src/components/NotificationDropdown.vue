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
      class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50"
      @click="open = !open; load()"
    >
      <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
        />
      </svg>
      <span class="hidden sm:inline">Alertes</span>
    </button>
    <div
      v-if="open"
      class="absolute right-0 z-50 mt-2 w-80 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-soft"
    >
      <div class="border-b border-slate-100 bg-slate-50/80 px-4 py-2.5">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Notifications</p>
      </div>
      <div class="max-h-80 overflow-y-auto p-2 text-sm">
        <p v-if="loading" class="p-4 text-center text-slate-500">Chargement…</p>
        <template v-else-if="(items || []).length">
          <div
            v-for="n in items"
            :key="n.id"
            class="rounded-xl border border-transparent p-3 transition hover:bg-slate-50"
          >
            <p class="font-medium text-slate-900">{{ n.title }}</p>
            <p class="mt-0.5 text-xs text-slate-500">{{ n.body }}</p>
            <button
              v-if="!n.read_at"
              type="button"
              class="mt-2 text-xs font-semibold text-brand-600 hover:underline"
              @click="markRead(n.id)"
            >
              Marquer comme lu
            </button>
          </div>
        </template>
        <p v-else class="p-6 text-center text-slate-500">Aucune notification</p>
      </div>
    </div>
  </div>
</template>
