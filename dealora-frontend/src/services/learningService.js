import api from './api'

const learningService = {
  async getLearningPaths() {
    const response = await api.get('/learning-paths')
    return response.data.data
  },

  async getCourse(slug) {
    const response = await api.get(`/courses/${slug}`)
    return response.data.data
  },

  async getEnrollmentStatus(slug, token) {
    const response = await api.get(
      `/courses/${slug}/enrollment`,
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    )

    return response.data
  },

  async enrollInCourse(slug, token) {
    const response = await api.post(
      `/courses/${slug}/enroll`,
      {},
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    )

    return response.data
  },

  async getLesson(courseSlug, lessonSlug, token) {
  const response = await api.get(
    `/courses/${encodeURIComponent(courseSlug)}/lessons/${encodeURIComponent(lessonSlug)}`,
    {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    }
  )

  return response.data.data
  },

  
async completeLesson(courseSlug, lessonSlug, token) {
  const response = await api.post(
    `/courses/${encodeURIComponent(courseSlug)}/lessons/${encodeURIComponent(lessonSlug)}/complete`,
    {},
    {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    }
  )

  return response.data
},

async getLessonProgress(courseSlug, lessonSlug, token) {
  const response = await api.get(
    `/courses/${encodeURIComponent(courseSlug)}/lessons/${encodeURIComponent(lessonSlug)}/progress`,
    {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    }
  )

  return response.data
},

async getMyLearning(token) {
  const response = await api.get('/my-learning', {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  })

  return response.data.data
},

async getCourseProgress(slug, token) {
  const response = await api.get(
    `/courses/${encodeURIComponent(slug)}/progress`,
    {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    }
  )

  return response.data.data
},

}

export default learningService
