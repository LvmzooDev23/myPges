<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import client from '../../api/client'
import UiCard from '../../components/ui/UiCard.vue'

const props = defineProps({ internshipId: String })
const cover = ref('')
const error = ref('')
const router = useRouter()

async function submit() {
  error.value = ''
  try {
    await client.post(`/student/internships/${props.internshipId}/apply`, {
      cover_letter: cover.value,
    })
    router.push('/student/applications')
  } catch (e) {
    error.value = e.response?.data?.message || 'Erreur'
  }
}
</script>

<template>
  <div class="mx-auto max-w-2xl space-y-6">
    <div>
      <p class="text-sm font-medium text-brand-600">Candidature</p>
      <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Lettre de motivation</h1>
    </div>

    <UiCard title="Votre message" subtitle="Expliquez votre motivation en quelques lignes">
      <form class="space-y-4" @submit.prevent="submit">
        <textarea
          v-model="cover"
          rows="10"
          placeholder="Bonjour, …"
          class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm leading-relaxed shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20"
        />
        <p v-if="error" class="text-sm font-medium text-rose-600">{{ error }}</p>
        <button
          type="submit"
          class="w-full rounded-xl bg-brand-600 py-3 text-sm font-semibold text-white shadow-sm hover:bg-brand-500 sm:w-auto sm:px-8"
        >
          Envoyer la candidature
        </button>
      </form>
    </UiCard>
  </div>
</template>
