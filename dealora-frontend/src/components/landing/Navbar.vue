
<script setup>
import { ref } from 'vue'

const mobileMenu = ref(false)

const links = [
  { title: 'Home', href: '#home' },
  { title: 'Learning Paths', href: '#learning-paths' },
  { title: 'How It Works', href: '#how-it-works' },
  { title: 'About', href: '#about' },
]
</script>

<template>
  <v-app-bar flat color="transparent" height="84">
    <v-container class="d-flex align-center">
      <a href="#home" class="brand">
        Dealora<span>.</span>
      </a>

      <v-spacer />

      <div class="d-none d-md-flex align-center ga-7">
        <a
          v-for="link in links"
          :key="link.title"
          :href="link.href"
          class="nav-link"
        >
          {{ link.title }}
        </a>
      </div>

      <v-spacer />

      <div class="d-none d-md-flex align-center ga-3">
        <v-btn
          variant="text"
          color="primary"
          href="/login"
        >
          Log In
        </v-btn>

        <v-btn
          color="primary"
          rounded="pill"
          elevation="0"
          href="/register"
        >
          Get Started
        </v-btn>
      </div>

      <v-app-bar-nav-icon
        class="d-md-none"
        @click="mobileMenu = !mobileMenu"
      />
    </v-container>
  </v-app-bar>

  <v-navigation-drawer
    v-model="mobileMenu"
    location="right"
    temporary
  >
    <v-list nav>
      <v-list-item
        v-for="link in links"
        :key="link.title"
        :title="link.title"
        :href="link.href"
        @click="mobileMenu = false"
      />

      <v-divider class="my-3" />

      <v-list-item title="Log In" to="/login" @click="mobileMenu = false" />
      <v-list-item title="Get Started" to="/register" @click="mobileMenu = false" />
    </v-list>
  </v-navigation-drawer>
</template>

<style scoped>
.brand {
  font-family: 'Poppins', sans-serif;
  font-size: 2rem;
  font-weight: 800;
  color: #6c3eb8;
  letter-spacing: -1.5px;
}

.brand span {
  color: #f472b6;
}

.nav-link {
  color: #342c4e;
  font-size: 0.9rem;
  font-weight: 600;
  transition: color 0.2s;
}

.nav-link:hover {
  color: #6c3eb8;
}
</style>
