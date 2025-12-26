<script setup lang="ts">
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import { type League, type User } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Trophy, Users } from 'lucide-vue-next';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';

interface Props {
    league: League & { users: User[] };
}

const props = defineProps<Props>();

const breadcrumbs = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: props.league.name,
        href: `/league/${props.league.league_id}`,
    },
];
</script>

<template>
    <Head :title="league.name" />

    <AppSidebarLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-4 p-4">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                    <Trophy class="h-6 w-6 text-primary" />
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">{{ league.name }}</h1>
                    <p class="text-muted-foreground">Manage and view league details</p>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Users</CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ league.users.length }}</div>
                    </CardContent>
                </Card>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Members</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="space-y-4">
                        <div v-for="user in league.users" :key="user.id" class="flex items-center gap-4">
                            <Avatar>
                                <AvatarImage :src="user.avatar" :alt="user.name" />
                                <AvatarFallback>{{ user.name.substring(0, 2).toUpperCase() }}</AvatarFallback>
                            </Avatar>
                            <div class="flex-1 space-y-1">
                                <p class="text-sm font-medium leading-none">{{ user.name }}</p>
                                <p class="text-sm text-muted-foreground">{{ user.email }}</p>
                            </div>
                            <div class="text-sm font-medium capitalize text-muted-foreground">
                                {{ user.role }}
                            </div>
                        </div>
                        <div v-if="league.users.length === 0" class="text-center py-4 text-muted-foreground">
                            No members in this league yet.
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppSidebarLayout>
</template>
