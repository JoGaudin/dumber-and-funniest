<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { type League } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { Trophy } from 'lucide-vue-next';

defineProps<{
    leagues: League[];
}>();

const page = usePage();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Mes Ligues</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="league in leagues" :key="league.league_id">
                <SidebarMenuButton
                    as-child
                    :tooltip="league.name"
                >
                    <Link :href="`/league/${league.league_id}`">
                        <Trophy class="size-4" />
                        <span>{{ league.name }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
            <SidebarMenuItem v-if="leagues.length === 0">
                <div class="px-2 py-1.5 text-xs text-muted-foreground">
                    Pas encore de ligue...
                </div>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
