<script setup lang="ts">
import {
    Layers,
    ShieldCheck,
    Cookie,
    CloudCog,
    HardDrive,
    Server,
    CircleUserRound,
    Scale,
    Github
} from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion';
import { navItems } from '@/types/nav-items';

interface Props {
    name: string;
    address1: string;
    address2: string;
    country: string;
    email: string;
    phone: string;
}

const props = defineProps<Props>();
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-950 via-gray-900 to-gray-950 text-gray-100">
        <!-- Hero Section with Navigation -->
        <header class="relative overflow-hidden">
            <!-- Navigation -->
            <nav class="container mx-auto flex items-center justify-between px-4 py-6">
                <div class="flex items-center space-x-2">
                    <Layers class="h-8 w-8 text-indigo-500" />
                    <span class="text-xl font-bold">KegelMaster</span>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden space-x-8 md:flex">
                    <a v-for="item in navItems" :key="item.label" :href="item.href" class="text-gray-300 transition-colors hover:text-white">
                        {{ item.label }}
                    </a>
                </div>

                <div class="flex space-x-4">
                    <Link href="/login">
                        <Button variant="ghost" class="text-gray-300 hover:text-white"> Login </Button>
                    </Link>
                    <Link href="/register">
                        <Button class="bg-indigo-600 hover:bg-indigo-700"> Registrieren </Button>
                    </Link>
                </div>
            </nav>
        </header>

        <!-- Content -->
        <main class="container mx-auto px-4 py-12 md:py-16">
            <div class="mx-auto max-w-3xl">
                <h1 class="mb-8 text-3xl font-bold text-indigo-400 md:text-4xl">Datenschutzerklärung</h1>

                <div class="mb-8 flex items-center rounded-lg border border-indigo-800 bg-indigo-900/20 p-4">
                    <ShieldCheck class="mr-3 h-6 w-6 flex-shrink-0 text-indigo-400" />
                    <p class="text-gray-300">
                        Der Schutz Ihrer persönlichen Daten ist uns wichtig. Diese Datenschutzerklärung informiert Sie darüber, wie und zu welchem
                        Zweck wir personenbezogene Daten erheben und verarbeiten.
                    </p>
                </div>

                <Accordion type="single" collapsible class="space-y-4">
                    <!-- 1. Verantwortliche Stelle -->
                    <AccordionItem value="item-1" class="overflow-hidden rounded-xl border border-gray-800 bg-gray-900/50">
                        <AccordionTrigger class="px-6 py-4 hover:bg-gray-800/50">
                            <div class="flex items-center">
                                <CircleUserRound class="mr-3 h-5 w-5 text-indigo-400" />
                                <h2 class="text-xl font-semibold text-white">1. Verantwortliche Stelle</h2>
                            </div>
                        </AccordionTrigger>
                        <AccordionContent class="px-6 pt-2 pb-4 text-gray-300">
                            <p class="mb-4">Verantwortlich für die Datenverarbeitung auf dieser Website ist:</p>
                            <div class="mb-4">
                                <p>{{ props.name }}</p>
                                <p>{{  props.address1 }}</p>
                                <p>{{  props.address2 }}</p>
                                <p>{{ props.country }}</p>
                                <p>E-Mail: {{ props.email }}de</p>
                            </div>
                            <p>
                                Diese private Website wird ohne Gewinnabsicht betrieben und dient ausschließlich der Verwaltung von Vereinsdaten für
                                registrierte Benutzer.
                            </p>
                        </AccordionContent>
                    </AccordionItem>

                    <!-- 2. Cookies -->
                    <AccordionItem value="item-2" class="overflow-hidden rounded-xl border border-gray-800 bg-gray-900/50">
                        <AccordionTrigger class="px-6 py-4 hover:bg-gray-800/50">
                            <div class="flex items-center">
                                <Cookie class="mr-3 h-5 w-5 text-indigo-400" />
                                <h2 class="text-xl font-semibold text-white">2. Cookies</h2>
                            </div>
                        </AccordionTrigger>
                        <AccordionContent class="px-6 pt-2 pb-4 text-gray-300">
                            <p class="mb-4">
                                Unsere Website verwendet ausschließlich technisch notwendige Cookies, die für den Betrieb der Website und die
                                Bereitstellung unserer Dienste unerlässlich sind.
                            </p>
                            <p class="mb-4">
                                <strong>Session-Cookies:</strong> Diese temporären Cookies werden für die Anmeldung und Authentifizierung verwendet.
                                Sie werden gelöscht, sobald Sie Ihren Browser schließen.
                            </p>
                            <p>
                                <strong>Persistente Cookies:</strong> Für die "Angemeldet bleiben"-Funktion verwenden wir längerfristige Cookies, die
                                Ihre Anmeldedaten für einen begrenzten Zeitraum speichern, um wiederholte Anmeldungen zu vermeiden.
                            </p>
                        </AccordionContent>
                    </AccordionItem>

                    <!-- 3. Cloudflare CDN -->
                    <AccordionItem value="item-3" class="overflow-hidden rounded-xl border border-gray-800 bg-gray-900/50">
                        <AccordionTrigger class="px-6 py-4 hover:bg-gray-800/50">
                            <div class="flex items-center">
                                <CloudCog class="mr-3 h-5 w-5 text-indigo-400" />
                                <h2 class="text-xl font-semibold text-white">3. Cloudflare als CDN</h2>
                            </div>
                        </AccordionTrigger>
                        <AccordionContent class="px-6 pt-2 pb-4 text-gray-300">
                            <p class="mb-4">
                                Wir nutzen das Content Delivery Network (CDN) von Cloudflare, Inc., mit Sitz in 101 Townsend St, San Francisco, CA
                                94107, USA, um unsere Website schneller und sicherer zu machen.
                            </p>
                            <p class="mb-4">
                                Cloudflare kann bestimmte Informationen erfassen, wie z.B. Ihre IP-Adresse, um den Datenverkehr zu optimieren und
                                Schutz gegen DDoS-Angriffe zu bieten. Cloudflare speichert aggregierte Statistiken über die Nutzung unserer Website,
                                jedoch keine personenbezogenen Informationen.
                            </p>
                            <p>
                                Weitere Informationen über die Datenverarbeitung durch Cloudflare finden Sie in deren Datenschutzerklärung:
                                <a
                                    href="https://www.cloudflare.com/privacypolicy/"
                                    target="_blank"
                                    class="text-indigo-400 underline hover:text-indigo-300"
                                    >https://www.cloudflare.com/privacypolicy/</a
                                >
                            </p>
                        </AccordionContent>
                    </AccordionItem>

                    <!-- 4. Server-Protokolle -->
                    <AccordionItem value="item-4" class="overflow-hidden rounded-xl border border-gray-800 bg-gray-900/50">
                        <AccordionTrigger class="px-6 py-4 hover:bg-gray-800/50">
                            <div class="flex items-center">
                                <Server class="mr-3 h-5 w-5 text-indigo-400" />
                                <h2 class="text-xl font-semibold text-white">4. Server-Logfiles</h2>
                            </div>
                        </AccordionTrigger>
                        <AccordionContent class="px-6 pt-2 pb-4 text-gray-300">
                            <p class="mb-4">
                                Bei jedem Aufruf unserer Website erfasst unser System automatisch Daten und Informationen vom System des aufrufenden
                                Computers. Folgende Daten werden hierbei erhoben:
                            </p>
                            <ul class="mb-4 list-inside list-disc space-y-1">
                                <li>IP-Adresse</li>
                                <li>Datum und Uhrzeit der Anfrage</li>
                                <li>Zugriffsstatus/HTTP-Statuscode</li>
                                <li>Referrer-URL (zuvor besuchte Seite)</li>
                                <li>Verwendeter Browser und Betriebssystem</li>
                                <li>Menge der übertragenen Daten</li>
                            </ul>
                            <p class="mb-4">
                                Diese Server-Logfiles werden ausschließlich für die Absicherung und Optimierung unseres Angebots verwendet und
                                automatisch nach 7 Tagen gelöscht.
                            </p>
                            <p>
                                Die Rechtsgrundlage für die Verarbeitung dieser Daten ist unser berechtigtes Interesse an der fehlerfreien Funktion
                                unserer Website gemäß Art. 6 Abs. 1 lit. f DSGVO.
                            </p>
                        </AccordionContent>
                    </AccordionItem>

                    <!-- 5. Nutzerkonten und Registrierung -->
                    <AccordionItem value="item-5" class="overflow-hidden rounded-xl border border-gray-800 bg-gray-900/50">
                        <AccordionTrigger class="px-6 py-4 hover:bg-gray-800/50">
                            <div class="flex items-center">
                                <HardDrive class="mr-3 h-5 w-5 text-indigo-400" />
                                <h2 class="text-xl font-semibold text-white">5. Nutzerkonten und Registrierung</h2>
                            </div>
                        </AccordionTrigger>
                        <AccordionContent class="px-6 pt-2 pb-4 text-gray-300">
                            <p class="mb-4">
                                Für die Nutzung unserer Vereinsverwaltung ist die Erstellung eines Benutzerkontos erforderlich. Bei der Registrierung
                                erheben wir folgende Daten:
                            </p>
                            <ul class="mb-4 list-inside list-disc space-y-1">
                                <li>Name</li>
                                <li>E-Mail-Adresse</li>
                                <li>Passwort (verschlüsselt gespeichert)</li>
                            </ul>
                            <p class="mb-4">
                                Diese Daten werden ausschließlich für die Bereitstellung unseres Dienstes verwendet und nicht an Dritte weitergegeben.
                                Die Rechtsgrundlage hierfür ist die Erfüllung des Nutzungsvertrags gemäß Art. 6 Abs. 1 lit. b DSGVO.
                            </p>
                            <p>
                                Ihr Benutzerkonto können Sie jederzeit löschen. Dabei werden alle mit Ihrem Konto verknüpften personenbezogenen Daten
                                unwiderruflich gelöscht, sofern keine gesetzlichen Aufbewahrungspflichten bestehen.
                            </p>
                        </AccordionContent>
                    </AccordionItem>

                    <!-- 6. Betroffenenrechte -->
                    <AccordionItem value="item-6" class="overflow-hidden rounded-xl border border-gray-800 bg-gray-900/50">
                        <AccordionTrigger class="px-6 py-4 hover:bg-gray-800/50">
                            <div class="flex items-center">
                                <Scale class="mr-3 h-5 w-5 text-indigo-400" />
                                <h2 class="text-xl font-semibold text-white">6. Ihre Rechte</h2>
                            </div>
                        </AccordionTrigger>
                        <AccordionContent class="px-6 pt-2 pb-4 text-gray-300">
                            <p class="mb-4">Als betroffene Person haben Sie folgende Rechte:</p>
                            <ul class="mb-4 list-inside list-disc space-y-1">
                                <li>Auskunftsrecht (Art. 15 DSGVO)</li>
                                <li>Recht auf Berichtigung (Art. 16 DSGVO)</li>
                                <li>Recht auf Löschung (Art. 17 DSGVO)</li>
                                <li>Recht auf Einschränkung der Verarbeitung (Art. 18 DSGVO)</li>
                                <li>Recht auf Datenübertragbarkeit (Art. 20 DSGVO)</li>
                                <li>Widerspruchsrecht (Art. 21 DSGVO)</li>
                            </ul>
                            <p class="mb-4">Zur Ausübung Ihrer Rechte wenden Sie sich bitte per E-Mail an kegelmaster@schnurbus.net.</p>
                            <p>
                                Darüber hinaus haben Sie das Recht, sich bei einer Datenschutzaufsichtsbehörde über die Verarbeitung Ihrer
                                personenbezogenen Daten durch uns zu beschweren.
                            </p>
                        </AccordionContent>
                    </AccordionItem>
                </Accordion>

                <!-- Aktualisierungshinweis -->
                <div class="mt-8 rounded-lg border border-gray-800 bg-gray-900/70 p-4 text-sm text-gray-400">
                    <p>
                        Diese Datenschutzerklärung ist aktuell gültig und hat den Stand April 2023. Aufgrund technischer oder rechtlicher
                        Entwicklungen kann es notwendig werden, diese Datenschutzerklärung anzupassen.
                    </p>
                </div>

            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t border-gray-800 bg-gray-950 py-12">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
                    <div>
                        <div class="mb-6 flex items-center space-x-2">
                            <Layers class="h-6 w-6 text-indigo-500" />
                            <span class="text-lg font-bold">KegelMaster</span>
                        </div>
                        <p class="mb-4 text-gray-400">
                            Die moderne Lösung für die Verwaltung von Sport- und Freizeitvereinen, entwickelt mit Liebe für Details.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 transition-colors hover:text-white">
                                <Github class="h-5 w-5" />
                            </a>
                            <!-- Weitere Social-Media-Icons hier -->
                        </div>
                    </div>

                    <div>
                        <h4 class="mb-4 text-lg font-semibold">Produkt</h4>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-gray-400 transition-colors hover:text-white">Features</a></li>
                            <li><a href="#" class="text-gray-400 transition-colors hover:text-white">Roadmap</a></li>
                            <li><a href="#" class="text-gray-400 transition-colors hover:text-white">Updates</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="mb-4 text-lg font-semibold">Ressourcen</h4>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-gray-400 transition-colors hover:text-white">Dokumentation</a></li>
                            <li><a href="#" class="text-gray-400 transition-colors hover:text-white">Hilfe-Center</a></li>
                            <li><a href="#" class="text-gray-400 transition-colors hover:text-white">Blog</a></li>
                            <li><a href="#" class="text-gray-400 transition-colors hover:text-white">Community</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="mb-4 text-lg font-semibold">Rechtliches</h4>
                        <ul class="space-y-3">
                            <li>
                                <a :href="route('page', { page: 'privacy'})" class="text-gray-400 transition-colors hover:text-white">Datenschutz</a>
                            </li>
                            <li><a :href="route('page', {page: 'imprint'})" class="text-gray-400 transition-colors hover:text-white">Impressum</a></li>
                        </ul>
                    </div>
                </div>

                <div class="mt-12 border-t border-gray-800 pt-6 text-center text-sm text-gray-500">
                    <p>&copy; 2025 KegelMaster. Alle Rechte vorbehalten.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
/* Custom Styles */
.shadow-lg {
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
}

/* Scrollbar Styling */
::-webkit-scrollbar {
    width: 12px;
}

::-webkit-scrollbar-track {
    background: #121212;
}

::-webkit-scrollbar-thumb {
    background: #3730a3;
    border-radius: 6px;
}

::-webkit-scrollbar-thumb:hover {
    background: #4338ca;
}
</style>
