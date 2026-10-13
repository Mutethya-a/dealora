
<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { authService } from '../services/authService'

const router = useRouter()
const authStore = useAuthStore()

const firstName = computed(
  () => authStore.user?.first_name || 'Learner'
)

async function handleLogout() {
  try {
    if (authStore.token) {
      await authService.logout(authStore.token)
    }
  } catch (error) {
    console.error('Logout request failed:', error)
  } finally {
    authStore.clearSession()
    await router.push('/login')
  }
}
</script>

<template>
  <v-container fluid class="dashboard pa-8">
    <v-row align="center" justify="space-between">
      <v-col cols="12" sm="8">
        <h1>Welcome back, {{ firstName }}! 💜</h1>
        <p>Ready to become a better dealmaker?</p>
      </v-col>

      <v-col cols="12" sm="4" class="text-sm-right">
        <v-btn
          color="primary"
          variant="outlined"
          prepend-icon="mdi-logout"
          @click="handleLogout"
        >
          Log Out
        </v-btn>
      </v-col>
    </v-row>

    <v-card class="mt-8 pa-6" rounded="xl" elevation="0">
      <h2>Your learning journey starts here.</h2>
      <p class="mt-3">
        Soon, you'll be able to explore courses,
        track progress, and practice your skills
        with Dealora's AI coach.
      </p>
    </v-card>
  </v-container>
</template>

<style scoped>
.dashboard {
  min-height: 100vh;
  background: #fcfaf8;
  color: #252139;
}

h1 {
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
  color: #7048d9;
}

p {
  color: #827b98;
}

.v-card {
  background: #ffffff;
  border: 1px solid #eee9f3;
}
</style>
