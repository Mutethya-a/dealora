
<script setup>
import { ref, computed } from 'vue'
import { RouterLink } from 'vue-router'

const form = ref(null)

const firstName = ref('')
const lastName = ref('')
const email = ref('')
const password = ref('')
const confirmPassword = ref('')

const showPassword = ref(false)
const showConfirmPassword = ref(false)
const acceptTerms = ref(false)
const loading = ref(false)
const message = ref('')

const nameRules = [
  v => !!v?.trim() || 'This field is required',
  v => v?.trim().length >= 2 ||
    'Must contain at least 2 characters',
]

const emailRules = [
  v => !!v?.trim() || 'Email address is required',
  v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) ||
    'Enter a valid email address',
]

const passwordRules = [
  v => !!v || 'Password is required',
  v => v?.length >= 8 ||
    'Password must contain at least 8 characters',
  v => /[A-Z]/.test(v) ||
    'Include at least one uppercase letter',
  v => /[0-9]/.test(v) ||
    'Include at least one number',
]

const confirmPasswordRules = [
  v => !!v || 'Please confirm your password',
  v => v === password.value || 'Passwords do not match',
]

const canSubmit = computed(() =>
  firstName.value.trim() &&
  lastName.value.trim() &&
  email.value.trim() &&
  password.value &&
  confirmPassword.value &&
  acceptTerms.value
)

async function handleRegister() {
  message.value = ''

  const { valid } = await form.value.validate()

  if (!valid || !acceptTerms.value) return

  loading.value = true

  try {
    // Laravel registration endpoint will be connected next.
    message.value =
      'Registration form validated! Backend integration comes next.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <v-container fluid class="auth-page pa-0">
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

    <main class="auth-main">
      <div class="auth-container">

        <!-- Heading -->
        <div class="welcome-heading">
          <div class="eyebrow">
            <span class="eyebrow-line"></span>
            YOUR JOURNEY STARTS HERE
          </div>

          <h1>
            Start something <em>great.</em>
          </h1>

          <p>
            Build confidence, sharpen your skills,
            and become the dealmaker you want to be.
          </p>
        </div>

        <!-- Registration Card -->
        <div class="register-card">
          <div class="card-heading">
            <h2>Create your account</h2>
            <p>Join Dealora and start learning today.</p>
          </div>

          <v-alert
            v-if="message"
            type="info"
            variant="tonal"
            rounded="lg"
            class="mb-5"
          >
            {{ message }}
          </v-alert>

          <v-form
            ref="form"
            @submit.prevent="handleRegister"
          >
            <!-- Names -->
            <v-row dense>
              <v-col cols="12" sm="6">
                <div class="field-group">
                  <label for="firstName">First name</label>

                  <v-text-field
                    id="firstName"
                    v-model="firstName"
                    placeholder="Jane"
                    variant="outlined"
                    rounded="lg"
                    density="comfortable"
                    autocomplete="given-name"
                    :rules="nameRules"
                    hide-details="auto"
                    class="auth-input"
                  />
                </div>
              </v-col>

              <v-col cols="12" sm="6">
                <div class="field-group">
                  <label for="lastName">Last name</label>

                  <v-text-field
                    id="lastName"
                    v-model="lastName"
                    placeholder="Doe"
                    variant="outlined"
                    rounded="lg"
                    density="comfortable"
                    autocomplete="family-name"
                    :rules="nameRules"
                    hide-details="auto"
                    class="auth-input"
                  />
                </div>
              </v-col>
            </v-row>

            <!-- Email -->
            <div class="field-group">
              <label for="email">Email address</label>

              <v-text-field
                id="email"
                v-model="email"
                type="email"
                placeholder="you@example.com"
                prepend-inner-icon="mdi-email-outline"
                variant="outlined"
                rounded="lg"
                density="comfortable"
                autocomplete="email"
                :rules="emailRules"
                hide-details="auto"
                class="auth-input"
              />
            </div>

            <!-- Password -->
            <div class="field-group">
              <label for="password">Password</label>

              <v-text-field
                id="password"
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="Create a strong password"
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
                rounded="lg"
                density="comfortable"
                autocomplete="new-password"
                :rules="passwordRules"
                hide-details="auto"
                class="auth-input"
              />

              <p class="password-hint">
                Use 8+ characters, an uppercase letter,
                and a number.
              </p>
            </div>

            <!-- Confirm Password -->
            <div class="field-group">
              <label for="confirmPassword">
                Confirm password
              </label>

              <v-text-field
                id="confirmPassword"
                v-model="confirmPassword"
                :type="
                  showConfirmPassword
                    ? 'text'
                    : 'password'
                "
                placeholder="Repeat your password"
                prepend-inner-icon="mdi-lock-check-outline"
                :append-inner-icon="
                  showConfirmPassword
                    ? 'mdi-eye-off-outline'
                    : 'mdi-eye-outline'
                "
                @click:append-inner="
                  showConfirmPassword =
                    !showConfirmPassword
                "
                variant="outlined"
                rounded="lg"
                density="comfortable"
                autocomplete="new-password"
                :rules="confirmPasswordRules"
                hide-details="auto"
                class="auth-input"
              />
            </div>

            <!-- Terms -->
            <v-checkbox
              v-model="acceptTerms"
              color="primary"
              density="compact"
              hide-details
              class="terms-checkbox"
            >
              <template #label>
                <span class="terms-label">
                  I agree to Dealora's Terms of Service
                  and Privacy Policy.
                </span>
              </template>
            </v-checkbox>

            <!-- Submit -->
            <v-btn
              type="submit"
              block
              size="x-large"
              color="primary"
              rounded="lg"
              elevation="0"
              class="register-button"
              :disabled="!canSubmit"
              :loading="loading"
              append-icon="mdi-arrow-right"
            >
              Create Account
            </v-btn>
          </v-form>

          <div class="signin-prompt">
            Already have an account?
            <RouterLink to="/login">
              Sign in
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

.glow {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  pointer-events: none;
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

.auth-header {
  position: relative;
  z-index: 1;
  padding: 30px clamp(24px, 6vw, 100px);
  display: flex;
  justify-content: space-between;
  align-items: center;
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
  display: flex;
  align-items: center;
  gap: 8px;
  color: #716a86;
  font-size: 0.85rem;
  font-weight: 600;
  text-decoration: none;
}

.home-link:hover {
  color: #7048d9;
}

.auth-main {
  position: relative;
  z-index: 1;
  flex: 1;
  display: flex;
  justify-content: center;
  padding: 45px 20px 75px;
}

.auth-container {
  width: 100%;
  max-width: 520px;
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
  font-size: clamp(2.4rem, 5vw, 3.4rem);
  font-weight: 700;
  letter-spacing: -2px;
}

.welcome-heading h1 em {
  font-style: normal;
  color: #7048d9;
}

.welcome-heading p {
  max-width: 390px;
  margin: 14px auto 0;
  color: #827b98;
  font-size: 0.93rem;
  line-height: 1.8;
}

.register-card {
  background: rgba(255, 255, 255, 0.94);
  border: 1px solid #eee9f3;
  border-radius: 24px;
  padding: 38px;
  box-shadow: 0 20px 65px rgba(65, 43, 102, 0.055);
}

.card-heading {
  margin-bottom: 30px;
}

.card-heading h2 {
  font-family: 'Poppins', sans-serif;
  font-size: 1.35rem;
  font-weight: 700;
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

.auth-input :deep(.v-field) {
  background: white;
  border-radius: 12px;
  font-size: 0.9rem;
}

.auth-input :deep(.v-field__outline) {
  --v-field-border-opacity: 0.15;
}

.auth-input :deep(.v-field__prepend-inner) {
  color: #a29aaf;
}

.password-hint {
  font-size: 0.75rem;
  color: #91899e;
  margin-top: 8px;
  line-height: 1.6;
}

.terms-checkbox {
  margin-bottom: 22px;
}

.terms-label {
  color: #716a86;
  font-size: 0.8rem;
  line-height: 1.6;
}

.register-button {
  height: 54px !important;
  font-size: 0.95rem;
  font-weight: 700;
  letter-spacing: 0;
  text-transform: none;
  box-shadow: 0 8px 20px rgba(112, 72, 217, 0.17);
  transition: transform 0.2s, box-shadow 0.2s;
}

.register-button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 12px 25px rgba(112, 72, 217, 0.24);
}

.signin-prompt {
  margin-top: 30px;
  text-align: center;
  color: #827b98;
  font-size: 0.85rem;
}

.signin-prompt a {
  color: #7048d9;
  font-weight: 700;
  text-decoration: none;
  margin-left: 4px;
}

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

@media (max-width: 600px) {
  .auth-header {
    padding: 22px;
  }

  .home-link span {
    display: none;
  }

  .auth-main {
    padding: 35px 18px 55px;
  }

  .register-card {
    padding: 28px 22px;
    border-radius: 20px;
  }

  .auth-footer {
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 20px;
  }
}

@media (prefers-reduced-motion: reduce) {
  .register-button {
    transition: none;
  }
}
</style>
