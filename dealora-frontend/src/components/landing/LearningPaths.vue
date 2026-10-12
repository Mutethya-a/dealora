
<script setup>
const courses = [
  {
    title: 'Sales Foundations',
    description:
      'Learn how to understand customers, communicate value, and confidently close your first sale.',
    icon: 'mdi-chart-line',
    color: '#F1E7FF',
    iconColor: '#7048D9',
    lessons: 8,
    duration: '2 hours',
  },
  {
    title: 'Negotiation Skills',
    description:
      'Discover how to bargain, handle objections, and create deals that work for everyone.',
    icon: 'mdi-handshake-outline',
    color: '#FCE7F3',
    iconColor: '#D94689',
    lessons: 10,
    duration: '3 hours',
  },
  {
    title: 'Procurement Essentials',
    description:
      'Understand supplier selection, quotations, purchasing, and how to make smarter buying decisions.',
    icon: 'mdi-package-variant-closed',
    color: '#E2F7EE',
    iconColor: '#16875B',
    lessons: 9,
    duration: '3 hours',
  },
  {
    title: 'Importation Basics',
    description:
      'Learn international sourcing, shipping methods, customs, and how to calculate import costs.',
    icon: 'mdi-ferry',
    color: '#E4F1FF',
    iconColor: '#3177C5',
    lessons: 12,
    duration: '4 hours',
  },
]
</script>

<template>
  <section id="learning-paths" class="learning-section">
    <v-container>
      <div class="section-heading text-center">
        <span class="eyebrow">EXPLORE OUR COURSES</span>

        <h2>Your journey starts here.</h2>

        <p>
          No experience? No problem. Start with the basics
          and build practical business skills at your own pace.
        </p>
      </div>

      <v-row class="mt-8">
        <v-col
          v-for="course in courses"
          :key="course.title"
          cols="12"
          sm="6"
          lg="3"
        >
          <v-card
            class="course-card pa-6"
            rounded="xl"
            elevation="0"
          >
            <div
              class="course-icon mb-6"
              :style="{ backgroundColor: course.color }"
            >
              <v-icon
                :icon="course.icon"
                :color="course.iconColor"
                size="32"
              />
            </div>

            <v-chip
              size="small"
              color="primary"
              variant="tonal"
              class="mb-4"
            >
              Beginner Friendly
            </v-chip>

            <h3>{{ course.title }}</h3>

            <p class="course-description mt-3">
              {{ course.description }}
            </p>

            <div class="course-meta mt-6">
              <span>
                <v-icon icon="mdi-book-open-outline" size="17" />
                {{ course.lessons }} lessons
              </span>

              <span>
                <v-icon icon="mdi-clock-outline" size="17" />
                {{ course.duration }}
              </span>
            </div>

            <v-divider class="my-5" />

            <v-btn
              color="primary"
              variant="text"
              class="px-0"
              append-icon="mdi-arrow-right"
              href="#join"
            >
              Explore Course
            </v-btn>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </section>
</template>

<style scoped>
.learning-section {
  padding: 100px 0;
  background: #fff8f4;
  scroll-margin-top: 84px;
}

.section-heading {
  max-width: 650px;
  margin: 0 auto;
}

.eyebrow {
  color: #7048d9;
  font-size: 0.8rem;
  font-weight: 700;
  letter-spacing: 2px;
}

.section-heading h2 {
  margin-top: 15px;
  color: #252139;
  font-size: clamp(2rem, 4vw, 3rem);
  font-weight: 800;
}

.section-heading p {
  margin-top: 16px;
  color: #716a86;
  line-height: 1.8;
}

.course-card {
  height: 100%;
  background: #ffffff;
  border: 1px solid #eee8f5;
  transition:
    transform 0.3s ease,
    box-shadow 0.3s ease;
}

.course-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 18px 40px rgba(91, 55, 150, 0.1) !important;
}

.course-icon {
  width: 65px;
  height: 65px;
  border-radius: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.course-card h3 {
  font-size: 1.2rem;
  font-weight: 700;
  color: #252139;
}

.course-description {
  color: #716a86;
  font-size: 0.9rem;
  line-height: 1.8;
  min-height: 115px;
}

.course-meta {
  display: flex;
  justify-content: space-between;
  gap: 8px;
  flex-wrap: wrap;
  color: #716a86;
  font-size: 0.78rem;
}

.course-meta span {
  display: flex;
  align-items: center;
  gap: 5px;
}

@media (max-width: 600px) {
  .learning-section {
    padding: 65px 0;
  }

  .course-description {
    min-height: auto;
  }
}
</style>
