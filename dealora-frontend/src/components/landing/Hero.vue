
<template>
  <section id="home" class="hero-section">
    <v-container>
      <v-row align="center" class="hero-row">
        <v-col cols="12" md="6">
          <v-chip
            color="primary"
            variant="tonal"
            class="mb-6"
          >
            <v-icon
              start
              icon="mdi-sparkles"
            />
            Your business journey starts here
          </v-chip>

          <h1 class="hero-title">
            Learn business.
            <br />
            Build confidence.
            <br />
            <span>Make the deal.</span>
          </h1>

          <p class="hero-description mt-6">
            Master sales, negotiation, procurement,
            and importation from scratch through
            interactive lessons, AI-powered practice,
            and real-world business simulations.
          </p>

          <div class="d-flex flex-wrap ga-4 mt-8">
            <v-btn
              color="primary"
              size="x-large"
              rounded="pill"
              elevation="3"
              append-icon="mdi-arrow-right"
              href="#learning-paths"
            >
              Start Learning
            </v-btn>

            <v-btn
              color="primary"
              variant="outlined"
              size="x-large"
              rounded="pill"
              prepend-icon="mdi-play-circle-outline"
              href="#how-it-works"
            >
              How It Works
            </v-btn>
          </div>

          <div class="d-flex flex-wrap ga-6 mt-10">
            <div class="hero-benefit">
              <v-icon color="success" icon="mdi-check-circle" />
              Beginner Friendly
            </div>

            <div class="hero-benefit">
              <v-icon color="success" icon="mdi-check-circle" />
              Interactive Learning
            </div>
          </div>
        </v-col>

        <v-col cols="12" md="6">
          <div class="hero-visual">
            <div class="visual-card">
              <div class="d-flex align-center ga-3 mb-6">
                <v-avatar color="primary" size="52">
                  <v-icon
                    icon="mdi-school-outline"
                    color="white"
                    size="28"
                  />
                </v-avatar>

                <div>
                  <h3>Your Learning Journey</h3>
                  <p>From beginner to dealmaker</p>
                </div>
              </div>

              <div
                v-for="(course, index) in courses"
                :key="course.title"
                class="course-item"
              >
                <v-avatar
                  :color="course.color"
                  variant="tonal"
                  rounded="lg"
                  size="48"
                >
                  <v-icon :icon="course.icon" />
                </v-avatar>

                <div class="course-details">
                  <strong>{{ course.title }}</strong>
                  <span>{{ course.description }}</span>
                </div>

                <v-icon
                  :icon="index === 0
                    ? 'mdi-arrow-right-circle'
                    : 'mdi-lock-outline'"
                  :color="index === 0 ? 'primary' : 'grey'"
                />
              </div>

              <div class="progress-box mt-5">
                <div class="d-flex justify-space-between mb-2">
                  <strong>Start your journey</strong>
                  <span>Level 1</span>
                </div>

                <v-progress-linear
                  color="primary"
                  bg-color="#E5D9FA"
                  :model-value="10"
                  rounded
                  height="9"
                />
              </div>
            </div>

            <div class="floating-badge">
              <v-icon
                icon="mdi-star-four-points"
                color="#F4C95D"
              />
              Learn • Practise • Grow
            </div>
          </div>
        </v-col>
      </v-row>
    </v-container>
  </section>
</template>

<script setup>
const courses = [
  {
    title: 'Sales Foundations',
    description: 'Learn how to sell with confidence',
    icon: 'mdi-chart-line',
    color: 'purple',
  },
  {
    title: 'Negotiation Skills',
    description: 'Learn the art of making deals',
    icon: 'mdi-handshake-outline',
    color: 'pink',
  },
  {
    title: 'Procurement Essentials',
    description: 'Understand sourcing and purchasing',
    icon: 'mdi-package-variant-closed',
    color: 'deep-purple',
  },
  {
    title: 'Importation Basics',
    description: 'Discover international trade',
    icon: 'mdi-ferry',
    color: 'blue',
  },
]
</script>

<style scoped>
.hero-section {
  background: linear-gradient(
    135deg,
    #fff8f4 0%,
    #f3ebff 55%,
    #fce7f3 100%
  );
  min-height: calc(100vh - 84px);
  overflow: hidden;
}

.hero-row {
  min-height: calc(100vh - 84px);
  padding-top: 60px;
  padding-bottom: 80px;
}

.hero-title {
  font-size: clamp(2.5rem, 5vw, 4.5rem);
  line-height: 1.2;
  font-weight: 800;
  letter-spacing: -2px;
  color: #1f1b3a;
}

.hero-title span {
  color: #6c3eb8;
}

.hero-description {
  font-size: 1.08rem;
  line-height: 1.9;
  color: #716a86;
  max-width: 550px;
}

.hero-benefit {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #51496b;
  font-size: 0.9rem;
  font-weight: 500;
}

.hero-visual {
  position: relative;
  padding: 30px;
}

.visual-card {
  background: #ffffff;
  border-radius: 28px;
  padding: 30px;
  box-shadow: 0 24px 65px rgba(91, 55, 150, 0.12);
  transform: rotate(2deg);
}

.visual-card h3 {
  font-size: 1.2rem;
  color: #1f1b3a;
}

.visual-card p {
  font-size: 0.85rem;
  color: #827b98;
}

.course-item {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 14px 0;
  border-bottom: 1px solid #f1edf7;
}

.course-details {
  display: flex;
  flex-direction: column;
  flex: 1;
  gap: 4px;
}

.course-details strong {
  font-size: 0.9rem;
  color: #28213e;
}

.course-details span {
  font-size: 0.76rem;
  color: #8b849c;
}

.progress-box {
  background: #f7f1ff;
  padding: 20px;
  border-radius: 16px;
  font-size: 0.85rem;
  color: #6c3eb8;
}

.floating-badge {
  position: absolute;
  right: 0;
  bottom: 0;
  background: white;
  border-radius: 50px;
  padding: 16px 22px;
  box-shadow: 0 10px 35px rgba(91, 55, 150, 0.14);
  font-size: 0.85rem;
  font-weight: 700;
  color: #6c3eb8;
  display: flex;
  align-items: center;
  gap: 8px;
}

@media (max-width: 960px) {
  .hero-row {
    padding-top: 35px;
  }

  .hero-visual {
    padding: 20px 10px 35px;
  }

  .visual-card {
    transform: none;
    padding: 20px;
  }

  .floating-badge {
    right: 8px;
  }
}
</style>
