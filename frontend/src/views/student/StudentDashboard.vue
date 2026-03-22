<script setup>
import { onMounted, ref } from 'vue'
import client from '../../api/client'
import DashboardStats from '../../components/DashboardStats.vue'

const profile = ref(null)

onMounted(async () => {
  const { data } = await client.get('/student/profile')
  profile.value = data.data ?? data
})
</script>

<template>
  <div>
    <h1 class="text-xl font-bold text-slate-900">Espace étudiant</h1>
    <p v-if="profile" class="mt-4 text-slate-600">
      Bienvenue, {{ profile.user?.name }}. Complétez votre profil et postulez aux offres.
    </p>
    <DashboardStats
      v-if="profile"
      class="mt-6"
      :stats="[
        { label: 'N° étudiant', value: profile.student_number || '—' },
        { label: 'CV', value: profile.cv_path ? 'Déposé' : 'Manquant' },
      ]"
    />
  </div>
</template>
