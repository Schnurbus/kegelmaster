<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import type { NavItem, SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { CircleUserRound, Club, LayoutGrid } from 'lucide-vue-next';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import AppLogo from './AppLogo.vue';
import ClubSelector from './ClubSelector.vue';

const page = usePage<SharedData>();

const { t } = useI18n();

const mainNavItems = computed(() => [
    {
        title: t('Dashboard'),
        href: '/dashboard',
        icon: LayoutGrid,
    },
    // {
    //     title: t('Club', 2),
    //     href: '/club',
    //     icon: Club,
    // },
    {
        title: t('Player', 2),
        href: '/players',
        icon: CircleUserRound,
    },
    {
        title: t('Matchday', 2),
        href: route('matchdays.index'),
        icon: CircleUserRound,
    },
    {
        title: t('Transaction', 2),
        href: route('transactions.index'),
        icon: CircleUserRound,
    },
]);

const settingsNavItems = computed(() => [
    {
        title: t('Role', 2),
        href: '/role',
        icon: CircleUserRound,
    },
    {
        title: t('Fee Type', 2),
        href: route('fee-type.index'),
        icon: CircleUserRound,
    },
    {
        title: t('Competition Type', 2),
        href: route('competition-type.index'),
        icon: CircleUserRound,
    },
]);

const footerNavItems: NavItem[] = [
    // {
    //     title: 'Github Repo',
    //     href: 'https://github.com/laravel/vue-starter-kit',
    //     icon: Folder,
    // },
    // {
    //     title: 'Documentation',
    //     href: 'https://laravel.com/docs/starter-kits',
    //     icon: BookOpen,
    // },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <SidebarGroup class="px-2 py-0">
                <SidebarGroupLabel>{{ t('Club', 2) }}</SidebarGroupLabel>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <ClubSelector
                            v-show="page.props.userClubs?.length"
                            :clubs="page.props.clubs || []"
                            :currentClub="page.props.currentClub || ''"
                        />
                        <SidebarMenuButton as-child :is-active="route('club.index') == page.url" class="mt-2">
                            <Link :href="route('club.index')">
                                <Club />
                                <span>{{ t('Club', 2) }}</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroup>

            <NavMain v-if="page.props.userClubs?.length" :items="mainNavItems" :label="t('Club Menu')" />
            <NavMain v-if="page.props.userClubs?.length" :items="settingsNavItems" :label="t('Settings Menu')" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
