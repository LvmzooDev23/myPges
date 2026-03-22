<script setup>
import { onMounted, ref } from 'vue'
import client from '../../api/client'
import ApplicationStatus from '../../components/ApplicationStatus.vue'

const internships = ref([])
const selectedId = ref(null)
const applications = ref([])

onMounted(async () => {
  const { data } = await client.get('/company/internships')
  internships.value = data.data || data
})

async function loadApps() {
  if (!selectedId.value) return
  const { data } = await client.get(`/company/internships/${selectedId.value}/applications`)
  applications.value = data.data || data
}

async function setStatus(app, status) {
  await client.patch(`/company/applications/${app.id}`, { status })
  await loadApps()
}
</script>

<template>
  <div>
    <h1 class="text-xl font-bold">Candidatures</h1>
    <div class="mt-4 flex gap-4">
      <select v-model="selectedId" class="rounded border px-3 py-2" @change="loadApps">
        <option :value="null">Choisir une offre</option>
        <option v-for="i in internships" :key="i.id" :value="i.id">{{ i.title }}</option>
      </select>
    </div>
    <ul class="mt-6 space-y-3">
      <li
        v-for="a in applications"
        :key="a.id"
        class="flex flex-wrap items-center justify-between gap-2 rounded border bg-white p-4"
      >
        <div>
          <p class="font-medium">{{ a.student?.user?.name }}</p>
          <p class="text-sm text-slate-500">{{ a.student?.user?.email }}</p>
        </div>
        <ApplicationStatus :status="a.status" />
        <div v-if="a.status === 'pending'" class="flex gap-2">
          <button
            type="button"
            class="rounded bg-emerald-600 px-2 py-1 text-xs text-white"
            @click="setStatus(a, 'accepted')"
          >
            Accepter
          </button>
          <button
            type="button"
            class="rounded bg-rose-600 px-2 py-1 text-xs text-white"
            @click="setStatus(a, 'rejected')"
          >
            Refuser
          </button>
        </div>
      </li>
    </ul>
  </div>
</template>
