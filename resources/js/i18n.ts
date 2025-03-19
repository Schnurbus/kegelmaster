import { createI18n } from 'vue-i18n';
import de from './locales/de.json';
import en from './locales/en.json';

const i18n = createI18n({
    legacy: false,
    locale: 'de-DE',
    globalInjection: true,
    numberFormats: {
        en: {
            currency: {
                style: 'currency',
                currency: 'USD',
            },
            decimal: {
                style: 'decimal',
                minimumFractionDigits: 0,
                maximumFractionDigits: 2,
            },
            percent: {
                style: 'percent',
                useGrouping: false,
            },
        },
        'de-DE': {
            currency: {
                style: 'currency',
                currency: 'EUR',
            },
            decimal: {
                style: 'decimal',
                minimumFractionDigits: 0,
                maximumFractionDigits: 2,
            },
            percent: {
                style: 'percent',
                useGrouping: false,
            },
        },
    },

    fallbackLocale: 'en',
    messages: { en, de },
});

export default i18n;
