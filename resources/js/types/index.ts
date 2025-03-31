import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import { Component } from 'vue';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    flash: { toasts: Toast[] };
    name: string;
    auth: Auth;
    userClubs?: ClubItem[];
    currentClubId?: number;
    currentClubName?: string;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

export interface ClubItem {
    id: number;
    name: string;
}

export interface RoleItem {
    id: number;
    name: string;
}

export interface FilterItems {
    value: string;
    label: string;
    icon?: Component;
}

export interface Toast {
    id: string;
    type: string;
    message: string;
}

export interface LayoutItem {
    isResizable: boolean;
    x: number;
    y: number;
    w: number;
    h: number;
    id: string;
    component: string | Component;
    props: Record<string, any>;
}
