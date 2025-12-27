<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem, type League } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Trophy } from 'lucide-vue-next';

interface Props {
    leagues: League[];
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-4 p-4">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <Link 
                    v-for="league in leagues" 
                    :key="league.league_id" 
                    :href="`/league/${league.league_id}`"
                    class="transition-transform hover:scale-[1.02]"
                >
                    <Card class="h-full overflow-hidden">
                        <div class="aspect-video w-full bg-muted flex items-center justify-center overflow-hidden">
                            <img 
                                v-if="league.logo" 
                                :src="league.logo" 
                                :alt="league.name" 
                                class="h-full w-full object-cover"
                            />
                            <Trophy v-else class="h-12 w-12 text-muted-foreground/50" />
                        </div>
                        <CardHeader>
                            <CardTitle>{{ league.name }}</CardTitle>
                            <CardDescription v-if="league.description" class="line-clamp-2">
                                {{ league.description }}
                            </CardDescription>
                        </CardHeader>
                    </Card>
                </Link>
            </div>

            <div v-if="leagues.length === 0" class="flex flex-1 items-center justify-center rounded-lg border border-dashed p-8 text-center">
                <div class="flex flex-col items-center gap-2">
                    <Trophy class="h-12 w-12 text-muted-foreground/50" />
                    <h3 class="text-lg font-semibold">No leagues yet</h3>
                    <p class="text-sm text-muted-foreground">
                        You haven't joined any leagues yet.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>