<script setup>
import { ref, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import learningService from '../services/learningService'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const lesson = ref(null)
const course = ref(null)
const loading = ref(true)
const error = ref('')
const sidebarOpen = ref(true)

const lessonContent = computed(() => lesson.value?.content || '')

const formattedContent = computed(() => {
  const content = lessonContent.value

  if (!content) return []

  return content
    .split(/\n\s*\n/)
    .map((paragraph) => paragraph.trim())
    .filter(Boolean)
})

async function fetchLesson() {
  loading.value = true
  error.value = ''
  lesson.value = null

  try {
    const courseSlug = route.params.courseSlug
    const lessonSlug = route.params.lessonSlug

    const response = await learningService.getLesson(
      courseSlug,
      lessonSlug,
      authStore.token
    )

    lesson.value = response
    course.value = response.course
  } catch (err) {
    console.error('Failed to load lesson:', err)

    if (err.response?.status === 403) {
      error.value = 'You need to enroll in this course to access this lesson.'
    } else if (err.response?.status === 404) {
      error.value = 'This lesson could not be found.'
    } else {
      error.value = 'Unable to load this lesson. Please try again.'
    }
  } finally {
    loading.value = false
  }
}

function backToCourse() {
  router.push(`/courses/${route.params.courseSlug}`)
}

watch(
  () => [route.params.courseSlug, route.params.lessonSlug],
  () => fetchLesson(),
  { immediate: true }
)
</script>

<template>
  <div class="lesson-page">
    <!-- Header -->
    <header class="lesson-header">
      <RouterLink to="/dashboard" class="brand">
        dealora<span>.</span>
      </RouterLink>

      <div class="header-center">
        <v-icon icon="mdi-book-open-page-variant-outline" size="19" />
        <span>{{ course?.title || 'My Learning' }}</span>
      </div>

      <v-btn
        variant="text"
        prepend-icon="mdi-arrow-left"
        color="#7048D9"
        @click="backToCourse"
      >
        Back to Course
      </v-btn>
    </header>

    <!-- Loading -->
    <div v-if="loading" class="center-state">
      <v-progress-circular
        indeterminate
        color="#7048D9"
        size="50"
      />
      <p>Preparing your lesson...</p>
    </div>

    <!-- Error -->
    <div v-else-if="error" class="center-state">
      <v-icon icon="mdi-alert-circle-outline" color="#E879A7" size="58" />
      <h2>Unable to open lesson</h2>
      <p>{{ error }}</p>
      <v-btn color="#7048D9" rounded="lg" @click="backToCourse">
        Back to Course
      </v-btn>
    </div>

    <!-- Lesson -->
    <div v-else-if="lesson" class="lesson-layout">
      <!-- Sidebar -->
      <aside v-if="sidebarOpen" class="lesson-sidebar">
        <div class="sidebar-heading">
          <div class="eyebrow">YOUR CLASSROOM</div>
          <h2>{{ course?.title }}</h2>
          <p>Take it one lesson at a time.</p>
        </div>

        <div class="sidebar-divider"></div>

        <div class="current-lesson">
          <div class="current-label">
            <v-icon icon="mdi-play-circle-outline" size="18" />
            CURRENT LESSON
          </div>

          <div class="current-lesson-title">
            {{ lesson.title }}
          </div>

          <div class="current-duration">
            <v-icon icon="mdi-clock-outline" size="16" />
            {{ lesson.duration_minutes }} minutes
          </div>
        </div>

        <div class="sidebar-bottom">
          <v-icon icon="mdi-lightbulb-on-outline" color="#7048D9" size="24" />
          <p>Small lessons. Big opportunities. Keep learning!</p>
        </div>
      </aside>

      <!-- Reading Area -->
      <main class="lesson-main">
        <div class="lesson-topline">
          <v-btn
            icon="mdi-menu-open"
            variant="text"
            color="#7048D9"
            @click="sidebarOpen = !sidebarOpen"
          />

          <span>
            <v-icon icon="mdi-clock-outline" size="17" />
            {{ lesson.duration_minutes }} min read
          </span>
        </div>

        <div class="lesson-reading-card">
          <div class="lesson-eyebrow">
            <v-icon icon="mdi-star-four-points" size="16" />
            LESSON {{ lesson.position }}
          </div>

          <h1>{{ lesson.title }}</h1>

          <div class="lesson-intro">
            Every skill you learn brings you one step closer to
            making your next great deal.
          </div>

          <div class="content-divider"></div>

          <div v-if="formattedContent.length" class="lesson-body">
            <p
              v-for="(paragraph, index) in formattedContent"
              :key="index"
            >
              {{ paragraph }}
            </p>
          </div>

          <div v-else class="empty-content">
            <v-icon icon="mdi-text-box-outline" size="48" color="#B9A5E8" />
            <h3>Lesson content coming soon</h3>
            <p>We're preparing the learning materials for this lesson.</p>
          </div>

          <div class="lesson-end-note">
            <v-icon icon="mdi-heart-outline" color="#7048D9" size="21" />
            <span>You've taken another step toward becoming a confident dealmaker.</span>
          </div>
        </div>

        <div class="lesson-actions">
          <v-btn
            variant="outlined"
            color="#7048D9"
            prepend-icon="mdi-arrow-left"
            rounded="lg"
            @click="backToCourse"
          >
            Course Overview
          </v-btn>

          <v-btn
            color="#7048D9"
            rounded="lg"
            prepend-icon="mdi-check-circle-outline"
            disabled
          >
            Mark Complete — Coming Next
          </v-btn>
        </div>
      </main>
    </div>

    <footer class="lesson-footer">
      Learn the skills. <strong>Make the deal.</strong>
    </footer>
  </div>
</template>

<style scoped>
.lesson-page {
  min-height: 100vh;
  background: #fcfaf8;
  color: #29213c;
  display: flex;
  flex-direction: column;
}

.lesson-header {
  height: 82px;
  background: #ffffff;
  border-bottom: 1px solid #eee9f3;
  padding: 0 5%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 20px;
}

.brand {
  font-family: 'Poppins', sans-serif;
  font-size: 1.9rem;
  font-weight: 800;
  letter-spacing: -1.5px;
  color: #7048d9;
  text-decoration: none;
}

.brand span {
  color: #f472b6;
}

.header-center {
  display: flex;
  align-items: center;
  gap: 10px;
  color: #625a76;
  font-size: 0.88rem;
  font-weight: 700;
}

.lesson-layout {
  display: flex;
  flex: 1;
  min-height: 75vh;
}

.lesson-sidebar {
  width: 300px;
  flex-shrink: 0;
  background: #ffffff;
  border-right: 1px solid #eee9f3;
  padding: 35px 24px;
  display: flex;
  flex-direction: column;
}

.eyebrow {
  color: #a17cd8;
  font-size: 0.68rem;
  font-weight: 800;
  letter-spacing: 1.4px;
  margin-bottom: 13px;
}

.sidebar-heading h2 {
  font-family: 'Poppins', sans-serif;
  font-size: 1.25rem;
  font-weight: 750;
  line-height: 1.4;
}

.sidebar-heading p {
  color: #91899e;
  font-size: 0.82rem;
  margin-top: 12px;
}

.sidebar-divider {
  height: 1px;
  background: #f0ecf5;
  margin: 30px 0;
}

.current-lesson {
  padding: 20px;
  background: #f3edff;
  border: 1px solid #e9dcff;
  border-radius: 16px;
}

.current-label {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #7048d9;
  font-size: 0.69rem;
  font-weight: 800;
  letter-spacing: 0.8px;
}

.current-lesson-title {
  font-size: 0.95rem;
  font-weight: 750;
  margin: 15px 0 12px;
  line-height: 1.5;
}

.current-duration {
  display: flex;
  align-items: center;
  gap: 7px;
  color: #91899e;
  font-size: 0.78rem;
}

.sidebar-bottom {
  margin-top: auto;
  padding: 22px 0;
}

.sidebar-bottom p {
  color: #91899e;
  font-size: 0.82rem;
  line-height: 1.7;
  margin-top: 12px;
}

.lesson-main {
  flex: 1;
  min-width: 0;
  max-width: 1050px;
  padding: 30px 45px 70px;
  margin: 0 auto;
}

.lesson-topline {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 22px;
}

.lesson-topline span {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  color: #91899e;
  font-size: 0.82rem;
}

.lesson-reading-card {
  background: #ffffff;
  border: 1px solid #eee9f3;
  border-radius: 22px;
  padding: 55px;
}

.lesson-eyebrow {
  display: flex;
  align-items: center;
  gap: 9px;
  color: #9772db;
  font-size: 0.72rem;
  font-weight: 800;
  letter-spacing: 1.5px;
  margin-bottom: 20px;
}

.lesson-reading-card h1 {
  font-family: 'Poppins', sans-serif;
  font-size: clamp(2rem, 3vw, 2.8rem);
  font-weight: 800;
  line-height: 1.25;
  margin-bottom: 22px;
}

.lesson-intro {
  font-size: 1.05rem;
  line-height: 1.9;
  color: #827b98;
}

.content-divider {
  height: 1px;
  background: #f0ecf5;
  margin: 35px 0;
}

.lesson-body p {
  color: #51495f;
  font-size: 0.98rem;
  line-height: 2;
  margin-bottom: 23px;
  white-space: pre-line;
}

.empty-content {
  text-align: center;
  padding: 50px 15px;
}

.empty-content h3 {
  margin-top: 15px;
}

.empty-content p {
  margin-top: 10px;
  color: #91899e;
}

.lesson-end-note {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 22px;
  background: #f6f1ff;
  border-radius: 14px;
  color: #6c5b87;
  font-size: 0.86rem;
  margin-top: 40px;
  line-height: 1.7;
}

.lesson-actions {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 15px;
  margin-top: 25px;
}

.lesson-footer {
  padding: 28px;
  text-align: center;
  background: #ffffff;
  border-top: 1px solid #eee9f3;
  color: #a29aad;
  font-size: 0.82rem;
}

.lesson-footer strong {
  color: #7048d9;
}

.center-state {
  flex: 1;
  min-height: 65vh;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  gap: 20px;
  text-align: center;
  padding: 25px;
}

.center-state p {
  color: #91899e;
}

@media (max-width: 900px) {
  .lesson-sidebar {
    width: 240px;
  }

  .lesson-main {
    padding: 25px;
  }

  .lesson-reading-card {
    padding: 35px;
  }
}

@media (max-width: 650px) {
  .lesson-header {
    padding: 0 15px;
  }

  .header-center {
    display: none;
  }

  .lesson-layout {
    flex-direction: column;
  }

  .lesson-sidebar {
    width: 100%;
    border-right: none;
    border-bottom: 1px solid #eee9f3;
  }

  .lesson-main {
    width: 100%;
    padding: 18px 15px 40px;
  }

  .lesson-reading-card {
    padding: 27px 22px;
  }
}
</style>
