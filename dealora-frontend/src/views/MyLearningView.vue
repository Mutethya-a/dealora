<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import learningService from '../services/learningService'

const router = useRouter()
const authStore = useAuthStore()

const loading = ref(true)
const error = ref('')
const courses = ref([])

const summary = ref({
  enrolled_courses: 0,
  in_progress: 0,
  completed: 0,
  not_started: 0,
})

const selectedFilter = ref('all')

const filters = [
  { title: 'All Courses', value: 'all' },
  { title: 'In Progress', value: 'in_progress' },
  { title: 'Completed', value: 'completed' },
  { title: 'Not Started', value: 'not_started' },
]

const filteredCourses = computed(() => {
  if (selectedFilter.value === 'all') {
    return courses.value
  }

  return courses.value.filter(
    (course) => course.status === selectedFilter.value
  )
})

async function fetchMyLearning() {
  loading.value = true
  error.value = ''

  try {
    const response = await learningService.getMyLearning(
      authStore.token
    )

    courses.value = response.courses || []
    summary.value = response.summary || summary.value
  } catch (err) {
    console.error('Failed to load learning dashboard:', err)
    error.value = 'Unable to load your courses. Please try again.'
  } finally {
    loading.value = false
  }
}

function openCourse(course) {
  router.push(`/courses/${course.slug}`)
}

function continueCourse(course) {
  if (course.next_lesson) {
    router.push(
      `/courses/${course.slug}/lessons/${course.next_lesson.slug}`
    )
  } else {
    openCourse(course)
  }
}

function statusLabel(status) {
  if (status === 'completed') return 'Completed'
  if (status === 'in_progress') return 'In Progress'
  return 'Not Started'
}

onMounted(fetchMyLearning)
</script>

<template>
  <div class="learning-page">
    <header class="learning-header">
      <RouterLink to="/" class="brand">
        dealora<span>.</span>
      </RouterLink>

      <v-btn
        variant="text"
        prepend-icon="mdi-arrow-left"
        color="#7048D9"
        @click="router.push('/dashboard')"
      >
        Dashboard
      </v-btn>
    </header>

    <main class="learning-container">
      <section class="learning-hero">
        <div>
          <div class="eyebrow">YOUR PERSONAL LEARNING SPACE</div>
          <h1>My Learning<span>.</span></h1>
          <p>
            Every lesson brings you closer to mastering the deal.
            Pick up right where you left off.
          </p>
        </div>

        <div class="hero-icon">
          <v-icon icon="mdi-school-outline" size="75" />
        </div>
      </section>

      <div v-if="loading" class="center-state">
        <v-progress-circular
          indeterminate
          color="#7048D9"
          size="50"
        />
        <p>Loading your learning journey...</p>
      </div>

      <div v-else-if="error" class="center-state">
        <v-icon
          icon="mdi-alert-circle-outline"
          color="#E879A7"
          size="55"
        />
        <p>{{ error }}</p>

        <v-btn color="#7048D9" @click="fetchMyLearning">
          Try Again
        </v-btn>
      </div>

      <template v-else>
        <section class="stats-grid">
          <div class="stat-card">
            <div class="stat-icon purple">
              <v-icon icon="mdi-bookshelf" />
            </div>
            <strong>{{ summary.enrolled_courses }}</strong>
            <span>Enrolled Courses</span>
          </div>

          <div class="stat-card">
            <div class="stat-icon pink">
              <v-icon icon="mdi-progress-clock" />
            </div>
            <strong>{{ summary.in_progress }}</strong>
            <span>In Progress</span>
          </div>

          <div class="stat-card">
            <div class="stat-icon green">
              <v-icon icon="mdi-check-circle-outline" />
            </div>
            <strong>{{ summary.completed }}</strong>
            <span>Completed</span>
          </div>
        </section>

        <section class="courses-section">
          <div class="section-heading">
            <div>
              <div class="eyebrow">KEEP GROWING</div>
              <h2>Your Courses</h2>
              <p>
                All your learning, organized in one place.
              </p>
            </div>

            <v-btn
              color="#7048D9"
              variant="outlined"
              prepend-icon="mdi-compass-outline"
              rounded="lg"
              @click="router.push('/dashboard')"
            >
              Explore Courses
            </v-btn>
          </div>

          <div class="filters">
            <button
              v-for="filter in filters"
              :key="filter.value"
              class="filter-button"
              :class="{
                active: selectedFilter === filter.value
              }"
              @click="selectedFilter = filter.value"
            >
              {{ filter.title }}
            </button>
          </div>

          <div
            v-if="filteredCourses.length"
            class="course-grid"
          >
            <article
              v-for="course in filteredCourses"
              :key="course.id"
              class="course-card"
            >
              <div class="course-top">
                <div class="course-icon">
                  <v-icon
                    :icon="
                      course.learning_path?.icon ||
                      'mdi-school-outline'
                    "
                    size="30"
                  />
                </div>

                <v-chip
                  size="small"
                  variant="tonal"
                  :color="
                    course.status === 'completed'
                      ? 'success'
                      : '#7048D9'
                  "
                >
                  {{ statusLabel(course.status) }}
                </v-chip>
              </div>

              <div class="path-label">
                {{
                  course.learning_path?.title ||
                  'Learning Path'
                }}
              </div>

              <h3>{{ course.title }}</h3>

              <p class="course-description">
                {{ course.description }}
              </p>

              <div class="course-meta">
                <span>
                  <v-icon
                    icon="mdi-book-open-outline"
                    size="17"
                  />
                  {{ course.total_lessons }} lessons
                </span>

                <span>
                  <v-icon
                    icon="mdi-signal"
                    size="17"
                  />
                  {{ course.level || 'Beginner' }}
                </span>
              </div>

              <div class="progress-section">
                <div class="progress-heading">
                  <span>
                    {{ course.completed_lessons }}
                    of {{ course.total_lessons }}
                    lessons
                  </span>

                  <strong>
                    {{ course.progress_percentage }}%
                  </strong>
                </div>

                <v-progress-linear
                  :model-value="course.progress_percentage"
                  color="#7048D9"
                  bg-color="#E8DFF7"
                  height="9"
                  rounded
                />
              </div>

              <v-btn
                block
                color="#7048D9"
                rounded="lg"
                size="large"
                class="continue-button"
                :prepend-icon="
                  course.status === 'completed'
                    ? 'mdi-check-circle-outline'
                    : 'mdi-play-circle-outline'
                "
                @click="continueCourse(course)"
              >
                {{
                  course.status === 'completed'
                    ? 'Review Course'
                    : course.status === 'not_started'
                      ? 'Start Learning'
                      : 'Continue Learning'
                }}
              </v-btn>
            </article>
          </div>

          <div v-else class="empty-state">
            <v-icon
              icon="mdi-book-open-page-variant-outline"
              size="65"
              color="#B9A5E8"
            />

            <h3>
              {{
                selectedFilter === 'all'
                  ? 'Your learning journey starts here'
                  : 'No courses in this category'
              }}
            </h3>

            <p>
              {{
                selectedFilter === 'all'
                  ? 'Explore learning paths and enroll in your first course.'
                  : 'Try another filter to see your courses.'
              }}
            </p>

            <v-btn
              v-if="selectedFilter === 'all'"
              color="#7048D9"
              rounded="lg"
              @click="router.push('/dashboard')"
            >
              Explore Courses
            </v-btn>
          </div>
        </section>
      </template>
    </main>

    <footer class="learning-footer">
      <strong>dealora<span>.</span></strong>
      <p>Learn the skills. Make the deal.</p>
    </footer>
  </div>
</template>

<style scoped>
.learning-page {
  min-height: 100vh;
  background: #fcfaf8;
  color: #28213d;
  font-family: 'Inter', sans-serif;
}

.learning-header {
  height: 84px;
  background: white;
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
.learning-footer span,
.learning-hero h1 span {
  color: #f472b6;
}

.learning-container {
  max-width: 1240px;
  margin: 0 auto;
  padding: 42px 24px 90px;
}

.learning-hero {
  background: linear-gradient(120deg, #7048d9, #a27cf0);
  border-radius: 26px;
  padding: 48px;
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 25px;
}

.learning-hero .eyebrow {
  color: #eee1ff;
}

.eyebrow {
  font-size: 0.72rem;
  font-weight: 800;
  letter-spacing: 1.5px;
  color: #9772db;
  margin-bottom: 12px;
}

.learning-hero h1 {
  font-family: 'Poppins', sans-serif;
  font-size: clamp(2rem, 4vw, 3rem);
  font-weight: 800;
}

.learning-hero p {
  margin-top: 12px;
  max-width: 560px;
  line-height: 1.8;
  opacity: 0.92;
}

.hero-icon {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.15);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.center-state {
  min-height: 350px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 18px;
  color: #827b98;
  text-align: center;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 22px;
  margin-top: 32px;
}

.stat-card {
  background: white;
  border: 1px solid #eee9f3;
  border-radius: 20px;
  padding: 25px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.stat-icon {
  width: 46px;
  height: 46px;
  border-radius: 13px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.purple {
  background: #f0eaff;
  color: #7048d9;
}

.pink {
  background: #fff0f7;
  color: #e879a7;
}

.green {
  background: #e9f8ef;
  color: #2e9b69;
}

.stat-card strong {
  font-size: 2rem;
  font-weight: 800;
}

.stat-card span {
  font-size: 0.85rem;
  color: #827b98;
}

.courses-section {
  margin-top: 55px;
}

.section-heading {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 20px;
  margin-bottom: 25px;
}

.section-heading h2 {
  font-family: 'Poppins', sans-serif;
  font-size: 1.7rem;
}

.section-heading p {
  color: #91899e;
  font-size: 0.9rem;
  margin-top: 8px;
}

.filters {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  margin-bottom: 25px;
}

.filter-button {
  background: white;
  border: 1px solid #e9e1f3;
  border-radius: 12px;
  padding: 11px 18px;
  color: #827b98;
  font-size: 0.83rem;
  font-weight: 700;
  cursor: pointer;
}

.filter-button.active {
  background: #7048d9;
  border-color: #7048d9;
  color: white;
}

.course-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 24px;
}

.course-card {
  background: white;
  border: 1px solid #eee9f3;
  border-radius: 22px;
  padding: 28px;
  display: flex;
  flex-direction: column;
}

.course-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 22px;
}

.course-icon {
  width: 58px;
  height: 58px;
  border-radius: 16px;
  background: #f0eaff;
  color: #7048d9;
  display: flex;
  align-items: center;
  justify-content: center;
}

.path-label {
  color: #9772db;
  font-size: 0.72rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-bottom: 10px;
}

.course-card h3 {
  font-family: 'Poppins', sans-serif;
  font-size: 1.2rem;
  margin-bottom: 12px;
}

.course-description {
  color: #827b98;
  font-size: 0.87rem;
  line-height: 1.8;
  margin-bottom: 20px;
  flex: 1;
}

.course-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  color: #91899e;
  font-size: 0.8rem;
  margin-bottom: 24px;
}

.course-meta span {
  display: flex;
  align-items: center;
  gap: 6px;
}

.progress-section {
  border-top: 1px solid #f0ecf5;
  padding-top: 20px;
}

.progress-heading {
  display: flex;
  justify-content: space-between;
  margin-bottom: 12px;
  font-size: 0.82rem;
  color: #827b98;
}

.progress-heading strong {
  color: #7048d9;
  font-weight: 800;
}

.continue-button {
  margin-top: 24px;
  text-transform: none;
  font-weight: 700;
  letter-spacing: 0;
  min-height: 48px;
}

.empty-state {
  padding: 75px 25px;
  text-align: center;
  border: 1px dashed #d9c9f0;
  border-radius: 22px;
  background: white;
}

.empty-state h3 {
  margin-top: 20px;
  font-size: 1.2rem;
}

.empty-state p {
  margin: 12px auto 22px;
  color: #91899e;
  max-width: 400px;
}

.learning-footer {
  background: white;
  border-top: 1px solid #eee9f3;
  padding: 30px 6%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
}

.learning-footer strong {
  font-family: 'Poppins', sans-serif;
  font-size: 1.5rem;
  color: #7048d9;
}

.learning-footer p {
  color: #91899e;
  font-size: 0.8rem;
}

@media (max-width: 900px) {
  .course-grid {
    grid-template-columns: 1fr;
  }

  .hero-icon {
    display: none;
  }
}

@media (max-width: 600px) {
  .learning-header {
    padding: 0 18px;
  }

  .learning-container {
    padding: 22px 15px 50px;
  }

  .learning-hero {
    padding: 30px 24px;
  }

  .stats-grid {
    grid-template-columns: 1fr;
  }

  .section-heading {
    flex-direction: column;
    align-items: flex-start;
  }

  .learning-footer {
    flex-direction: column;
    text-align: center;
  }
}
</style>
