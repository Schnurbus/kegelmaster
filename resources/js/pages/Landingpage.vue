<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const user = computed(() => usePage().props.auth.user);

if (user.value) {
    router.visit('/dashboard');
}
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <!-- Header -->
    <header class="bg-gradient-to-r from-indigo-600 to-blue-500 text-white">
        <div class="container mx-auto flex items-center justify-between px-6 py-4">
            <h1 class="text-2xl font-bold">🌟 Kegelmaster</h1>
            <nav>
                <ul class="flex space-x-6">
                    <li><a href="#features" class="hover:underline">Funktionen</a></li>
                    <li><a href="#pricing" class="hover:underline">Preise</a></li>
                    <li><a href="#contact" class="hover:underline">Kontakt</a></li>
                    <li><a href="/register" class="hover:underline">Registrieren</a></li>
                    <li><a href="/login" class="hover:underline">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-white py-16 text-center">
        <div class="container mx-auto px-6">
            <h2 class="mb-4 text-4xl font-semibold text-gray-800">Vereinfache die Verwaltung deines Kegelclubs</h2>
            <p class="mx-auto mb-8 max-w-2xl text-lg text-gray-600">
                Mit Kegelmaster kannst du Clubmitglieder, Turniere und Statistiken spielend leicht organisieren. Egal ob auf dem Laptop oder
                Smartphone – alles ist mit wenigen Klicks erreichbar.
            </p>
            <a
                href="#features"
                class="btn-glow rounded-lg bg-gradient-to-r from-orange-400 to-pink-500 px-8 py-3 text-lg font-semibold text-white transition-all hover:shadow-lg"
            >
                Jetzt starten
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="bg-gray-50 py-16">
        <div class="container mx-auto px-6 text-center">
            <h3 class="mb-8 text-3xl font-semibold text-gray-800">Unsere Features</h3>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="rounded-xl bg-white p-6 shadow-md transition-shadow hover:shadow-lg">
                    <h4 class="mb-4 text-xl font-bold text-indigo-600">🎳 Mitgliederverwaltung</h4>
                    <p class="text-gray-600">Behalte den Überblick über alle Clubmitglieder, Beiträge und Rollen in deinem Verein.</p>
                </div>
                <div class="rounded-xl bg-white p-6 shadow-md transition-shadow hover:shadow-lg">
                    <h4 class="mb-4 text-xl font-bold text-indigo-600">🏆 Turniermanagement</h4>
                    <p class="text-gray-600">Plane Turniere, pflege Ergebnisse ein und teile die Ergebnisse live mit den Mitgliedern.</p>
                </div>
                <div class="rounded-xl bg-white p-6 shadow-md transition-shadow hover:shadow-lg">
                    <h4 class="mb-4 text-xl font-bold text-indigo-600">📊 Statistiken</h4>
                    <p class="text-gray-600">Analysiere Leistungen mit umfangreichen Statistiken. Perfekt für eine transparente Vereinsführung!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="bg-white py-16">
        <div class="container mx-auto px-6 text-center">
            <h3 class="mb-8 text-3xl font-semibold text-gray-800">Flexible Preisgestaltung</h3>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <!-- Free Plan -->
                <div class="rounded-xl bg-gray-50 p-8 shadow-md transition-shadow hover:shadow-lg">
                    <h4 class="mb-4 text-lg font-bold">Kostenfrei</h4>
                    <p class="mb-8 text-gray-600">Ideal für kleine Clubs, die die Basisfunktionen nutzen möchten.</p>
                    <p class="mb-4 text-2xl font-bold">0 €</p>
                    <ul class="mb-8 space-y-2 text-gray-600">
                        <li>✅ Mitgliederverwaltung</li>
                        <li>✅ Bis zu 10 Mitglieder</li>
                        <li>❌ Erweiterte Statistiken</li>
                    </ul>
                    <a href="#" class="rounded-lg bg-indigo-600 px-6 py-2 text-white hover:bg-indigo-700">Kostenlos starten</a>
                </div>
                <!-- Pro Plan -->
                <div class="rounded-xl bg-gray-50 p-8 shadow-md transition-shadow hover:shadow-lg">
                    <h4 class="mb-4 text-lg font-bold">Pro</h4>
                    <p class="mb-8 text-gray-600">Perfekt für mittlere Clubs, die alles im Griff haben wollen.</p>
                    <p class="mb-4 text-2xl font-bold">19 € / Monat</p>
                    <ul class="mb-8 space-y-2 text-gray-600">
                        <li>✅ Unbegrenzte Mitglieder</li>
                        <li>✅ Turniermanagement</li>
                        <li>✅ Erweiterte Statistiken</li>
                    </ul>
                    <a href="#" class="rounded-lg bg-indigo-600 px-6 py-2 text-white hover:bg-indigo-700">Jetzt loslegen</a>
                </div>
                <!-- Premium Plan -->
                <div class="rounded-xl bg-gray-50 p-8 shadow-md transition-shadow hover:shadow-lg">
                    <h4 class="mb-4 text-lg font-bold">Premium</h4>
                    <p class="mb-8 text-gray-600">Die All-in-One-Lösung für große Vereine und Organisationen.</p>
                    <p class="mb-4 text-2xl font-bold">49 € / Monat</p>
                    <ul class="mb-8 space-y-2 text-gray-600">
                        <li>✅ Alle Pro Features</li>
                        <li>✅ Individuelle Anpassungen</li>
                        <li>✅ Premium Support</li>
                    </ul>
                    <a href="#" class="rounded-lg bg-indigo-600 px-6 py-2 text-white hover:bg-indigo-700">Jetzt upgraden</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="bg-gradient-to-r from-indigo-600 to-blue-500 py-16 text-white">
        <div class="container mx-auto px-6 text-center">
            <h3 class="mb-4 text-3xl font-semibold">Kontakt</h3>
            <p class="mb-6 text-lg">Du hast Fragen oder benötigst Hilfe? Kontaktiere uns jederzeit!</p>
            <a
                href="mailto:kegelmaster@schnurbus.de"
                class="rounded-lg bg-white px-8 py-3 font-semibold text-indigo-600 transition-all hover:bg-gray-100"
            >
                Schreib uns eine E-Mail
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 py-6 text-gray-400">
        <div class="container mx-auto flex justify-between px-6">
            <p>&copy; 2025 Kegelmaster. Alle Rechte vorbehalten.</p>
            <a href="#privacy" class="hover:underline">Datenschutz</a>
        </div>
    </footer>
</template>
