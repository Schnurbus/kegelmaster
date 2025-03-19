import 'vue-i18n';
// import { MessageSchema } from '@/locales/schema'; // Falls du eine Schema-Datei für deine Übersetzungen hast

declare module 'vue' {
    interface ComponentCustomProperties {
        $t: (typeof import('vue-i18n'))['t'];
        $d: (typeof import('vue-i18n'))['d'];
    }
}
