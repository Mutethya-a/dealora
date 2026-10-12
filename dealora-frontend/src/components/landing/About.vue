
<script setup>
const benefits = [
  {
    icon: 'mdi-school-outline',
    title: 'Made for Beginners',
    description: 'No business knowledge or experience required.',
  },
  {
    icon: 'mdi-robot-outline',
    title: 'Learn by Doing',
    description: 'Practise business decisions through interactive challenges.',
  },
  {
    icon: 'mdi-chart-line',
    title: 'Grow at Your Pace',
    description: 'Build skills progressively and track your learning journey.',
  },
]
</script>

<template>
  <section id="about" class="about-section">
    <v-container>
      <v-row align="center">
        <v-col cols="12" md="6">
          <v-chip color="primary" variant="tonal" class="mb-6">
            ABOUT DEALORA
          </v-chip>

          <h2 class="about-title">
            Business skills are for
            <span>everyone.</span>
          </h2>

          <p class="about-description mt-6">
            We believe everyone deserves the opportunity
            to learn practical business skills, regardless
            of their background or experience.
          </p>

          <p class="about-description mt-4">
            Dealora makes sales, negotiation, procurement,
            and importation easier to understand through
            structured lessons, interactive practice,
            and realistic business scenarios.
          </p>

          <v-btn
            color="primary"
            rounded="pill"
            size="large"
            class="mt-8"
            append-icon="mdi-arrow-right"
            href="#join"
          >
            Start Your Journey
          </v-btn>
        </v-col>

        <v-col cols="12" md="6">
          <div class="benefits-container">
            <div
              v-for="benefit in benefits"
              :key="benefit.title"
              class="benefit-card"
            >
              <div class="benefit-icon">
                <v-icon
                  :icon="benefit.icon"
                  color="primary"
                  size="30"
                />
              </div>

              <div>
                <h3>{{ benefit.title }}</h3>
                <p>{{ benefit.description }}</p>
              </div>
            </div>
          </div>
        </v-col>
      </v-row>
    </v-container>
  </section>
</template>

<style scoped>
.about-section {
  padding: 110px 0;
  background: #fff8f4;
  scroll-margin-top: 84px;
}

.about-title {
  font-size: clamp(2.2rem, 4vw, 3.3rem);
  font-weight: 800;
  line-height: 1.3;
  color: #252139;
}

.about-title span {
  color: #7048d9;
}

.about-description {
  color: #716a86;
  font-size: 1rem;
  line-height: 1.9;
  max-width: 520px;
}

.benefits-container {
  display: flex;
  flex-direction: column;
  gap: 22px;
  padding: 25px;
}

.benefit-card {
  display: flex;
  align-items: center;
  gap: 20px;
  background: #ffffff;
  border: 1px solid #eee8f5;
  border-radius: 20px;
  padding: 25px;
  transition: all 0.3s ease;
}

.benefit-card:hover {
  transform: translateX(8px);
  box-shadow: 0 15px 35px rgba(91, 55, 150, 0.1);
}

.benefit-icon {
  width: 65px;
  height: 65px;
  flex-shrink: 0;
  background: #f1e7ff;
  border-radius: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.benefit-card h3 {
  color: #252139;
  font-size: 1.05rem;
  font-weight: 700;
}

.benefit-card p {
  color: #827b98;
  font-size: 0.88rem;
  margin-top: 8px;
  line-height: 1.6;
}

@media (max-width: 600px) {
  .about-section {
    padding: 70px 0;
  }

  .benefits-container {
    padding: 20px 0;
  }
}
</style>
