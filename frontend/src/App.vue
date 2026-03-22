<script setup>
import { RouterView } from 'vue-router'
import Navbar from './components/Navbar.vue'
import { useRoute } from 'vue-router'
import { computed, onMounted } from 'vue'
import { useAuthStore } from './stores/auth'

const route = useRoute()
const hideNav = computed(() => route.meta.guest === true)
const auth = useAuthStore()

onMounted(() => {
  if (auth.token && !auth.user) {
    auth.fetchMe().catch(() => {})
  }
})
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <Navbar v-if="!hideNav" />
    <main class="flex-1">
      <RouterView />
    </main>
  </div>
</template>
