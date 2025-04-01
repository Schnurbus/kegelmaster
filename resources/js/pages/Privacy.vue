<script setup lang="ts">
import { Layers, ShieldCheck, Cookie, CloudCog, HardDrive, Server, CircleUserRound, Scale } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion';
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-950 via-gray-900 to-gray-950 text-gray-100">
        <!-- Header -->
        <header class="border-b border-gray-800">
            <div class="container mx-auto py-6 px-4 flex justify-between items-center">
                <Link href="/public" class="flex items-center space-x-2">
                    <Layers class="h-8 w-8 text-indigo-500" />
                    <span class="text-xl font-bold">KegelMaster</span>
                </Link>

                <Link href="/public">
                    <Button variant="outline" class="border-gray-700 text-gray-300 hover:bg-gray-800">
                        Zurück zur Startseite
                    </Button>
                </Link>
            </div>
        </header>

        <!-- Content -->
        <main class="container mx-auto px-4 py-12 md:py-16">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-3xl md:text-4xl font-bold mb-8 text-indigo-400">Datenschutzerklärung</h1>

                <div class="flex items-center mb-8 p-4 bg-indigo-900/20 border border-indigo-800 rounded-lg">
                    <ShieldCheck class="h-6 w-6 text-indigo-400 mr-3 flex-shrink-0" />
                    <p class="text-gray-300">
                        Der Schutz Ihrer persönlichen Daten ist uns wichtig. Diese Datenschutzerklärung informiert Sie darüber,
                        wie und zu welchem Zweck wir personenbezogene Daten erheben und verarbeiten.
                    </p>
                </div>

                <Accordion type="single" collapsible class="space-y-4">
                    <!-- 1. Verantwortliche Stelle -->
                    <AccordionItem value="item-1" class="bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden">
                        <AccordionTrigger class="px-6 py-4 hover:bg-gray-800/50">
                            <div class="flex items-center">
                                <CircleUserRound class="h-5 w-5 mr-3 text-indigo-400" />
                                <h2 class="text-xl font-semibold text-white">1. Verantwortliche Stelle</h2>
                            </div>
                        </AccordionTrigger>
                        <AccordionContent class="px-6 pb-4 pt-2 text-gray-300">
                            <p class="mb-4">Verantwortlich für die Datenverarbeitung auf dieser Website ist:</p>
                            <div class="mb-4">
                                <p>Pascal Schnurbus</p>
                                <p>Godefriedstr. 22</p>
                                <p>44265 Dortmund</p>
                                <p>E-Mail: kegelmaster@schnurbus.de</p>
                            </div>
                            <p>
                                Diese private Website wird ohne Gewinnabsicht betrieben und dient ausschließlich
                                der Verwaltung von Vereinsdaten für registrierte Benutzer.
                            </p>
                        </AccordionContent>
                    </AccordionItem>

                    <!-- 2. Cookies -->
                    <AccordionItem value="item-2" class="bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden">
                        <AccordionTrigger class="px-6 py-4 hover:bg-gray-800/50">
                            <div class="flex items-center">
                                <Cookie class="h-5 w-5 mr-3 text-indigo-400" />
                                <h2 class="text-xl font-semibold text-white">2. Cookies</h2>
                            </div>
                        </AccordionTrigger>
                        <AccordionContent class="px-6 pb-4 pt-2 text-gray-300">
                            <p class="mb-4">
                                Unsere Website verwendet ausschließlich technisch notwendige Cookies, die für den Betrieb
                                der Website und die Bereitstellung unserer Dienste unerlässlich sind.
                            </p>
                            <p class="mb-4">
                                <strong>Session-Cookies:</strong> Diese temporären Cookies werden für die Anmeldung und
                                Authentifizierung verwendet. Sie werden gelöscht, sobald Sie Ihren Browser schließen.
                            </p>
                            <p>
                                <strong>Persistente Cookies:</strong> Für die "Angemeldet bleiben"-Funktion verwenden wir
                                längerfristige Cookies, die Ihre Anmeldedaten für einen begrenzten Zeitraum speichern,
                                um wiederholte Anmeldungen zu vermeiden.
                            </p>
                        </AccordionContent>
                    </AccordionItem>

                    <!-- 3. Cloudflare CDN -->
                    <AccordionItem value="item-3" class="bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden">
                        <AccordionTrigger class="px-6 py-4 hover:bg-gray-800/50">
                            <div class="flex items-center">
                                <CloudCog class="h-5 w-5 mr-3 text-indigo-400" />
                                <h2 class="text-xl font-semibold text-white">3. Cloudflare als CDN</h2>
                            </div>
                        </AccordionTrigger>
                        <AccordionContent class="px-6 pb-4 pt-2 text-gray-300">
                            <p class="mb-4">
                                Wir nutzen das Content Delivery Network (CDN) von Cloudflare, Inc., mit Sitz in
                                101 Townsend St, San Francisco, CA 94107, USA, um unsere Website schneller
                                und sicherer zu machen.
                            </p>
                            <p class="mb-4">
                                Cloudflare kann bestimmte Informationen erfassen, wie z.B. Ihre IP-Adresse,
                                um den Datenverkehr zu optimieren und Schutz gegen DDoS-Angriffe zu bieten.
                                Cloudflare speichert aggregierte Statistiken über die Nutzung unserer Website,
                                jedoch keine personenbezogenen Informationen.
                            </p>
                            <p>
                                Weitere Informationen über die Datenverarbeitung durch Cloudflare finden Sie in
                                deren Datenschutzerklärung: <a href="https://www.cloudflare.com/privacypolicy/" target="_blank" class="text-indigo-400 hover:text-indigo-300 underline">https://www.cloudflare.com/privacypolicy/</a>
                            </p>
                        </AccordionContent>
                    </AccordionItem>

                    <!-- 4. Server-Protokolle -->
                    <AccordionItem value="item-4" class="bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden">
                        <AccordionTrigger class="px-6 py-4 hover:bg-gray-800/50">
                            <div class="flex items-center">
                                <Server class="h-5 w-5 mr-3 text-indigo-400" />
                                <h2 class="text-xl font-semibold text-white">4. Server-Logfiles</h2>
                            </div>
                        </AccordionTrigger>
                        <AccordionContent class="px-6 pb-4 pt-2 text-gray-300">
                            <p class="mb-4">
                                Bei jedem Aufruf unserer Website erfasst unser System automatisch Daten und
                                Informationen vom System des aufrufenden Computers. Folgende Daten werden hierbei erhoben:
                            </p>
                            <ul class="list-disc list-inside mb-4 space-y-1">
                                <li>IP-Adresse</li>
                                <li>Datum und Uhrzeit der Anfrage</li>
                                <li>Zugriffsstatus/HTTP-Statuscode</li>
                                <li>Referrer-URL (zuvor besuchte Seite)</li>
                                <li>Verwendeter Browser und Betriebssystem</li>
                                <li>Menge der übertragenen Daten</li>
                            </ul>
                            <p class="mb-4">
                                Diese Server-Logfiles werden ausschließlich für die Absicherung und
                                Optimierung unseres Angebots verwendet und automatisch nach 7 Tagen gelöscht.
                            </p>
                            <p>
                                Die Rechtsgrundlage für die Verarbeitung dieser Daten ist unser berechtigtes
                                Interesse an der fehlerfreien Funktion unserer Website gemäß Art. 6 Abs. 1 lit. f DSGVO.
                            </p>
                        </AccordionContent>
                    </AccordionItem>

                    <!-- 5. Nutzerkonten und Registrierung -->
                    <AccordionItem value="item-5" class="bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden">
                        <AccordionTrigger class="px-6 py-4 hover:bg-gray-800/50">
                            <div class="flex items-center">
                                <HardDrive class="h-5 w-5 mr-3 text-indigo-400" />
                                <h2 class="text-xl font-semibold text-white">5. Nutzerkonten und Registrierung</h2>
                            </div>
                        </AccordionTrigger>
                        <AccordionContent class="px-6 pb-4 pt-2 text-gray-300">
                            <p class="mb-4">
                                Für die Nutzung unserer Vereinsverwaltung ist die Erstellung eines Benutzerkontos erforderlich.
                                Bei der Registrierung erheben wir folgende Daten:
                            </p>
                            <ul class="list-disc list-inside mb-4 space-y-1">
                                <li>Name</li>
                                <li>E-Mail-Adresse</li>
                                <li>Passwort (verschlüsselt gespeichert)</li>
                            </ul>
                            <p class="mb-4">
                                Diese Daten werden ausschließlich für die Bereitstellung unseres Dienstes verwendet
                                und nicht an Dritte weitergegeben. Die Rechtsgrundlage hierfür ist die Erfüllung des
                                Nutzungsvertrags gemäß Art. 6 Abs. 1 lit. b DSGVO.
                            </p>
                            <p>
                                Ihr Benutzerkonto können Sie jederzeit löschen. Dabei werden alle mit Ihrem Konto
                                verknüpften personenbezogenen Daten unwiderruflich gelöscht, sofern keine gesetzlichen
                                Aufbewahrungspflichten bestehen.
                            </p>
                        </AccordionContent>
                    </AccordionItem>

                    <!-- 6. Betroffenenrechte -->
                    <AccordionItem value="item-6" class="bg-gray-900/50 border border-gray-800 rounded-xl overflow-hidden">
                        <AccordionTrigger class="px-6 py-4 hover:bg-gray-800/50">
                            <div class="flex items-center">
                                <Scale class="h-5 w-5 mr-3 text-indigo-400" />
                                <h2 class="text-xl font-semibold text-white">6. Ihre Rechte</h2>
                            </div>
                        </AccordionTrigger>
                        <AccordionContent class="px-6 pb-4 pt-2 text-gray-300">
                            <p class="mb-4">Als betroffene Person haben Sie folgende Rechte:</p>
                            <ul class="list-disc list-inside mb-4 space-y-1">
                                <li>Auskunftsrecht (Art. 15 DSGVO)</li>
                                <li>Recht auf Berichtigung (Art. 16 DSGVO)</li>
                                <li>Recht auf Löschung (Art. 17 DSGVO)</li>
                                <li>Recht auf Einschränkung der Verarbeitung (Art. 18 DSGVO)</li>
                                <li>Recht auf Datenübertragbarkeit (Art. 20 DSGVO)</li>
                                <li>Widerspruchsrecht (Art. 21 DSGVO)</li>
                            </ul>
                            <p class="mb-4">
                                Zur Ausübung Ihrer Rechte wenden Sie sich bitte per E-Mail an kegelmaster@schnurbus.net.
                            </p>
                            <p>
                                Darüber hinaus haben Sie das Recht, sich bei einer Datenschutzaufsichtsbehörde
                                über die Verarbeitung Ihrer personenbezogenen Daten durch uns zu beschweren.
                            </p>
                        </AccordionContent>
                    </AccordionItem>
                </Accordion>

                <!-- Aktualisierungshinweis -->
                <div class="mt-8 p-4 bg-gray-900/70 border border-gray-800 rounded-lg text-gray-400 text-sm">
                    <p>
                        Diese Datenschutzerklärung ist aktuell gültig und hat den Stand April 2023.
                        Aufgrund technischer oder rechtlicher Entwicklungen kann es notwendig werden,
                        diese Datenschutzerklärung anzupassen.
                    </p>
                </div>

                <!-- Navigation Links -->
                <div class="mt-8 flex justify-center space-x-6">
                    <Link :href="route('imprint')" class="text-indigo-400 hover:text-indigo-300 transition-colors">
                        Impressum
                    </Link>
                    <Link :href="route('home')" class="text-indigo-400 hover:text-indigo-300 transition-colors">
                        Startseite
                    </Link>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-950 py-6 border-t border-gray-800 mt-12">
            <div class="container mx-auto px-4 text-center text-sm text-gray-500">
                <p>&copy; 2025 KegelMaster. Alle Rechte vorbehalten.</p>
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
