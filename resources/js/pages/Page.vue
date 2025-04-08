<script setup lang="ts">
import { Layers, Mail, Phone, MapPin } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { compile, createApp, onMounted, ref } from 'vue';

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const components = {
    Layers,
    Mail,
    Phone,
    MapPin,
    Link
};

const props = defineProps<{ content: string }>();

const app = createApp({
    template: props.content,
});

onMounted(() => {
    app.mount('#pageContent');
})
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-950 via-gray-900 to-gray-950 text-gray-100">
        <!-- Header -->
        <header class="border-b border-gray-800">
            <div class="container mx-auto flex items-center justify-between px-4 py-6">
                <Link href="/" class="flex items-center space-x-2">
                    <Layers class="h-8 w-8 text-indigo-500" />
                    <span class="text-xl font-bold">KegelMaster</span>
                </Link>

                <Link href="/">
                    <Button variant="outline" class="border-gray-700 text-gray-300 hover:bg-gray-800"> Zurück zur Startseite </Button>
                </Link>
            </div>
        </header>

        <!-- Content -->
        <main class="container mx-auto px-4 py-12 md:py-16">
            <div class="mx-auto max-w-3xl">
                <div id="pageContent">
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="mt-12 border-t border-gray-800 bg-gray-950 py-6">
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
