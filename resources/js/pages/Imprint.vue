<script setup lang="ts">
import { Layers, Mail, Phone, MapPin, Github } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
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

            <main class="container mx-auto px-4 py-12 md:py-16">
                <div class="mx-auto max-w-3xl">
                    <h1 class="mb-8 text-3xl font-bold text-indigo-400 md:text-4xl">Impressum</h1>

                    <div class="space-y-8 rounded-xl border border-gray-800 bg-gray-900/50 p-6 shadow-lg md:p-8">
                        <!-- Angaben gemäß § 5 TMG -->
                        <section>
                            <h2 class="mb-4 text-xl font-semibold text-white">Angaben gemäß § 5 TMG</h2>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <MapPin class="mt-1 mr-3 h-5 w-5 flex-shrink-0 text-indigo-400" />
                                    <div>
                                        <p>{{ props.name }}</p>
                                        <p>{{  props.address1 }}</p>
                                        <p>{{  props.address2 }}</p>
                                        <p>{{ props.country }}</p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Kontakt -->
                        <section>
                            <h2 class="mb-4 text-xl font-semibold text-white">Kontakt</h2>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <Phone class="mr-3 h-5 w-5 flex-shrink-0 text-indigo-400" />
                                    <p>Telefon: {{ props.phone }}</p>
                                </div>
                                <div class="flex items-center">
                                    <Mail class="mr-3 h-5 w-5 flex-shrink-0 text-indigo-400" />
                                    <p>E-Mail: {{ props.email }}</p>
                                </div>
                            </div>
                        </section>

                        <!-- Verantwortlich für den Inhalt -->
                        <section>
                            <h2 class="mb-4 text-xl font-semibold text-white">Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV</h2>
                            <p>{{ props.name }}</p>
                            <p>{{ props.address1 }}</p>
                            <p>{{ props.address2 }}</p>
                        </section>

                        <!-- Haftungshinweis -->
                        <section>
                            <h2 class="mb-4 text-xl font-semibold text-white">Haftungshinweis</h2>
                            <p class="mb-4 text-gray-300">
                                Trotz sorgfältiger inhaltlicher Kontrolle übernehmen wir keine Haftung für die Inhalte externer Links. Für den Inhalt der
                                verlinkten Seiten sind ausschließlich deren Betreiber verantwortlich.
                            </p>
                            <p class="text-gray-300">
                                Die Inhalte unserer Seiten wurden mit größter Sorgfalt erstellt. Für die Richtigkeit, Vollständigkeit und Aktualität der
                                Inhalte können wir jedoch keine Gewähr übernehmen.
                            </p>
                        </section>
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
