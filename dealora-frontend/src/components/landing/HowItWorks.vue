
<script setup>
const steps = [
  {
    number: '01',
    title: 'Learn the Basics',
    description:
      'Start with bite-sized lessons that explain business concepts in simple, beginner-friendly language.',
    icon: 'mdi-book-open-page-variant-outline',
    color: '#EEE6FF',
    iconColor: '#7048D9',
  },
  {
    number: '02',
    title: 'Practise with AI',
    description:
      'Apply what you learn through realistic customer conversations, negotiations, and interactive challenges.',
    icon: 'mdi-robot-happy-outline',
    color: '#FCE7F3',
    iconColor: '#D94689',
  },
  {
    number: '03',
    title: 'Build Real Skills',
    description:
      'Receive feedback, track your progress, and gain the confidence to handle real business situations.',
    icon: 'mdi-trophy-outline',
    color: '#FFF3D6',
    iconColor: '#B8860B',
  },
]
</script>

<template>
  <section id="how-it-works" class="how-section">
    <v-container>
      <div class="section-heading text-center">
        <span class="eyebrow">SIMPLE. PRACTICAL. EFFECTIVE.</span>

        <h2>Learning business shouldn't be complicated.</h2>

        <p>
          Dealora takes you from complete beginner to
          confident learner, one practical step at a time.
        </p>
      </div>

      <v-row class="mt-10">
        <v-col
          v-for="step in steps"
          :key="step.number"
          cols="12"
          md="4"
        >
          <div class="step-card">
            <div class="step-number">
              {{ step.number }}
            </div>

            <div
              class="step-icon"
              :style="{ background: step.color }"
            >
              <v-icon
                :icon="step.icon"
                :color="step.iconColor"
                size="38"
              />
            </div>

            <h3>{{ step.title }}</h3>

            <p>{{ step.description }}</p>
          </div>
        </v-col>
      </v-row>
    </v-container>
  </section>
</template>

<style scoped>
.how-section {
  padding: 110px 0;
  background: #ffffff;
  scroll-margin-top: 84px;
}

.section-heading {
  max-width: 750px;
  margin: auto;
}

.eyebrow {
  color: #7048d9;
  font-weight: 700;
  font-size: 0.8rem;
  letter-spacing: 2px;
}

.section-heading h2 {
  margin-top: 18px;
  font-size: clamp(2rem, 4vw, 3rem);
  color: #252139;
  font-weight: 800;
  line-height: 1.3;
}

.section-heading p {
  margin-top: 20px;
  color: #716a86;
  line-height: 1.9;
}

.step-card {
  position: relative;
  padding: 40px 30px;
  text-align: center;
  border: 1px solid #eee8f5;
  border-radius: 24px;
  height: 100%;
  transition: all 0.3s ease;
}

.step-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 45px rgba(91, 55, 150, 0.1);
}

.step-number {
  position: absolute;
  top: 22px;
  right: 25px;
  font-size: 2rem;
  font-weight: 800;
  color: #eee6ff;
}

.step-icon {
  width: 85px;
  height: 85px;
  border-radius: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 10px auto 28px;
}

.step-card h3 {
  font-size: 1.3rem;
  font-weight: 700;
  color: #252139;
}

.step-card p {
  margin-top: 16px;
  color: #716a86;
  line-height: 1.8;
  font-size: 0.92rem;
}

@media (max-width: 600px) {
  .how-section {
    padding: 70px 0;
  }
}
</style>
