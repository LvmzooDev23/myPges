<script setup>
import { RouterView } from 'vue-router'
import Navbar from './components/Navbar.vue'
import { useRoute } from 'vue-router'
import { computed, onMounted } from 'vue'
import { useAuthStore } from './stores/auth'

const route = useRoute()
const auth = useAuthStore()

const isGuest = computed(() => route.meta.guest === true)
const isDashboard = computed(() => route.matched.some((r) => r.meta.dashboard === true))

/** Barre publique uniquement hors pages auth invité et hors console dashboard */
const showPublicChrome = computed(() => !isGuest.value && !isDashboard.value)

onMounted(() => {
  if (auth.token && !auth.user) {
    auth.fetchMe().catch(() => {})
  }
})
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <Navbar v-if="showPublicChrome" />
    <main class="flex-1">
      <RouterView />
    </main>
  </div>
</template>
