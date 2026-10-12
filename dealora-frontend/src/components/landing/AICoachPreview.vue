
<script setup>
import { ref, computed } from 'vue'

const selectedAnswer = ref(null)

const answers = [
  {
    id: 1,
    text: 'Immediately offer a large discount.',
    feedback:
      'Discounting too quickly can reduce your profit. First, understand why the customer feels the price is high.',
    correct: false,
  },
  {
    id: 2,
    text: 'Ask what they are comparing it with and explain your value.',
    feedback:
      'Excellent! You are discovering the customer’s concern before responding. This is an important sales skill.',
    correct: true,
  },
  {
    id: 3,
    text: 'Tell them your price is final and end the conversation.',
    feedback:
      'Being firm is sometimes necessary, but ending the conversation immediately may lose a potential customer.',
    correct: false,
  },
]

const feedback = computed(() =>
  answers.find(answer => answer.id === selectedAnswer.value)
)

function selectAnswer(id) {
  selectedAnswer.value = id
}

function resetScenario() {
  selectedAnswer.value = null
}
</script>

<template>
  <section class="coach-section">
    <v-container>
      <v-row align="center" class="ga-md-0">
        <v-col cols="12" md="5">
          <v-chip color="primary" variant="tonal" class="mb-6">
            <v-icon start icon="mdi-sparkles" />
            PRACTISE WITH DEALORA AI
          </v-chip>

          <h2 class="coach-heading">
            Practise your pitch.
            <span>Perfect your skills.</span>
          </h2>

          <p class="coach-description mt-6">
            Learning doesn't stop after a lesson.
            Practise handling customers, objections,
            and negotiations in realistic scenarios.
          </p>

          <div class="d-flex align-center ga-3 mt-8">
            <v-icon
              icon="mdi-check-circle"
              color="success"
            />
            <span>Safe environment to make mistakes</span>
          </div>

          <div class="d-flex align-center ga-3 mt-4">
            <v-icon
              icon="mdi-check-circle"
              color="success"
            />
            <span>Learn from actionable feedback</span>
          </div>

          <div class="d-flex align-center ga-3 mt-4">
            <v-icon
              icon="mdi-check-circle"
              color="success"
            />
            <span>Build confidence through practice</span>
          </div>
        </v-col>

        <v-col cols="12" md="7">
          <v-card class="coach-card pa-6 pa-md-8" rounded="xl" elevation="0">
            <div class="d-flex align-center ga-3 mb-6">
              <v-avatar color="primary" size="52">
                <v-icon icon="mdi-robot-outline" color="white" />
              </v-avatar>

              <div>
                <h3>Dealora AI Coach</h3>
                <p class="coach-subtitle">Interactive sales practice demo</p>
              </div>

              <v-spacer />

              <v-chip color="success" size="small" variant="tonal">
                Demo
              </v-chip>
            </div>

            <v-divider class="mb-6" />

            <div class="customer-message">
              <span class="message-label">CUSTOMER</span>

              <p>
                "Your product is too expensive.
                Why should I buy from you?"
              </p>
            </div>

            <h4 class="mt-7 mb-4">
              How would you respond?
            </h4>

            <div
              v-for="answer in answers"
              :key="answer.id"
              class="answer-option"
              :class="{ selected: selectedAnswer === answer.id }"
              role="button"
              tabindex="0"
              @click="selectAnswer(answer.id)"
              @keydown.enter="selectAnswer(answer.id)"
              @keydown.space.prevent="selectAnswer(answer.id)"
            >
              <v-icon
                :icon="selectedAnswer === answer.id
                  ? 'mdi-radiobox-marked'
                  : 'mdi-radiobox-blank'"
                :color="selectedAnswer === answer.id
                  ? 'primary'
                  : 'grey'"
              />

              <span>{{ answer.text }}</span>
            </div>

            <v-expand-transition>
              <div v-if="feedback" class="feedback-box mt-6">
                <div class="d-flex align-center ga-2 mb-2">
                  <v-icon
                    :icon="feedback.correct
                      ? 'mdi-check-circle'
                      : 'mdi-lightbulb-outline'"
                    :color="feedback.correct
                      ? 'success'
                      : 'primary'"
                  />

                  <strong>Coach Feedback</strong>
                </div>

                <p>{{ feedback.feedback }}</p>

                <v-btn
                  color="primary"
                  variant="text"
                  class="mt-3 px-0"
                  prepend-icon="mdi-refresh"
                  @click="resetScenario"
                >
                  Try Again
                </v-btn>
              </div>
            </v-expand-transition>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </section>
</template>

<style scoped>
.coach-section {
  padding: 110px 0;
  background: linear-gradient(
    135deg,
    #f6efff 0%,
    #fff8f4 100%
  );
}

.coach-heading {
  font-size: clamp(2rem, 4vw, 3rem);
  font-weight: 800;
  line-height: 1.3;
  color: #252139;
}

.coach-heading span {
  display: block;
  color: #7048d9;
}

.coach-description {
  color: #716a86;
  line-height: 1.9;
  max-width: 470px;
}

.coach-card {
  background: white;
  border: 1px solid #eee6fa;
  box-shadow: 0 24px 60px rgba(91, 55, 150, 0.1) !important;
}

.coach-card h3 {
  color: #252139;
  font-size: 1.1rem;
}

.coach-subtitle {
  font-size: 0.78rem;
  color: #827b98;
}

.customer-message {
  background: #f5f0ff;
  border-radius: 18px;
  padding: 24px;
}

.message-label {
  color: #7048d9;
  font-size: 0.7rem;
  font-weight: 800;
  letter-spacing: 1px;
}

.customer-message p {
  color: #252139;
  font-size: 1rem;
  line-height: 1.8;
  margin-top: 10px;
}

.coach-card h4 {
  color: #252139;
}

.answer-option {
  display: flex;
  align-items: center;
  gap: 12px;
  border: 1px solid #e8e0f4;
  border-radius: 14px;
  padding: 16px;
  margin-bottom: 12px;
  cursor: pointer;
  transition: all 0.2s ease;
  color: #51496b;
  font-size: 0.9rem;
}

.answer-option:hover {
  border-color: #a78bfa;
  background: #faf7ff;
}

.answer-option.selected {
  border-color: #7048d9;
  background: #f5efff;
}

.answer-option:focus-visible {
  outline: 3px solid #a78bfa;
  outline-offset: 2px;
}

.feedback-box {
  background: #f5f0ff;
  border-radius: 16px;
  padding: 20px;
  color: #51496b;
}

.feedback-box strong {
  color: #252139;
}

.feedback-box p {
  font-size: 0.9rem;
  line-height: 1.8;
}

@media (max-width: 600px) {
  .coach-section {
    padding: 70px 0;
  }
}
</style>
