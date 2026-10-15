<script setup>
import { ref, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import learningService from '../services/learningService'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const course = ref(null)
const loading = ref(true)
const error = ref('')

const enrolled = ref(false)
const enrolling = ref(false)
const checkingEnrollment = ref(false)
const enrollmentError = ref('')

const lessons = computed(() => {
  return [...(course.value?.lessons || [])].sort(
    (a, b) => a.position - b.position
  )
})

const totalDuration = computed(() => {
  const minutes = course.value?.duration_minutes || 0

  if (minutes < 60) return `${minutes} min`

  const hours = Math.floor(minutes / 60)
  const remaining = minutes % 60

  return remaining
    ? `${hours}h ${remaining}m`
    : `${hours}h`
})

const learningPathName = computed(() => {
  return course.value?.learning_path?.title || 'Learning Path'
})

const learningPathIcon = computed(() => {
  return course.value?.learning_path?.icon || 'mdi-school-outline'
})

async function checkEnrollment(slug) {
  enrolled.value = false
  enrollmentError.value = ''

  if (!authStore.token) return

  checkingEnrollment.value = true

  try {
    const response = await learningService.getEnrollmentStatus(
      slug,
      authStore.token
    )

    enrolled.value = response.enrolled === true
  } catch (err) {
    console.error('Enrollment status error:', err)
    enrollmentError.value =
      'Unable to check your enrollment status.'
  } finally {
    checkingEnrollment.value = false
  }
}

let requestId = 0

async function fetchCourse(slug) {
  const currentRequest = ++requestId

  loading.value = true
  error.value = ''
  enrollmentError.value = ''
  enrolled.value = false
  course.value = null

  try {
    const result = await learningService.getCourse(slug)

    if (currentRequest !== requestId) return

    course.value = result

    await checkEnrollment(slug)
  } catch (err) {
    if (currentRequest !== requestId) return

    console.error('Failed to fetch course:', err)

    error.value =
      err.response?.status === 404
        ? 'This course could not be found.'
        : 'Something went wrong while loading this course.'
  } finally {
    if (currentRequest === requestId) {
      loading.value = false
    }
  }
}

async function handleEnrollment() {
  if (!course.value || enrolled.value || enrolling.value) return

  if (!authStore.token) {
    router.push('/login')
    return
  }

  enrolling.value = true
  enrollmentError.value = ''

  try {
    await learningService.enrollInCourse(
      course.value.slug,
      authStore.token
    )

    enrolled.value = true
  } catch (err) {
    console.error('Enrollment failed:', err)

    enrollmentError.value =
      err.response?.data?.message ||
      'Unable to enroll in this course. Please try again.'
  } finally {
    enrolling.value = false
  }
}

watch(
  () => route.params.slug,
  (slug) => {
    if (slug) {
      fetchCourse(slug)
    }
  },
  { immediate: true }
)
</script>

<template>
  <div class="course-page">
    <!-- Navigation -->
    <header class="course-header">
      <RouterLink to="/" class="brand">
        dealora<span>.</span>
      </RouterLink>

      <v-btn
        variant="text"
        prepend-icon="mdi-arrow-left"
        color="#7048D9"
        rounded="lg"
        @click="router.push('/dashboard')"
      >
        Back to Dashboard
      </v-btn>
    </header>

    <main class="course-container">
      <!-- Loading -->
      <div v-if="loading" class="loading-state">
        <v-progress-circular
          indeterminate
          color="#7048D9"
          size="52"
        />

        <p>Getting your course ready...</p>
      </div>

      <!-- Error -->
      <div v-else-if="error" class="error-state">
        <v-icon
          icon="mdi-alert-circle-outline"
          size="65"
          color="#E879A7"
        />

        <h2>Oops! Something went wrong.</h2>
        <p>{{ error }}</p>

        <v-btn
          color="#7048D9"
          rounded="lg"
          @click="router.push('/dashboard')"
        >
          Return to Dashboard
        </v-btn>
      </div>

      <template v-else-if="course">
        <!-- Course Hero -->
        <section class="course-hero">
          <div class="hero-content">
            <div class="hero-eyebrow">
              <v-icon
                :icon="learningPathIcon"
                size="18"
              />
              {{ learningPathName }}
            </div>

            <h1>{{ course.title }}</h1>

            <p class="hero-description">
              {{ course.description }}
            </p>

            <div class="hero-meta">
              <span>
                <v-icon icon="mdi-school-outline" size="18" />
                {{ course.level || 'Beginner' }}
              </span>

              <span>
                <v-icon icon="mdi-clock-outline" size="18" />
                {{ totalDuration }}
              </span>

              <span>
                <v-icon icon="mdi-book-open-outline" size="18" />
                {{ lessons.length }}
                {{ lessons.length === 1 ? 'lesson' : 'lessons' }}
              </span>
            </div>
          </div>

          <div class="hero-art">
            <div class="hero-art-circle">
              <v-icon
                :icon="learningPathIcon"
                size="85"
              />
            </div>
          </div>
        </section>

        <!-- Course Information -->
        <div class="details-grid">
          <!-- Overview -->
          <section class="overview-card">
            <div class="section-eyebrow">
              GET TO KNOW YOUR COURSE
            </div>

            <h2>Course Overview</h2>

            <p>
              {{
                course.description ||
                'Build practical skills and grow your confidence through this course.'
              }}
            </p>

            <div class="overview-divider"></div>

            <h3>What you'll explore</h3>

            <div
              v-if="lessons.length"
              class="overview-topics"
            >
              <div
                v-for="lesson in lessons.slice(0, 4)"
                :key="lesson.id"
                class="overview-topic"
              >
                <v-icon
                  icon="mdi-check-circle-outline"
                  color="#7048D9"
                  size="21"
                />

                <span>{{ lesson.title }}</span>
              </div>
            </div>

            <p v-else class="empty-note">
              Your course lessons are being prepared.
            </p>
          </section>

          <!-- Enrollment Card -->
          <aside class="summary-card">
            <div class="summary-icon">
              <v-icon
                icon="mdi-book-education-outline"
                size="35"
              />
            </div>

            <h2>Ready to get started?</h2>

            <p class="summary-description">
              Take the next step in your learning journey.
              Build skills that help you make the deal.
            </p>

            <div class="summary-divider"></div>

            <div class="summary-row">
              <span>
                <v-icon icon="mdi-signal" size="18" />
                Level
              </span>

              <strong>{{ course.level || 'Beginner' }}</strong>
            </div>

            <div class="summary-row">
              <span>
                <v-icon icon="mdi-clock-outline" size="18" />
                Duration
              </span>

              <strong>{{ totalDuration }}</strong>
            </div>

            <div class="summary-row">
              <span>
                <v-icon icon="mdi-play-circle-outline" size="18" />
                Lessons
              </span>

              <strong>{{ lessons.length }}</strong>
            </div>

            <div class="summary-row">
              <span>
                <v-icon icon="mdi-tag-outline" size="18" />
                Learning path
              </span>

              <strong>{{ learningPathName }}</strong>
            </div>

            <v-alert
              v-if="enrollmentError"
              type="error"
              variant="tonal"
              density="comfortable"
              class="mt-5"
            >
              {{ enrollmentError }}
            </v-alert>

            <v-btn
              v-if="checkingEnrollment"
              block
              color="#7048D9"
              size="large"
              rounded="lg"
              class="enroll-button"
              loading
              disabled
            >
              Checking Enrollment
            </v-btn>

            <v-btn
              v-else-if="!enrolled"
              block
              color="#7048D9"
              size="large"
              rounded="lg"
              class="enroll-button"
              prepend-icon="mdi-school-outline"
              :loading="enrolling"
              @click="handleEnrollment"
            >
              Enroll in Course
            </v-btn>

            <v-btn
              v-else
              block
              color="#7048D9"
              size="large"
              rounded="lg"
              class="enroll-button"
              prepend-icon="mdi-check-circle-outline"
              disabled
            >
              Enrolled — Lessons Coming Next
            </v-btn>

            <p class="summary-footnote">
              <v-icon
                icon="mdi-heart-outline"
                size="15"
              />
              Learn at your own pace
            </p>
          </aside>
        </div>

        <!-- Curriculum -->
        <section class="curriculum-section">
          <div class="curriculum-heading">
            <div>
              <div class="section-eyebrow">
                YOUR LEARNING ROADMAP
              </div>

              <h2>Course Curriculum</h2>

              <p>
                Explore the lessons that will guide your learning.
              </p>
            </div>

            <v-chip
              color="#7048D9"
              variant="tonal"
              rounded="lg"
            >
              {{ lessons.length }}
              {{ lessons.length === 1 ? 'Lesson' : 'Lessons' }}
            </v-chip>
          </div>

          <!-- Lesson Cards -->
          <v-row v-if="lessons.length">
            <v-col
              v-for="(lesson, index) in lessons"
              :key="lesson.id"
              cols="12"
              md="6"
            >
              <div class="lesson-card">
                <div class="lesson-number">
                  {{ String(index + 1).padStart(2, '0') }}
                </div>

                <div class="lesson-info">
                  <div class="lesson-label">
                    LESSON {{ index + 1 }}
                  </div>

                  <h3>{{ lesson.title }}</h3>

                  <div class="lesson-meta">
                    <span>
                      <v-icon
                        icon="mdi-clock-outline"
                        size="16"
                      />

                      {{ lesson.duration_minutes || 0 }} min
                    </span>

                    <v-chip
                      v-if="lesson.is_preview"
                      size="x-small"
                      color="#7048D9"
                      variant="tonal"
                    >
                      Preview
                    </v-chip>
                  </div>
                </div>

                <div class="lesson-status">
                  <v-icon
                    :icon="
                      enrolled
                        ? 'mdi-book-open-outline'
                        : 'mdi-lock-outline'
                    "
                    size="21"
                    color="#9B91B0"
                  />
                </div>
              </div>
            </v-col>
          </v-row>

          <!-- Empty Curriculum -->
          <div v-else class="empty-lessons">
            <v-icon
              icon="mdi-bookshelf"
              size="55"
              color="#B9A5E8"
            />

            <h3>Lessons are coming soon</h3>

            <p>
              We're preparing something great for this course.
              Check back soon!
            </p>
          </div>
        </section>

        <!-- Encouragement -->
        <section class="encouragement-banner">
          <v-icon
            icon="mdi-star-four-points"
            color="#7048D9"
            size="30"
          />

          <div>
            <h3>Every expert was once a beginner.</h3>

            <p>
              One lesson at a time, you're building skills
              that can open new doors.
            </p>
          </div>
        </section>
      </template>
    </main>

    <footer class="course-footer">
      <span>dealora<span class="footer-dot">.</span></span>

      <p>
        Learn the skills.
        <strong>Make the deal.</strong>
      </p>
    </footer>
  </div>
</template>

<style scoped>
.course-page {
  min-height: 100vh;
  background: #fcfaf8;
  color: #28213d;
  font-family: 'Inter', sans-serif;
}

.course-header {
  height: 84px;
  background: #ffffff;
  border-bottom: 1px solid #eee9f3;
  padding: 0 6%;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.brand {
  font-family: 'Poppins', sans-serif;
  font-size: 2rem;
  font-weight: 800;
  letter-spacing: -1.5px;
  color: #7048d9;
  text-decoration: none;
}

.brand span,
.footer-dot {
  color: #f472b6;
}

.course-container {
  max-width: 1240px;
  margin: 0 auto;
  padding: 42px 24px 80px;
}

.loading-state,
.error-state {
  min-height: 65vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 20px;
  text-align: center;
}

.loading-state p,
.error-state p {
  color: #827b98;
}

.error-state h2 {
  font-size: 1.5rem;
}

.course-hero {
  background: linear-gradient(
    120deg,
    #7048d9 0%,
    #9670e9 65%,
    #b38df1 100%
  );
  border-radius: 26px;
  padding: 52px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 30px;
  overflow: hidden;
  color: #ffffff;
  position: relative;
}

.hero-content {
  position: relative;
  z-index: 1;
  max-width: 750px;
}

.hero-eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: rgba(255, 255, 255, 0.16);
  border: 1px solid rgba(255, 255, 255, 0.22);
  border-radius: 30px;
  padding: 9px 16px;
  font-size: 0.8rem;
  font-weight: 700;
  margin-bottom: 23px;
}

.course-hero h1 {
  font-family: 'Poppins', sans-serif;
  font-size: clamp(2rem, 4vw, 3.1rem);
  font-weight: 800;
  line-height: 1.2;
  margin-bottom: 18px;
}

.hero-description {
  font-size: 0.97rem;
  line-height: 1.9;
  opacity: 0.92;
  max-width: 650px;
}

.hero-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 25px;
  margin-top: 30px;
}

.hero-meta span {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 0.85rem;
  font-weight: 600;
}

.hero-art {
  flex-shrink: 0;
  position: relative;
}

.hero-art-circle {
  width: 185px;
  height: 185px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.14);
  border: 2px solid rgba(255, 255, 255, 0.22);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #ffffff;
  transform: rotate(-10deg);
}

.details-grid {
  display: grid;
  grid-template-columns: minmax(0, 1.6fr) minmax(300px, 1fr);
  gap: 25px;
  margin-top: 32px;
  align-items: start;
}

.overview-card,
.summary-card {
  background: #ffffff;
  border: 1px solid #eee9f3;
  border-radius: 22px;
  padding: 34px;
}

.section-eyebrow {
  font-size: 0.7rem;
  font-weight: 800;
  letter-spacing: 1.5px;
  color: #9772db;
  margin-bottom: 12px;
}

.overview-card h2,
.curriculum-heading h2 {
  font-family: 'Poppins', sans-serif;
  font-size: 1.6rem;
  font-weight: 750;
  margin-bottom: 16px;
}

.overview-card > p {
  color: #827b98;
  font-size: 0.93rem;
  line-height: 1.9;
}

.overview-divider,
.summary-divider {
  height: 1px;
  background: #f0ecf5;
  margin: 28px 0;
}

.overview-card h3 {
  font-size: 1.05rem;
  font-weight: 700;
  margin-bottom: 20px;
}

.overview-topics {
  display: flex;
  flex-direction: column;
  gap: 17px;
}

.overview-topic {
  display: flex;
  align-items: center;
  gap: 13px;
  font-size: 0.9rem;
  color: #5e566e;
}

.empty-note {
  color: #91899e;
  font-style: italic;
}

.summary-icon {
  width: 66px;
  height: 66px;
  background: #f0eaff;
  color: #7048d9;
  border-radius: 17px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 22px;
}

.summary-card h2 {
  font-family: 'Poppins', sans-serif;
  font-size: 1.3rem;
  font-weight: 750;
  margin-bottom: 12px;
}

.summary-description {
  color: #827b98;
  font-size: 0.87rem;
  line-height: 1.8;
}

.summary-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  margin-bottom: 19px;
  font-size: 0.82rem;
}

.summary-row > span {
  display: flex;
  align-items: center;
  gap: 9px;
  color: #91899e;
}

.summary-row strong {
  font-weight: 700;
  text-align: right;
  color: #51485f;
}

.enroll-button {
  margin-top: 25px;
  font-weight: 700;
  text-transform: none;
  letter-spacing: 0;
  min-height: 50px;
}

.summary-footnote {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 7px;
  color: #a29aad;
  font-size: 0.75rem;
  margin-top: 17px;
}

.curriculum-section {
  margin-top: 55px;
}

.curriculum-heading {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 25px;
}

.curriculum-heading p {
  color: #91899e;
  font-size: 0.9rem;
}

.lesson-card {
  display: flex;
  align-items: center;
  gap: 20px;
  height: 100%;
  padding: 25px;
  background: #ffffff;
  border: 1px solid #eee9f3;
  border-radius: 18px;
  transition:
    transform 0.2s,
    box-shadow 0.2s,
    border-color 0.2s;
}

.lesson-card:hover {
  transform: translateY(-3px);
  border-color: #d8c8f6;
  box-shadow: 0 12px 30px rgba(67, 42, 109, 0.06);
}

.lesson-number {
  flex-shrink: 0;
  width: 54px;
  height: 54px;
  background: #f3edff;
  color: #7048d9;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  font-size: 1.1rem;
}

.lesson-info {
  flex: 1;
  min-width: 0;
}

.lesson-label {
  color: #a59ab6;
  font-size: 0.66rem;
  font-weight: 800;
  letter-spacing: 1px;
  margin-bottom: 7px;
}

.lesson-info h3 {
  font-size: 0.96rem;
  font-weight: 700;
  line-height: 1.5;
  margin-bottom: 9px;
}

.lesson-meta {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 12px;
}

.lesson-meta span {
  display: flex;
  align-items: center;
  gap: 5px;
  color: #91899e;
  font-size: 0.77rem;
}

.lesson-status {
  flex-shrink: 0;
}

.empty-lessons {
  background: #ffffff;
  border: 1px dashed #d9c9f0;
  border-radius: 20px;
  text-align: center;
  padding: 60px 25px;
}

.empty-lessons h3 {
  margin-top: 17px;
  font-size: 1.2rem;
}

.empty-lessons p {
  color: #91899e;
  margin-top: 10px;
  font-size: 0.9rem;
}

.encouragement-banner {
  margin-top: 50px;
  background: #f4edff;
  border: 1px solid #e8ddfb;
  border-radius: 20px;
  padding: 27px;
  display: flex;
  align-items: center;
  gap: 20px;
}

.encouragement-banner h3 {
  font-size: 1rem;
  font-weight: 700;
  margin-bottom: 7px;
}

.encouragement-banner p {
  color: #827b98;
  font-size: 0.84rem;
  line-height: 1.6;
}

.course-footer {
  background: #ffffff;
  border-top: 1px solid #eee9f3;
  padding: 30px 6%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 20px;
}

.course-footer > span {
  color: #7048d9;
  font-family: 'Poppins', sans-serif;
  font-size: 1.5rem;
  font-weight: 800;
}

.course-footer p {
  font-size: 0.8rem;
  color: #91899e;
}

.course-footer strong {
  color: #7048d9;
}

@media (max-width: 900px) {
  .details-grid {
    grid-template-columns: 1fr;
  }

  .hero-art {
    display: none;
  }
}

@media (max-width: 600px) {
  .course-header {
    padding: 0 18px;
  }

  .brand {
    font-size: 1.6rem;
  }

  .course-container {
    padding: 22px 15px 50px;
  }

  .course-hero {
    padding: 30px 24px;
    border-radius: 20px;
  }

  .hero-meta {
    gap: 15px;
  }

  .overview-card,
  .summary-card {
    padding: 25px;
  }

  .curriculum-heading {
    align-items: flex-start;
    flex-direction: column;
  }

  .lesson-card {
    padding: 19px;
    gap: 13px;
  }

  .lesson-number {
    width: 43px;
    height: 43px;
    font-size: 0.95rem;
  }

  .course-footer {
    flex-direction: column;
    text-align: center;
  }
}
</style>
