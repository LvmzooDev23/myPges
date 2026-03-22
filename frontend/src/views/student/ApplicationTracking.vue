<script setup>
import { onMounted, ref } from 'vue'
import client from '../../api/client'
import ApplicationStatus from '../../components/ApplicationStatus.vue'

const applications = ref([])

onMounted(async () => {
  const { data } = await client.get('/student/applications')
  applications.value = data.data || data
})
</script>

<template>
  <div>
    <h1 class="text-xl font-bold">Mes candidatures</h1>
    <ul class="mt-6 space-y-3">
      <li
        v-for="a in applications"
        :key="a.id"
        class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm"
      >
        <div class="flex flex-wrap items-center justify-between gap-2">
          <span class="font-medium">{{ a.internship?.title }}</span>
          <ApplicationStatus :status="a.status" />
        </div>
        <p class="mt-2 text-sm text-slate-500">{{ a.internship?.company?.name }}</p>
      </li>
    </ul>
  </div>
</template>
