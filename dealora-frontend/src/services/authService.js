
import api from './api'

export const authService = {
  async register(userData) {
    const response = await api.post('/register', userData)
    return response.data
  },

  async login(credentials) {
    const response = await api.post('/login', credentials)
    return response.data
  },

  async getUser(token) {
    const response = await api.get('/user', {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })

    return response.data
  },

  async logout(token) {
    const response = await api.post(
      '/logout',
      {},
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    )

    return response.data
  },
}
