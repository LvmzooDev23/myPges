import { defineStore } from 'pinia'
import client from '../api/client'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token'),
  }),
  getters: {
    isAuthenticated: (s) => !!s.token,
    role: (s) => s.user?.role ?? null,
  },
  actions: {
    setToken(token) {
      this.token = token
      if (token) localStorage.setItem('token', token)
      else localStorage.removeItem('token')
    },
    async fetchMe() {
      const { data } = await client.get('/auth/me')
      this.user = data.user
      return data.user
    },
    async login(payload) {
      const { data } = await client.post('/auth/login', payload)
      this.setToken(data.access_token)
      this.user = data.user
      return data
    },
    async register(payload) {
      const { data } = await client.post('/auth/register', payload)
      this.setToken(data.access_token)
      this.user = data.user
      return data
    },
    async logout() {
      try {
        await client.post('/auth/logout')
      } catch {
        /* ignore */
      }
      this.user = null
      this.setToken(null)
    },
  },
})
