<script setup>
import Sidebar from '../components/Sidebar.vue'
</script>

<template>
  <div class="flex min-h-[calc(100vh-4rem)]">
    <Sidebar />
    <div class="flex-1 overflow-auto p-6 lg:p-8">
      <router-view />
    </div>
  </div>
</template>
