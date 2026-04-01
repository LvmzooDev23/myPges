<script setup>
import { onMounted, ref, computed } from 'vue'
import client from '../api/client'

const notifications = ref([])
const unreadCount = computed(() => notifications.value.filter(n => !n.read_at).length)
const loading = ref(true)
const showDropdown = ref(false)

onMounted(async () => {
  await loadNotifications()
  
  // Check for new notifications every 30 seconds
  setInterval(loadNotifications, 30000)
})

async function loadNotifications() {
  try {
    const { data } = await client.get('/notifications')
    notifications.value = data.data || data || []
  } catch (error) {
    console.error('Failed to load notifications:', error)
  } finally {
    loading.value = false
  }
}

async function markAsRead(notification) {
  if (notification.read_at) return
  
  try {
    await client.patch(`/notifications/${notification.id}/read`)
    notification.read_at = new Date().toISOString()
  } catch (error) {
    console.error('Failed to mark notification as read:', error)
  }
}

function getNotificationIcon(type) {
  switch (type) {
    case 'application_received':
      return '📨'
    case 'application_status':
      return '📋'
    case 'new_internship':
      return '💼'
    default:
      return '🔔'
  }
}

function formatTime(dateString) {
  const date = new Date(dateString)
  const now = new Date()
  const diffInMinutes = Math.floor((now - date) / (1000 * 60))
  
  if (diffInMinutes < 1) return 'À l\'instant'
  if (diffInMinutes < 60) return `Il y a ${diffInMinutes} min`
  
  const diffInHours = Math.floor(diffInMinutes / 60)
  if (diffInHours < 24) return `Il y a ${diffInHours}h`
  
  const diffInDays = Math.floor(diffInHours / 24)
  return `Il y a ${diffInDays}j`
}
</script>

<template>
  <div class="relative">
    <!-- Notification Bell -->
    <button 
      @click="showDropdown = !showDropdown"
      class="relative p-2 text-slate-600 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 rounded-lg"
    >
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
      </svg>
      
      <span 
        v-if="unreadCount > 0"
        class="absolute top-1 right-1 h-2 w-2 bg-red-500 rounded-full"
      ></span>
      
      <span 
        v-if="unreadCount > 0"
        class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full"
      >
        {{ unreadCount > 9 ? '9+' : unreadCount }}
      </span>
    </button>

    <!-- Dropdown -->
    <div 
      v-if="showDropdown"
      class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-slate-200 z-50 max-h-96 overflow-hidden"
    >
      <div class="p-4 border-b border-slate-200">
        <div class="flex items-center justify-between">
          <h3 class="text-sm font-semibold text-slate-900">Notifications</h3>
          <span v-if="unreadCount > 0" class="text-xs text-slate-500">
            {{ unreadCount }} non lue{{ unreadCount > 1 ? 's' : '' }}
          </span>
        </div>
      </div>
      
      <div class="max-h-80 overflow-y-auto">
        <div v-if="loading" class="flex justify-center py-8">
          <div class="h-6 w-6 animate-spin rounded-full border-2 border-brand-500 border-t-transparent" />
        </div>
        
        <div v-else-if="notifications.length === 0" class="p-4 text-center text-sm text-slate-500">
          Aucune notification
        </div>
        
        <div v-else>
          <div 
            v-for="notification in notifications" 
            :key="notification.id"
            :class="[
              'p-4 border-b border-slate-100 hover:bg-slate-50 cursor-pointer transition-colors',
              !notification.read_at ? 'bg-blue-50' : ''
            ]"
            @click="markAsRead(notification)"
          >
            <div class="flex items-start space-x-3">
              <div class="flex-shrink-0 text-xl">
                {{ getNotificationIcon(notification.type) }}
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-slate-900">
                  {{ notification.title }}
                </p>
                <p class="text-sm text-slate-600 mt-1">
                  {{ notification.body }}
                </p>
                <p class="text-xs text-slate-500 mt-2">
                  {{ formatTime(notification.created_at) }}
                </p>
              </div>
              <div v-if="!notification.read_at" class="flex-shrink-0">
                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
