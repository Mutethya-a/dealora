
<script setup>
const currentYear = new Date().getFullYear()

const exploreLinks = [
  { title: 'Sales Foundations', href: '#learning-paths' },
  { title: 'Negotiation Skills', href: '#learning-paths' },
  { title: 'Procurement', href: '#learning-paths' },
  { title: 'Importation', href: '#learning-paths' },
]

const companyLinks = [
  { title: 'About Dealora', href: '#about' },
  { title: 'How It Works', href: '#how-it-works' },
  { title: 'Get Started', href: '#join' },
]
</script>

<template>
  <footer class="footer-section">
    <v-container>
      <v-row>
        <v-col cols="12" md="5">
          <a href="#home" class="footer-logo">
            Dealora<span>.</span>
          </a>

          <p class="footer-description mt-4">
            Empowering the next generation of
            confident sellers, negotiators,
            and entrepreneurs through practical
            business education.
          </p>

          <div class="social-icons mt-6">
            <v-icon icon="mdi-school-outline" size="24" />
            <v-icon icon="mdi-handshake-outline" size="24" />
            <v-icon icon="mdi-earth" size="24" />
          </div>
        </v-col>

        <v-col cols="6" sm="4" md="3">
          <h3>Explore</h3>

          <a
            v-for="link in exploreLinks"
            :key="link.title"
            :href="link.href"
            class="footer-link"
          >
            {{ link.title }}
          </a>
        </v-col>

        <v-col cols="6" sm="4" md="2">
          <h3>Company</h3>

          <a
            v-for="link in companyLinks"
            :key="link.title"
            :href="link.href"
            class="footer-link"
          >
            {{ link.title }}
          </a>
        </v-col>

        <v-col cols="12" sm="4" md="2">
          <h3>Get Started</h3>

          <p class="footer-small">
            Ready to develop your business skills?
          </p>

          <v-btn
            color="primary"
            rounded="pill"
            size="small"
            href="#join"
            class="mt-4"
          >
            Join Dealora
          </v-btn>
        </v-col>
      </v-row>

      <v-divider class="my-8" />

      <div class="footer-bottom">
        <p>
          © {{ currentYear }} Dealora.
          All rights reserved.
        </p>

        <p>Learn the skills. Make the deal.</p>
      </div>
    </v-container>
  </footer>
</template>

<style scoped>
.footer-section {
  background: #211a39;
  color: #ffffff;
  padding: 75px 0 25px;
}

.footer-logo {
  font-family: 'Poppins', sans-serif;
  font-size: 2.2rem;
  font-weight: 800;
  color: #ffffff;
}

.footer-logo span {
  color: #f472b6;
}

.footer-description {
  max-width: 350px;
  color: #bdb4d0;
  line-height: 1.9;
  font-size: 0.9rem;
}

.social-icons {
  display: flex;
  gap: 18px;
  color: #c5b4f6;
}

.footer-section h3 {
  font-size: 1rem;
  font-weight: 700;
  margin-bottom: 25px;
}

.footer-link {
  display: block;
  color: #bdb4d0;
  font-size: 0.88rem;
  margin-bottom: 16px;
  transition: color 0.2s;
}

.footer-link:hover {
  color: #f472b6;
}

.footer-small {
  color: #bdb4d0;
  font-size: 0.85rem;
  line-height: 1.7;
}

.footer-bottom {
  display: flex;
  justify-content: space-between;
  gap: 20px;
  flex-wrap: wrap;
  color: #bdb4d0;
  font-size: 0.8rem;
}

@media (max-width: 600px) {
  .footer-section {
    padding-top: 55px;
  }
}
</style>
