<script setup>
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { authService } from '../services/authService'

const router = useRouter()
const authStore = useAuthStore()

const drawer = ref(true)
const loggingOut = ref(false)

const firstName = computed(
  () => authStore.user?.first_name || 'Learner'
)

const initials = computed(() =>
  firstName.value.charAt(0).toUpperCase()
)

const learningPaths = [
  {
    title: 'Sales Fundamentals',
    category: 'SALES',
    description: 'Learn how to connect with customers and close deals confidently.',
    icon: 'mdi-handshake-outline',
    color: '#7048D9',
    background: '#F0EAFE',
    lessons: 12,
  },
  {
    title: 'Negotiation Skills',
    category: 'NEGOTIATION',
    description: 'Master communication, persuasion, and win-win agreements.',
    icon: 'mdi-forum-outline',
    color: '#E46A9D',
    background: '#FDEBF3',
    lessons: 10,
  },
  {
    title: 'Procurement Essentials',
    category: 'PROCUREMENT',
    description: 'Understand sourcing, suppliers, and purchasing processes.',
    icon: 'mdi-clipboard-check-outline',
    color: '#D38B38',
    background: '#FFF2DE',
    lessons: 9,
  },
  {
    title: 'Importation Basics',
    category: 'IMPORTATION',
    description: 'Explore international sourcing, shipping, and importing.',
    icon: 'mdi-earth',
    color: '#328D86',
    background: '#E5F5F2',
    lessons: 11,
  },
]

const navigation = [
  { title: 'Dashboard', icon: 'mdi-view-dashboard-outline', active: true },
  { title: 'My Learning', icon: 'mdi-book-open-page-variant-outline' },
  { title: 'AI Coach', icon: 'mdi-robot-outline' },
  { title: 'Achievements', icon: 'mdi-trophy-outline' },
  { title: 'My Profile', icon: 'mdi-account-circle-outline' },
]

async function handleLogout() {
  if (loggingOut.value) return

  loggingOut.value = true

  try {
    if (authStore.token) {
      await authService.logout(authStore.token)
    }
  } catch (error) {
    console.error('Logout request failed:', error)
  } finally {
    authStore.clearSession()
    loggingOut.value = false
    await router.push('/login')
  }
}
</script>

<template>
  <v-layout class="dashboard-layout">
    <!-- Sidebar -->
    <v-navigation-drawer
      v-model="drawer"
      :width="260"
      color="#FFFFFF"
      class="sidebar"
    >
      <div class="sidebar-content">
        <RouterLink to="/" class="brand">
          dealora<span>.</span>
        </RouterLink>

        <div class="sidebar-label">YOUR SPACE</div>

        <v-list nav class="navigation-list">
          <v-list-item
            v-for="item in navigation"
            :key="item.title"
            :prepend-icon="item.icon"
            :title="item.title"
            :active="item.active"
            color="primary"
            rounded="lg"
            class="nav-item"
          />
        </v-list>

        <v-spacer />

        <div class="sidebar-bottom">
          <div class="sidebar-tip">
            <v-icon
              icon="mdi-lightbulb-on-outline"
              color="#7048D9"
              size="24"
            />
            <h4>Keep growing!</h4>
            <p>
              Every great deal starts with something new you learn.
            </p>
          </div>

          <v-btn
            block
            variant="text"
            color="#716A86"
            prepend-icon="mdi-logout"
            :loading="loggingOut"
            @click="handleLogout"
          >
            Log Out
          </v-btn>
        </div>
      </div>
    </v-navigation-drawer>

    <!-- Main -->
    <v-main class="dashboard-main">
      <!-- Topbar -->
      <header class="topbar">
        <div class="topbar-left">
          <v-btn
            icon="mdi-menu"
            variant="text"
            class="menu-button"
            @click="drawer = !drawer"
          />

          <span class="page-label">My Dashboard</span>
        </div>

        <div class="topbar-right">
          <v-avatar color="#EEE6FF" size="38">
            <span class="avatar-text">{{ initials }}</span>
          </v-avatar>

          <span class="topbar-name">{{ firstName }}</span>
        </div>
      </header>

      <div class="dashboard-content">
        <!-- Welcome -->
        <section class="welcome-banner">
          <div class="welcome-copy">
            <div class="welcome-eyebrow">
              <v-icon icon="mdi-star-four-points" size="16" />
              YOUR LEARNING JOURNEY
            </div>

            <h1>
              Welcome back,
              <span>{{ firstName }}!</span> 💜
            </h1>

            <p>
              Big dreams start with small steps.
              Ready to learn something amazing today?
            </p>

            <v-btn
              color="white"
              class="welcome-button"
              rounded="lg"
              elevation="0"
              append-icon="mdi-arrow-right"
              href="#learning-paths"
            >
              Explore Learning Paths
            </v-btn>
          </div>

          <div class="welcome-decoration">
            <v-icon icon="mdi-auto-fix" size="110" />
          </div>
        </section>

        <!-- Overview -->
        <section class="overview-section">
          <div class="section-heading">
            <div>
              <h2>Your learning overview</h2>
              <p>A fresh start to your dealmaking journey.</p>
            </div>
          </div>

          <v-row>
            <v-col cols="12" sm="4">
              <div class="stat-card">
                <div class="stat-icon purple">
                  <v-icon icon="mdi-book-open-outline" />
                </div>
                <div>
                  <h3>0</h3>
                  <p>Courses in progress</p>
                </div>
              </div>
            </v-col>

            <v-col cols="12" sm="4">
              <div class="stat-card">
                <div class="stat-icon pink">
                  <v-icon icon="mdi-check-circle-outline" />
                </div>
                <div>
                  <h3>0</h3>
                  <p>Lessons completed</p>
                </div>
              </div>
            </v-col>

            <v-col cols="12" sm="4">
              <div class="stat-card">
                <div class="stat-icon orange">
                  <v-icon icon="mdi-trophy-outline" />
                </div>
                <div>
                  <h3>0</h3>
                  <p>Achievements earned</p>
                </div>
              </div>
            </v-col>
          </v-row>
        </section>

        <!-- Learning Paths -->
        <section id="learning-paths" class="learning-section">
          <div class="section-heading">
            <div>
              <h2>Explore learning paths</h2>
              <p>
                Discover skills that help you make your next big move.
              </p>
            </div>
            <span class="section-tag">MADE FOR YOU ✨</span>
          </div>

          <v-row>
            <v-col
              v-for="path in learningPaths"
              :key="path.title"
              cols="12"
              md="6"
              lg="3"
            >
              <div class="course-card">
                <div
                  class="course-icon"
                  :style="{
                    background: path.background,
                    color: path.color
                  }"
                >
                  <v-icon :icon="path.icon" size="30" />
                </div>

                <div class="course-category">
                  {{ path.category }}
                </div>

                <h3>{{ path.title }}</h3>
                <p>{{ path.description }}</p>

                <div class="course-footer">
                  <span>
                    <v-icon
                      icon="mdi-book-open-outline"
                      size="16"
                    />
                    {{ path.lessons }} lessons
                  </span>
                  <v-icon
                    icon="mdi-arrow-top-right"
                    color="#7048D9"
                  />
                </div>
              </div>
            </v-col>
          </v-row>
        </section>

        <!-- AI Coach -->
        <section class="coach-section">
          <div class="coach-icon">
            <v-icon icon="mdi-robot-happy-outline" size="38" />
          </div>

          <div class="coach-copy">
            <span>COMING SOON</span>
            <h2>Meet your AI Deal Coach ✨</h2>
            <p>
              Practice sales conversations, sharpen negotiation
              skills, and build confidence through realistic
              scenarios.
            </p>
          </div>

          <v-btn
            variant="outlined"
            color="#7048D9"
            rounded="lg"
            disabled
          >
            Coming Soon
          </v-btn>
        </section>

        <footer class="dashboard-footer">
          Learn the skills. <strong>Make the deal.</strong> 💜
        </footer>
      </div>
    </v-main>
  </v-layout>
</template>

<style scoped>
.dashboard-layout {
  min-height: 100vh;
  background: #FCFAF8;
  color: #252139;
}

.sidebar {
  border-right: 1px solid #EEE9F3;
}

.sidebar-content {
  display: flex;
  flex-direction: column;
  min-height: 100%;
  padding: 28px 14px;
}

.brand {
  font-family: 'Poppins', sans-serif;
  font-size: 2rem;
  font-weight: 800;
  letter-spacing: -1.5px;
  color: #7048D9;
  text-decoration: none;
  padding: 0 15px;
  margin-bottom: 45px;
}

.brand span {
  color: #F472B6;
}

.sidebar-label {
  padding: 0 15px;
  color: #AAA2B3;
  font-size: 0.7rem;
  font-weight: 800;
  letter-spacing: 1.5px;
  margin-bottom: 15px;
}

.navigation-list {
  padding: 0;
}

.nav-item {
  margin-bottom: 8px;
  font-weight: 600;
}

.sidebar-bottom {
  margin-top: auto;
}

.sidebar-tip {
  background: #F5F0FF;
  border-radius: 16px;
  padding: 20px;
  margin: 15px 5px 20px;
}

.sidebar-tip h4 {
  margin-top: 12px;
  font-weight: 700;
  color: #7048D9;
}

.sidebar-tip p {
  font-size: 0.78rem;
  line-height: 1.7;
  color: #827B98;
  margin-top: 7px;
}

.dashboard-main {
  background: #FCFAF8;
}

.topbar {
  min-height: 82px;
  background: white;
  border-bottom: 1px solid #EEE9F3;
  padding: 0 35px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.topbar-left,
.topbar-right {
  display: flex;
  align-items: center;
  gap: 15px;
}

.page-label {
  font-size: 0.9rem;
  font-weight: 700;
}

.avatar-text {
  color: #7048D9;
  font-weight: 800;
}

.topbar-name {
  font-size: 0.85rem;
  font-weight: 700;
}

.dashboard-content {
  max-width: 1450px;
  margin: auto;
  padding: 38px;
}

.welcome-banner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: relative;
  overflow: hidden;
  border-radius: 24px;
  padding: 45px;
  background: linear-gradient(120deg, #7048D9, #A17AF0);
  color: white;
}

.welcome-copy {
  position: relative;
  z-index: 1;
}

.welcome-eyebrow {
  font-size: 0.72rem;
  font-weight: 800;
  letter-spacing: 1.5px;
  margin-bottom: 20px;
}

.welcome-banner h1 {
  font-family: 'Poppins', sans-serif;
  font-size: clamp(2rem, 3vw, 3rem);
  line-height: 1.25;
  font-weight: 700;
  margin-bottom: 15px;
}

.welcome-banner h1 span {
  color: #FFE0F0;
}

.welcome-banner p {
  max-width: 450px;
  line-height: 1.8;
  opacity: 0.9;
  margin-bottom: 25px;
}

.welcome-button {
  color: #7048D9 !important;
  font-weight: 700;
  text-transform: none;
}

.welcome-decoration {
  opacity: 0.2;
  transform: rotate(-15deg);
  margin-right: 55px;
}

.section-heading {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 15px;
  margin-bottom: 22px;
}

.section-heading h2 {
  font-family: 'Poppins', sans-serif;
  font-size: 1.4rem;
  font-weight: 700;
}

.section-heading p {
  font-size: 0.85rem;
  color: #91899E;
  margin-top: 5px;
}

.overview-section,
.learning-section {
  margin-top: 42px;
}

.stat-card {
  display: flex;
  align-items: center;
  gap: 17px;
  background: white;
  border: 1px solid #EEE9F3;
  border-radius: 18px;
  padding: 25px;
  height: 100%;
}

.stat-icon {
  width: 55px;
  height: 55px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.stat-icon.purple {
  background: #F0EAFE;
  color: #7048D9;
}

.stat-icon.pink {
  background: #FDEBF3;
  color: #E46A9D;
}

.stat-icon.orange {
  background: #FFF2DE;
  color: #D38B38;
}

.stat-card h3 {
  font-size: 1.7rem;
  font-weight: 800;
}

.stat-card p {
  color: #91899E;
  font-size: 0.78rem;
}

.section-tag {
  color: #8868BC;
  font-size: 0.7rem;
  font-weight: 800;
  letter-spacing: 1px;
}

.course-card {
  background: white;
  border: 1px solid #EEE9F3;
  border-radius: 20px;
  padding: 25px;
  height: 100%;
  display: flex;
  flex-direction: column;
  transition: transform 0.2s, box-shadow 0.2s;
}

.course-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(65, 43, 102, 0.08);
}

.course-icon {
  width: 62px;
  height: 62px;
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 25px;
}

.course-category {
  font-size: 0.65rem;
  font-weight: 800;
  letter-spacing: 1.4px;
  color: #91899E;
  margin-bottom: 10px;
}

.course-card h3 {
  font-size: 1.1rem;
  font-weight: 700;
  margin-bottom: 12px;
}

.course-card p {
  font-size: 0.82rem;
  color: #827B98;
  line-height: 1.7;
  flex: 1;
}

.course-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-top: 1px solid #F0ECF5;
  margin-top: 25px;
  padding-top: 17px;
  color: #91899E;
  font-size: 0.75rem;
}

.coach-section {
  margin-top: 42px;
  background: #F2ECFF;
  border: 1px solid #E6DAFF;
  border-radius: 22px;
  padding: 30px;
  display: flex;
  align-items: center;
  gap: 25px;
}

.coach-icon {
  width: 75px;
  height: 75px;
  flex-shrink: 0;
  border-radius: 20px;
  background: white;
  color: #7048D9;
  display: flex;
  align-items: center;
  justify-content: center;
}

.coach-copy {
  flex: 1;
}

.coach-copy span {
  font-size: 0.68rem;
  color: #8868BC;
  font-weight: 800;
  letter-spacing: 1px;
}

.coach-copy h2 {
  font-size: 1.3rem;
  font-weight: 700;
  margin: 8px 0;
}

.coach-copy p {
  color: #827B98;
  font-size: 0.85rem;
  line-height: 1.7;
}

.dashboard-footer {
  text-align: center;
  padding: 40px 0 10px;
  color: #AAA2B3;
  font-size: 0.8rem;
}

.dashboard-footer strong {
  color: #7048D9;
}

@media (max-width: 960px) {
  .dashboard-content {
    padding: 25px;
  }

  .welcome-banner {
    padding: 35px;
  }

  .welcome-decoration {
    display: none;
  }
}

@media (max-width: 600px) {
  .topbar {
    padding: 0 15px;
  }

  .dashboard-content {
    padding: 18px;
  }

  .welcome-banner {
    padding: 28px;
  }

  .coach-section {
    flex-direction: column;
    align-items: flex-start;
  }

  .topbar-name,
  .section-tag {
    display: none;
  }
}

@media (prefers-reduced-motion: reduce) {
  .course-card {
    transition: none;
  }
}
</style>
