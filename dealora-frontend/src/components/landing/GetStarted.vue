
<template>
  <section id="join" class="cta-section">
    <v-container>
      <div class="cta-card">
        <div class="floating-shape shape-one"></div>
        <div class="floating-shape shape-two"></div>

        <div class="cta-content">
          <v-icon
            icon="mdi-star-four-points"
            color="#F4C95D"
            size="48"
            class="mb-5"
          />

          <h2>Your first deal starts here.</h2>

          <p>
            You don't need experience.
            Just curiosity and the willingness to learn.
            Start building your business skills with Dealora.
          </p>

          <v-btn
            color="white"
            size="x-large"
            rounded="pill"
            class="cta-button mt-8"
            append-icon="mdi-arrow-right"
            href="#join"
          >
            Start Learning
          </v-btn>
        </div>
      </div>
    </v-container>
  </section>
</template>

<style scoped>
.cta-section {
  background: #fff8f4;
  padding: 80px 0 110px;
  scroll-margin-top: 84px;
}

.cta-card {
  position: relative;
  overflow: hidden;
  background: linear-gradient(
    135deg,
    #7048d9 0%,
    #8b5cf6 55%,
    #b084f9 100%
  );
  border-radius: 35px;
  padding: 90px 30px;
  text-align: center;
}

.cta-content {
  position: relative;
  z-index: 2;
}

.cta-content h2 {
  color: #ffffff;
  font-size: clamp(2rem, 4vw, 3.5rem);
  font-weight: 800;
}

.cta-content p {
  color: #eee6ff;
  max-width: 580px;
  margin: 20px auto 0;
  line-height: 1.9;
}

.cta-button {
  color: #7048d9 !important;
  font-weight: 700;
}

.floating-shape {
  position: absolute;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.08);
  animation: float 7s ease-in-out infinite;
}

.shape-one {
  width: 300px;
  height: 300px;
  top: -130px;
  left: -90px;
}

.shape-two {
  width: 230px;
  height: 230px;
  bottom: -100px;
  right: -50px;
  animation-delay: 2s;
}

@keyframes float {
  0%, 100% {
    transform: translateY(0);
  }

  50% {
    transform: translateY(25px);
  }
}

@media (prefers-reduced-motion: reduce) {
  .floating-shape {
    animation: none;
  }
}

@media (max-width: 600px) {
  .cta-card {
    padding: 65px 20px;
  }
}
</style>
