<script setup lang="ts">
import { Layers, Mail, Phone, MapPin } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-950 via-gray-900 to-gray-950 text-gray-100">
        <!-- Header -->
        <header class="border-b border-gray-800">
            <div class="container mx-auto py-6 px-4 flex justify-between items-center">
                <Link href="/" class="flex items-center space-x-2">
                    <Layers class="h-8 w-8 text-indigo-500" />
                    <span class="text-xl font-bold">KegelMaster</span>
                </Link>

                <Link href="/">
                    <Button variant="outline" class="border-gray-700 text-gray-300 hover:bg-gray-800">
                        Zurück zur Startseite
                    </Button>
                </Link>
            </div>
        </header>

        <!-- Content -->
        <main class="container mx-auto px-4 py-12 md:py-16">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-3xl md:text-4xl font-bold mb-8 text-indigo-400">Impressum</h1>

                <div class="bg-gray-900/50 rounded-xl p-6 md:p-8 border border-gray-800 space-y-8 shadow-lg">
                    <!-- Angaben gemäß § 5 TMG -->
                    <section>
                        <h2 class="text-xl font-semibold mb-4 text-white">Angaben gemäß § 5 TMG</h2>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <MapPin class="h-5 w-5 mr-3 text-indigo-400 mt-1 flex-shrink-0" />
                                <div>
                                    <p>Pascal Schnurbus</p>
                                    <p>Godefriedstr. 22</p>
                                    <p>44265 Dortmund</p>
                                    <p>Deutschland</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Kontakt -->
                    <section>
                        <h2 class="text-xl font-semibold mb-4 text-white">Kontakt</h2>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <Phone class="h-5 w-5 mr-3 text-indigo-400 flex-shrink-0" />
                                <p>Telefon: +49 ja habe ich</p>
                            </div>
                            <div class="flex items-center">
                                <Mail class="h-5 w-5 mr-3 text-indigo-400 flex-shrink-0" />
                                <p>E-Mail: kegelmaster@schnurbus.de</p>
                            </div>
                        </div>
                    </section>

                    <!-- Verantwortlich für den Inhalt -->
                    <section>
                        <h2 class="text-xl font-semibold mb-4 text-white">Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV</h2>
                        <p>Pascal Schnurbus</p>
                        <p>Godefriedstr. 22</p>
                        <p>44265 Dortmund</p>
                    </section>

                    <!-- Haftungshinweis -->
                    <section>
                        <h2 class="text-xl font-semibold mb-4 text-white">Haftungshinweis</h2>
                        <p class="text-gray-300 mb-4">
                            Trotz sorgfältiger inhaltlicher Kontrolle übernehmen wir keine Haftung für die Inhalte externer Links.
                            Für den Inhalt der verlinkten Seiten sind ausschließlich deren Betreiber verantwortlich.
                        </p>
                        <p class="text-gray-300">
                            Die Inhalte unserer Seiten wurden mit größter Sorgfalt erstellt. Für die Richtigkeit, Vollständigkeit und Aktualität
                            der Inhalte können wir jedoch keine Gewähr übernehmen.
                        </p>
                    </section>
                </div>

                <!-- Navigation Links -->
                <div class="mt-8 flex justify-center space-x-6">
                    <Link :href="route('privacy')" class="text-indigo-400 hover:text-indigo-300 transition-colors">
                        Datenschutzerklärung
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
