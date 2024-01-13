import { SUPPORTED_LANGUAGES } from '@/plugins/i18n/languages'

type Languages = typeof SUPPORTED_LANGUAGES
interface Locale {
  [key: string]: string | Locale
}

const messages = Object.fromEntries(
  Object.entries(import.meta.glob<{ default: any }>('./locales/*.json', { eager: true })).map(
    ([key, value]) => [key.replace(/\.\/locales\/(.+)\.json/, '$1'), value.default]
  )
) as Record<Languages[keyof Languages], Locale>

const defaultLanguage = SUPPORTED_LANGUAGES.ENGLISH
export const locale = localStorage.getItem('locale') || defaultLanguage

export function buildI18nConfig(): Record<string, any> {
  return {
    legacy: false,
    availableLocales: Object.values(SUPPORTED_LANGUAGES),
    defaultLanguage,
    locale,
    inheritLocale: false,
    locales: [
      {
        code: 'en',
        name: 'English',
        file: 'en.json'
      },
      {
        code: 'ru',
        name: 'Russian',
        file: 'ru.json'
      }
    ],
    messages,
    lazy: true,
    langDir: 'locales',
    strategy: 'no_prefix',
    detectBrowserLanguage: false,
    pluralRules: {
      ru(choice: number, choicesLength: number) {
        if (choice === 0) return 0

        const teen = choice > 10 && choice < 20
        const endsWithOne = choice % 10 === 1

        if (choicesLength < 4) return !teen && endsWithOne ? 1 : 2

        if (!teen && endsWithOne) return 1

        if (!teen && choice % 10 >= 2 && choice % 10 <= 4) return 2

        return choicesLength < 4 ? 2 : 3
      },
      en(choice: number, choicesLength: number) {
        return Math.min(choice, choicesLength - 1)
      }
    },
    numberFormats: {
      'en-US': {
        currency: {
          style: 'currency',
          currency: 'USD',
          notation: 'standard'
        },
        decimal: {
          style: 'decimal',
          minimumFractionDigits: 2,
          maximumFractionDigits: 2
        },
        percent: {
          style: 'percent',
          useGrouping: false
        }
      },
      'ru-RU': {
        currency: {
          style: 'currency',
          currency: '',
          useGrouping: true,
          currencyDisplay: 'symbol'
        },
        decimal: {
          style: 'decimal',
          minimumSignificantDigits: 3,
          maximumSignificantDigits: 5
        },
        percent: {
          style: 'percent',
          useGrouping: false
        }
      }
    }
  }
}
