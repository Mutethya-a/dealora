
import { createVuetify } from 'vuetify'
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'

export default createVuetify({
  theme: {
    defaultTheme: 'dealora',
    themes: {
      dealora: {
        dark: false,
        colors: {
          primary: '#6C3EB8',
          secondary: '#A78BFA',
          accent: '#F472B6',
          background: '#FFF8F4',
          surface: '#FFFFFF',
          success: '#22C55E',
          warning: '#F4C95D',
          error: '#EF4444',
          info: '#3B82F6',
          'on-background': '#1F1B3A',
          'on-surface': '#1F1B3A',
        },
      },
    },
  },
  icons: {
    defaultSet: 'mdi',
  },
})
