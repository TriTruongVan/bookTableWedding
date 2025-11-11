import { createI18n } from 'vue-i18n'
import vi from './locales/vi.json'

export const i18n = createI18n({
  legacy: false,
  globalInjection: true,
  locale: import.meta.env.VITE_APP_LOCALE || 'vi',
  messages: {
    vi
  }
})
