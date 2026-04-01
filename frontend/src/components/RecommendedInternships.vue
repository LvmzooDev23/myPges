<script setup>
import { onMounted, ref } from 'vue'
import { RouterLink } from 'vue-router'
import client from '../api/client'
import UiCard from './ui/UiCard.vue'

const recommendedInternships = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const { data } = await client.get('/student/recommended-internships')
    recommendedInternships.value = data
  } catch (error) {
    console.error('Failed to load recommended internships:', error)
  } finally {
    loading.value = false
  }
})

function getRelevanceColor(score) {
  if (score >= 70) return 'bg-green-100 text-green-800'
  if (score >= 40) return 'bg-yellow-100 text-yellow-800'
  return 'bg-slate-100 text-slate-800'
}
</script>

<template>
  <UiCard title="Stages recommandés" subtitle="Basés sur votre profil et compétences">
    <div v-if="loading" class="flex justify-center py-8">
      <div class="h-8 w-8 animate-spin rounded-full border-2 border-brand-500 border-t-transparent" />
    </div>
    
    <div v-else-if="recommendedInternships.length === 0" class="text-center py-8">
      <div class="text-slate-500 text-sm">
        <p class="mb-2">Aucune recommandation disponible pour le moment.</p>
        <p>Complétez votre profil avec vos compétences et formation pour recevoir des recommandations personnalisées.</p>
      </div>
    </div>
    
    <div v-else class="space-y-4">
      <div 
        v-for="item in recommendedInternships" 
        :key="item.internship.id"
        class="border border-slate-200 rounded-lg p-4 hover:border-brand-300 transition-colors"
      >
        <div class="flex justify-between items-start mb-3">
          <div class="flex-1">
            <h3 class="font-semibold text-slate-900 mb-1">{{ item.internship.title }}</h3>
            <p class="text-sm text-slate-600 mb-2">{{ item.internship.company?.name }}</p>
            <div class="flex flex-wrap gap-2 text-xs text-slate-500">
              <span v-if="item.internship.location">📍 {{ item.internship.location }}</span>
              <span v-if="item.internship.duration">⏱️ {{ item.internship.duration }}</span>
              <span v-if="item.internship.type">💼 {{ item.internship.type }}</span>
              <span v-if="item.internship.stipend">💰 {{ item.internship.stipend }}€</span>
            </div>
          </div>
          <div class="ml-4">
            <span 
              :class="[
                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                getRelevanceColor(item.relevance_score)
              ]"
            >
              {{ Math.round(item.relevance_score) }}% match
            </span>
          </div>
        </div>
        
        <div class="flex justify-between items-center">
          <p class="text-xs text-slate-500">
            Date limite: {{ new Date(item.internship.deadline).toLocaleDateString('fr-FR') }}
          </p>
          <RouterLink 
            :to="`/internships/${item.internship.id}`"
            class="text-sm font-medium text-brand-600 hover:text-brand-700"
          >
            Voir détails →
          </RouterLink>
        </div>
      </div>
    </div>
  </UiCard>
</template>
