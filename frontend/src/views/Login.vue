<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const email = ref('')
const password = ref('')
const error = ref('')
const auth = useAuthStore()
const router = useRouter()
const route = useRoute()

async function submit() {
  error.value = ''
  try {
    await auth.login({ email: email.value, password: password.value })
    router.push(route.query.redirect || '/internships')
  } catch (e) {
    error.value = e.response?.data?.message || 'Connexion impossible'
  }
}
</script>

<template>
  <div class="mx-auto flex max-w-md flex-col gap-6 px-4 py-16">
    <h1 class="text-2xl font-bold text-slate-900">Connexion</h1>
    <form class="space-y-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
      <div>
        <label class="text-sm font-medium text-slate-700">Email</label>
        <input v-model="email" type="email" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2" />
      </div>
      <div>
        <label class="text-sm font-medium text-slate-700">Mot de passe</label>
        <input v-model="password" type="password" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2" />
      </div>
      <p v-if="error" class="text-sm text-rose-600">{{ error }}</p>
      <button type="submit" class="w-full rounded-lg bg-brand-600 py-2 font-medium text-white hover:bg-brand-500">
        Se connecter
      </button>
    </form>
  </div>
</template>
