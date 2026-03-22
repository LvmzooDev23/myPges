<script setup>
import { ref } from 'vue'
import Sidebar from '../components/Sidebar.vue'
import DashboardTopBar from '../components/layout/DashboardTopBar.vue'

const mobileOpen = ref(false)

function closeMobile() {
  mobileOpen.value = false
}
</script>

<template>
  <div class="min-h-screen bg-slate-50 lg:flex">
    <div
      v-if="mobileOpen"
      class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm transition-opacity lg:hidden"
      aria-hidden="true"
      @click="closeMobile"
    />
    <Sidebar :mobile-open="mobileOpen" @close="closeMobile" />
    <div class="flex min-h-screen min-w-0 flex-1 flex-col">
      <DashboardTopBar @open-sidebar="mobileOpen = true" />
      <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
        <router-view />
      </main>
    </div>
  </div>
</template>
