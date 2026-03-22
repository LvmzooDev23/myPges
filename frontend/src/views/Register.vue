<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'student',
  company_name: '',
})
const error = ref('')
const auth = useAuthStore()
const router = useRouter()

async function submit() {
  error.value = ''
  try {
    await auth.register({ ...form.value })
    router.push('/internships')
  } catch (e) {
    const msg = e.response?.data?.message
    const errs = e.response?.data?.errors
    error.value = msg || (errs ? JSON.stringify(errs) : 'Inscription impossible')
  }
}
</script>

<template>
  <div class="mx-auto flex max-w-lg flex-col gap-6 px-4 py-12">
    <h1 class="text-2xl font-bold text-slate-900">Créer un compte</h1>
    <form class="space-y-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" @submit.prevent="submit">
      <div>
        <label class="text-sm font-medium">Nom</label>
        <input v-model="form.name" required class="mt-1 w-full rounded-lg border px-3 py-2" />
      </div>
      <div>
        <label class="text-sm font-medium">Email</label>
        <input v-model="form.email" type="email" required class="mt-1 w-full rounded-lg border px-3 py-2" />
      </div>
      <div>
        <label class="text-sm font-medium">Rôle</label>
        <select v-model="form.role" class="mt-1 w-full rounded-lg border px-3 py-2">
          <option value="student">Étudiant</option>
          <option value="company">Entreprise</option>
          <option value="supervisor">Superviseur</option>
        </select>
      </div>
      <div v-if="form.role === 'company'">
        <label class="text-sm font-medium">Nom de l'entreprise</label>
        <input v-model="form.company_name" class="mt-1 w-full rounded-lg border px-3 py-2" />
      </div>
      <div>
        <label class="text-sm font-medium">Mot de passe</label>
        <input v-model="form.password" type="password" required class="mt-1 w-full rounded-lg border px-3 py-2" />
      </div>
      <div>
        <label class="text-sm font-medium">Confirmation</label>
        <input
          v-model="form.password_confirmation"
          type="password"
          required
          class="mt-1 w-full rounded-lg border px-3 py-2"
        />
      </div>
      <p v-if="error" class="text-sm text-rose-600">{{ error }}</p>
      <button type="submit" class="w-full rounded-lg bg-brand-600 py-2 font-medium text-white">S'inscrire</button>
    </form>
  </div>
</template>
