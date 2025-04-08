<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Link } from '@inertiajs/vue3';
import { Layers, Users, FileText, Calendar, BarChart4, PiggyBank, LayoutDashboard, ChevronRight, ArrowRight, Github } from 'lucide-vue-next';

// Navigation Items
const navItems = [
    { label: 'Features', href: '#features' },
    { label: 'FAQ', href: '#faq' },
    { label: 'Kontakt', href: '#contact' },
];

// Features mit Details
const features = [
    {
        id: 'users',
        icon: Users,
        title: 'Mitgliederverwaltung',
        description:
            'Verwalte deine Vereinsmitglieder mit individuellen rollenbasierten Berechtigungen. Definiere genau, welche Rollen welche Aktionen ausführen dürfen.',
        screenshot: '/images/landing/members-management.png',
        points: [
            'Flexible Rollendefinition mit detaillierten Berechtigungen',
            // 'Automatische Benachrichtigungen bei wichtigen Ereignissen',
            'Übersichtliche Mitgliederlisten mit fortgeschrittenen Filterfunktionen',
        ],
    },
    {
        id: 'catalog',
        icon: FileText,
        title: 'Strafen & Wettbewerbe',
        description: 'Erstelle einen individuellen Katalog für Strafen und Wettbewerbe. Halte alle Finanzen nachvollziehbar und fair.',
        screenshot: '/images/landing/catalog-management.png',
        points: [
            'Individuelle Strafen und Wettbewerbe erstellen und verwalten',
            'Automatische Abrechnung und Zuweisung zu Mitgliedern',
            'Transparente Übersicht aller Zahlungen und Außenstände',
        ],
    },
    {
        id: 'matchdays',
        icon: Calendar,
        title: 'Spieltagserfassung',
        description: 'Dokumentiere unkompliziert alle Spieltage, Anwesenheiten und besonderen Ereignisse in einem intuitiven Interface.',
        screenshot: '/images/landing/matchday-tracking.png',
        points: [
            'Einfache Anwesenheitserfassung per Klick',
            'Spielergebnisse und besondere Vorkommnisse dokumentieren',
            'Verknüpfung mit dem Strafen- und Wettbewerbskatalog',
        ],
    },
    {
        id: 'accounting',
        icon: PiggyBank,
        title: 'Kassenbuch',
        description: 'Behalte den Überblick über alle Finanzen deines Vereins mit unserem digitalen Kassenbuch-System.',
        screenshot: '/images/landing/accounting-system.png',
        points: [
            'Automatische Buchungen bei Strafen und Wettbewerben',
            'Einfache Ein- und Auszahlungsverwaltung',
            // 'Export für Steuer und Buchhaltung mit einem Klick'
        ],
    },
    {
        id: 'statistics',
        icon: BarChart4,
        title: 'Statistiken',
        description: 'Visualisiere wichtige Trends und Entwicklungen deines Vereins mit aussagekräftigen Grafiken und Auswertungen.',
        screenshot: '/images/landing/statistics-view.png',
        points: [
            'Umfassende Teilnahmestatistiken und Trendanalysen',
            'Finanzieller Überblick mit automatisch generierten Berichten',
            'Personalisierte Auswertungen für jeden Spieler',
        ],
    },
    {
        id: 'dashboard',
        icon: LayoutDashboard,
        title: 'Anpassbares Dashboard',
        description: 'Gestalte dein Dashboard genau nach deinen Bedürfnissen mit den wichtigsten Informationen auf einen Blick.',
        screenshot: '/images/landing/custom-dashboard.png',
        points: [
            'Drag & Drop Interface zur einfachen Personalisierung',
            'Verschiedene Widget-Typen für unterschiedliche Informationen',
            'Individuelle Einstellungen für jedes Mitglied',
        ],
    },
];

// Animation für Features
const visibleFeatures = ref<string[]>([]);

const isVisible = (id: string) => visibleFeatures.value.includes(id);

onMounted(() => {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    if (id && !visibleFeatures.value.includes(id)) {
                        visibleFeatures.value.push(id);
                    }
                }
            });
        },
        { threshold: 0.1 },
    );

    features.forEach((feature) => {
        const element = document.getElementById(feature.id);
        if (element) observer.observe(element);
    });
});
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

            <!-- Hero Content -->
            <div class="container mx-auto flex flex-col items-center px-4 py-20 text-center md:py-32">
                <h1 class="mb-6 bg-gradient-to-r from-indigo-500 to-purple-600 bg-clip-text text-4xl font-extrabold text-transparent md:text-6xl">
                    Vereinsmanagement neu definiert
                </h1>
                <p class="mb-10 max-w-3xl text-xl text-gray-300 md:text-2xl">
                    Die All-in-One Lösung für die digitale Verwaltung deines Sport- oder Freizeitvereins. Einfach. Effizient. Modern.
                </p>
                <div class="flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4">
                    <Button size="lg" class="bg-indigo-600 text-white hover:bg-indigo-700">
                        Demo starten
                        <ArrowRight class="ml-2 h-5 w-5" />
                    </Button>
                    <Button size="lg" variant="outline" class="border-gray-700 text-gray-300 hover:bg-gray-800">
                        Mehr erfahren
                        <ChevronRight class="ml-2 h-5 w-5" />
                    </Button>
                </div>
            </div>

            <!-- Hero Background Elements -->
            <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
                <div class="absolute top-0 right-0 h-1/3 w-1/3 rounded-full bg-indigo-500/10 blur-3xl filter"></div>
                <div class="absolute bottom-0 left-0 h-1/2 w-1/2 rounded-full bg-purple-500/10 blur-3xl filter"></div>
            </div>
        </header>

        <!-- Features Section -->
        <section id="features" class="bg-black/20 py-20 md:py-32">
            <div class="container mx-auto px-4">
                <!-- Section Header -->
                <div class="mb-20 text-center">
                    <h2 class="mb-6 text-3xl font-bold md:text-5xl">Alle Features für deine Vereinsverwaltung</h2>
                    <p class="mx-auto max-w-3xl text-xl text-gray-400">
                        Entdecke die zahlreichen Möglichkeiten unserer Plattform, die deine Vereinsarbeit auf das nächste Level hebt.
                    </p>
                </div>

                <!-- Features List -->
                <div class="space-y-32 md:space-y-48">
                    <div
                        v-for="(feature, index) in features"
                        :key="feature.id"
                        :id="feature.id"
                        :class="['grid grid-cols-1 items-center gap-12 lg:grid-cols-2', index % 2 === 1 ? 'lg:grid-flow-col-dense' : '']"
                    >
                        <!-- Feature Text Content -->
                        <div
                            :class="[
                                'space-y-6 transition-all duration-1000',
                                isVisible(feature.id) ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0',
                            ]"
                        >
                            <div :class="`inline-flex items-center justify-center rounded-lg bg-indigo-950 p-2 text-indigo-400`">
                                <component :is="feature.icon" class="h-6 w-6" />
                            </div>

                            <h3 class="text-2xl font-bold md:text-3xl">{{ feature.title }}</h3>

                            <p class="text-lg text-gray-400">
                                {{ feature.description }}
                            </p>

                            <ul class="space-y-3">
                                <li v-for="(point, i) in feature.points" :key="i" class="flex items-start">
                                    <div class="mt-1 mr-3 rounded-full bg-indigo-500/20 p-0.5 text-indigo-400">
                                        <ChevronRight class="h-4 w-4" />
                                    </div>
                                    <span>{{ point }}</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Feature Screenshot -->
                        <div
                            :class="[
                                'overflow-hidden rounded-xl border-2 border-gray-800 shadow-2xl transition-all delay-200 duration-1000',
                                index % 2 === 1 ? 'lg:col-start-1' : '',
                                isVisible(feature.id) ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0',
                            ]"
                        >
                            <Card class="border-gray-800 bg-gray-900">
                                <CardHeader class="border-b border-gray-700 bg-gray-800/50">
                                    <div class="flex space-x-2">
                                        <div class="h-3 w-3 rounded-full bg-red-500"></div>
                                        <div class="h-3 w-3 rounded-full bg-yellow-500"></div>
                                        <div class="h-3 w-3 rounded-full bg-green-500"></div>
                                    </div>
                                </CardHeader>
                                <CardContent class="p-0">
                                    <img :src="feature.screenshot" :alt="`Screenshot: ${feature.title}`" class="w-full object-cover" />
                                </CardContent>
                            </Card>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="relative overflow-hidden py-20 md:py-32">
            <div class="relative z-10 container mx-auto px-4">
                <div class="rounded-3xl bg-gradient-to-br from-indigo-900 to-purple-900 p-8 text-center md:p-16">
                    <h2 class="mb-6 text-3xl font-bold md:text-4xl">Bereit für den nächsten Schritt in der Vereinsverwaltung?</h2>
                    <p class="mx-auto mb-10 max-w-3xl text-xl text-indigo-200">
                        Starte jetzt und erlebe, wie einfach die digitale Verwaltung deines Vereins sein kann.
                    </p>
                    <div class="flex flex-col justify-center space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4">
                        <Link href="/login">
                            <Button size="lg" class="bg-white text-indigo-900 hover:bg-gray-100">
                                Kostenlos testen
                                <ArrowRight class="ml-2 h-5 w-5" />
                            </Button>
                        </Link>
                        <Button size="lg" variant="outline" class="hidden border-indigo-300 text-white hover:bg-indigo-800"> Demo anschauen </Button>
                    </div>
                </div>
            </div>

            <!-- Background Glow -->
            <div class="absolute inset-0 -z-10">
                <div
                    class="absolute top-1/2 left-1/2 h-2/3 w-2/3 -translate-x-1/2 -translate-y-1/2 rounded-full bg-indigo-500/20 blur-3xl filter"
                ></div>
            </div>
        </section>

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
.shadow-glow {
    box-shadow: 0 0 30px rgba(99, 102, 241, 0.3);
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
