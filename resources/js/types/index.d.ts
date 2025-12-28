import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
    adminOnly?: boolean;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface CommentType {
    comment_type_id: number;
    name: string;
    emoji: string;
}

export interface Comment {
    comment_id: number;
    user_id: number;
    league_id: number;
    comment_type_id: number;
    comment: string;
    validated_nb: number;
    revocation_nb: number;
    created_at: string;
    user: User;
    submitter: User;
    type: CommentType;
    user_answer: 'validation' | 'revocation' | null;
}

export interface Ranking {
    user_id: number;
    name: string;
    avatar?: string;
    scores: Record<number, number>;
}

export interface League {
    league_id: number;
    name: string;
    description?: string;
    logo?: string;
    created_at: string;
    updated_at: string;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    role: 'admin' | 'user';
    leagues?: League[];
}

export type BreadcrumbItemType = BreadcrumbItem;
