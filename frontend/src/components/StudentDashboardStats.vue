<script setup>
import { onMounted, ref } from 'vue'
import client from '../api/client'
import UiCard from './ui/UiCard.vue'

const stats = ref({
  number_of_applications: 0,
  accepted_applications: 0,
  pending_applications: 0,
  rejected_applications: 0
})
const loading = ref(true)

onMounted(async () => {
  try {
    const { data } = await client.get('/student/dashboard-stats')
    stats.value = data
  } catch (error) {
    console.error('Failed to load dashboard stats:', error)
  } finally {
    loading.value = false
  }
})

const statCards = [
  {
    key: 'number_of_applications',
    label: 'Total candidatures',
    color: 'bg-blue-500',
    icon: '📊'
  },
  {
    key: 'accepted_applications',
    label: 'Acceptées',
    color: 'bg-green-500',
    icon: '✅'
  },
  {
    key: 'pending_applications',
    label: 'En attente',
    color: 'bg-yellow-500',
    icon: '⏳'
  },
  {
    key: 'rejected_applications',
    label: 'Refusées',
    color: 'bg-red-500',
    icon: '❌'
  }
]
</script>

<template>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div v-if="loading" class="col-span-full flex justify-center py-8">
      <div class="h-8 w-8 animate-spin rounded-full border-2 border-brand-500 border-t-transparent" />
    </div>
    
    <UiCard 
      v-for="stat in statCards" 
      :key="stat.key"
      class="relative overflow-hidden"
    >
      <div class="p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div 
              :class="[
                'w-12 h-12 rounded-lg flex items-center justify-center text-white text-xl',
                stat.color
              ]"
            >
              {{ stat.icon }}
            </div>
          </div>
          <div class="ml-5 w-0 flex-1">
            <dl>
              <dt class="text-sm font-medium text-slate-600 truncate">
                {{ stat.label }}
              </dt>
              <dd class="text-lg font-semibold text-slate-900">
                {{ stats[stat.key] }}
              </dd>
            </dl>
          </div>
        </div>
      </div>
      <div 
        :class="[
          'absolute bottom-0 right-0 w-20 h-20 opacity-10 rounded-full -mr-10 -mb-10',
          stat.color
        ]"
      ></div>
    </UiCard>
  </div>
</template>
