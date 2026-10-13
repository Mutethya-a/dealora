
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(null)

  const isAuthenticated = computed(
    () => Boolean(user.value && token.value),
  )

  function setSession(authData) {
    user.value = authData.user
    token.value = authData.token
  }

  function clearSession() {
    user.value = null
    token.value = null
  }

  return {
    user,
    token,
    isAuthenticated,
    setSession,
    clearSession,
  }
})
