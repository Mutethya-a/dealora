<script setup>
import { ref, computed } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { authService } from '../services/authService'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const form = ref(null)
const email = ref('')
const password = ref('')
const showPassword = ref(false)
const rememberMe = ref(false)
const loading = ref(false)
const message = ref('')

const emailRules = [
  (v) => !!v?.trim() || 'Email address is required',
  (v) =>
    /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) ||
    'Please enter a valid email address',
]

const passwordRules = [
  (v) => !!v || 'Password is required',
]

const canSubmit = computed(
  () =>
    email.value.trim().length > 0 &&
    password.value.length > 0 &&
    !loading.value,
)

async function handleLogin() {
  message.value = ''

  const { valid } = await form.value.validate()
  if (!valid) return

  loading.value = true

  try {
    const response = await authService.login({
      email: email.value.trim(),
      password: password.value,
    })

    authStore.setSession(response)

    
    await router.push('/dashboard')

  } catch (error) {
    if (error.response?.status === 422 ||
        error.response?.status === 401) {
      message.value = 'Invalid email or password.'
    } else if (error.response) {
      message.value = 'Login failed. Please try again.'
    } else {
      message.value =
        'Cannot connect to Dealora. Check that Laravel is running.'
    }
  } finally {
    loading.value = false
  }
}

</script>

<template>
  <v-container fluid class="auth-page pa-0">
    <!-- Decorative background -->
    <div class="glow glow-one"></div>
    <div class="glow glow-two"></div>

    <!-- Header -->
    <header class="auth-header">
      <RouterLink to="/" class="logo">
        dealora<span>.</span>
      </RouterLink>

      <RouterLink to="/" class="home-link">
        <v-icon icon="mdi-arrow-left" size="18" />
        <span>Back to website</span>
      </RouterLink>
    </header>

    <!-- Login Content -->
    <main class="auth-main">
      <div class="auth-container">

        <div class="welcome-heading">
          <div class="eyebrow">
            <span class="eyebrow-line"></span>
            YOUR JOURNEY CONTINUES
          </div>

          <h1>
            Welcome <em>back.</em>
          </h1>

          <p>
            Ready to learn something new?
            Your next great deal starts here.
          </p>
        </div>

        <div class="login-card">
          <div class="card-heading">
            <h2>Sign in to Dealora</h2>
            <p>Enter your details to continue learning.</p>
          </div>

          <v-alert
            v-if="message"
            type="error"
            variant="tonal"
            rounded="lg"
            class="mb-5"
          >
            {{ message }}
          </v-alert>

          <v-form
            ref="form"
            @submit.prevent="handleLogin"
          >
            <div class="field-group">
              <label for="email">Email address</label>

              <v-text-field
                id="email"
                v-model="email"
                type="email"
                placeholder="you@example.com"
                prepend-inner-icon="mdi-email-outline"
                variant="outlined"
                density="comfortable"
                rounded="lg"
                autocomplete="email"
                :rules="emailRules"
                hide-details="auto"
                class="auth-input"
              />
            </div>

            <div class="field-group">
              <div class="password-label">
                <label for="password">Password</label>

                <span class="forgot-text">
                  Forgot password?
                </span>
              </div>

              <v-text-field
                id="password"
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="Enter your password"
                prepend-inner-icon="mdi-lock-outline"
                :append-inner-icon="
                  showPassword
                    ? 'mdi-eye-off-outline'
                    : 'mdi-eye-outline'
                "
                @click:append-inner="
                  showPassword = !showPassword
                "
                variant="outlined"
                density="comfortable"
                rounded="lg"
                autocomplete="current-password"
                :rules="passwordRules"
                hide-details="auto"
                class="auth-input"
              />
            </div>

            <v-checkbox
              v-model="rememberMe"
              label="Remember me"
              color="primary"
              density="compact"
              hide-details
              class="remember-checkbox"
            />

            <v-btn
              type="submit"
              block
              size="x-large"
              color="primary"
              rounded="lg"
              elevation="0"
              class="login-button"
              :disabled="!canSubmit"
              :loading="loading"
              append-icon="mdi-arrow-right"
            >
              Sign In
            </v-btn>
          </v-form>

          <div class="signup-prompt">
            New to Dealora?
            <RouterLink to="/register">
              Create an account
            </RouterLink>
          </div>
        </div>

        <p class="bottom-quote">
          Learn the skills.
          <span>Make the deal.</span>
          <v-icon
            icon="mdi-star-four-points"
            size="15"
            color="#B18AF2"
          />
        </p>
      </div>
    </main>

    <!-- Footer -->
    <footer class="auth-footer">
      <span>© {{ new Date().getFullYear() }} Dealora</span>
      <span>Made for curious minds and future dealmakers.</span>
    </footer>
  </v-container>
</template>

<style scoped>
.auth-page {
  min-height: 100vh;
  position: relative;
  overflow: hidden;
  background: #fcfaf8;
  color: #252139;
  display: flex;
  flex-direction: column;
}

/* Background glow */

.glow {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  pointer-events: none;
  z-index: 0;
}

.glow-one {
  width: 430px;
  height: 430px;
  background: rgba(195, 171, 249, 0.25);
  top: 13%;
  left: -200px;
}

.glow-two {
  width: 380px;
  height: 380px;
  background: rgba(251, 207, 232, 0.22);
  bottom: 0;
  right: -160px;
}

/* Header */

.auth-header {
  position: relative;
  z-index: 1;
  padding: 30px clamp(24px, 6vw, 100px);
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.logo {
  font-family: 'Poppins', sans-serif;
  font-size: 2rem;
  font-weight: 800;
  letter-spacing: -1.5px;
  color: #7048d9;
  text-decoration: none;
}

.logo span {
  color: #f472b6;
}

.home-link {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: #716a86;
  font-size: 0.85rem;
  font-weight: 600;
  text-decoration: none;
  transition: color 0.2s;
}

.home-link:hover {
  color: #7048d9;
}

/* Main layout */

.auth-main {
  position: relative;
  z-index: 1;
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 45px 20px 75px;
}

.auth-container {
  width: 100%;
  max-width: 460px;
}

.welcome-heading {
  text-align: center;
  margin-bottom: 35px;
}

.eyebrow {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  color: #8868bc;
  font-size: 0.7rem;
  font-weight: 800;
  letter-spacing: 2px;
  margin-bottom: 20px;
}

.eyebrow-line {
  width: 25px;
  height: 2px;
  background: #b18af2;
}

.welcome-heading h1 {
  font-family: 'Poppins', sans-serif;
  font-size: clamp(2.5rem, 5vw, 3.6rem);
  font-weight: 700;
  letter-spacing: -2.5px;
  line-height: 1.2;
}

.welcome-heading h1 em {
  font-style: normal;
  color: #7048d9;
}

.welcome-heading p {
  margin: 15px auto 0;
  max-width: 330px;
  font-size: 0.93rem;
  line-height: 1.8;
  color: #827b98;
}

/* Login card */

.login-card {
  background: rgba(255, 255, 255, 0.94);
  border: 1px solid #eee9f3;
  border-radius: 24px;
  padding: 38px;
  box-shadow:
    0 20px 65px rgba(65, 43, 102, 0.055),
    0 2px 10px rgba(65, 43, 102, 0.025);
}

.card-heading {
  margin-bottom: 30px;
}

.card-heading h2 {
  font-family: 'Poppins', sans-serif;
  font-size: 1.35rem;
  font-weight: 700;
  color: #252139;
}

.card-heading p {
  color: #91899e;
  font-size: 0.85rem;
  margin-top: 7px;
}

.field-group {
  margin-bottom: 22px;
}

.field-group label {
  display: block;
  color: #39314e;
  font-size: 0.83rem;
  font-weight: 700;
  margin-bottom: 10px;
}

.password-label {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.forgot-text {
  color: #7048d9;
  font-size: 0.78rem;
  font-weight: 600;
}

/* Vuetify inputs */

.auth-input :deep(.v-field) {
  background: #ffffff;
  border-radius: 12px;
  font-size: 0.9rem;
}

.auth-input :deep(.v-field__outline) {
  --v-field-border-opacity: 0.15;
}

.auth-input :deep(.v-field--focused .v-field__outline) {
  --v-field-border-opacity: 1;
}

.auth-input :deep(.v-field__prepend-inner) {
  color: #a29aaf;
}

.remember-checkbox {
  margin-top: -5px;
  margin-bottom: 20px;
}

.remember-checkbox :deep(.v-label) {
  font-size: 0.82rem;
  color: #716a86;
}

/* Button */

.login-button {
  height: 54px !important;
  font-weight: 700;
  font-size: 0.95rem;
  letter-spacing: 0;
  text-transform: none;
  box-shadow: 0 8px 20px rgba(112, 72, 217, 0.17);
  transition: transform 0.2s, box-shadow 0.2s;
}

.login-button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 12px 25px rgba(112, 72, 217, 0.24);
}

.signup-prompt {
  margin-top: 30px;
  text-align: center;
  font-size: 0.85rem;
  color: #827b98;
}

.signup-prompt a {
  color: #7048d9;
  font-weight: 700;
  text-decoration: none;
  margin-left: 4px;
}

.signup-prompt a:hover {
  text-decoration: underline;
}

/* Bottom text */

.bottom-quote {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 5px;
  flex-wrap: wrap;
  margin-top: 35px;
  color: #9c94aa;
  font-size: 0.8rem;
}

.bottom-quote span {
  color: #7048d9;
  font-weight: 700;
}

/* Footer */

.auth-footer {
  position: relative;
  z-index: 1;
  padding: 22px clamp(24px, 6vw, 100px);
  display: flex;
  justify-content: space-between;
  gap: 15px;
  color: #aaa2b3;
  font-size: 0.75rem;
}

/* Responsive */

@media (max-width: 600px) {
  .auth-header {
    padding: 22px;
  }

  .logo {
    font-size: 1.7rem;
  }

  .home-link span {
    display: none;
  }

  .auth-main {
    padding: 35px 18px 55px;
  }

  .login-card {
    padding: 28px 22px;
    border-radius: 20px;
  }

  .welcome-heading h1 {
    letter-spacing: -1.5px;
  }

  .auth-footer {
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 20px;
  }
}

@media (prefers-reduced-motion: reduce) {
  .login-button {
    transition: none;
  }
}
</style>
