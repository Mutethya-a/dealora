
import { createRouter, createWebHistory } from 'vue-router'

import LandingView from '../views/LandingView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import { useAuthStore } from '../stores/auth'
import CourseDetailsView from '../views/CourseDetailsView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: LandingView,
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView,
  },
  {
    path: '/register',
    name: 'register',
    component: RegisterView,
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('../views/DashboardView.vue'),
    meta: {
      requiresAuth: true,
    },
  },
  {
  path: '/courses/:slug',
  name: 'course-details',
  component: CourseDetailsView,
  meta: { requiresAuth: true },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,

  scrollBehavior(to) {
    if (to.hash) {
      return {
        el: to.hash,
        behavior: 'smooth',
      }
    }

    return { top: 0 }
  },
})
router.beforeEach((to) => {
  const authStore = useAuthStore()

  //unauthenticated users cant access protected stuff
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return {
      name: 'login',
      query: { redirect: to.fullPath },
    }
  }

  //cant return to login or registration after doing it
  if (
    authStore.isAuthenticated &&
    (to.name === 'login' || to.name === 'register')
  ) {
    return { name: 'dashboard' }
  }
})

export default router
