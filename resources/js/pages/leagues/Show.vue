<script setup lang="ts">
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import { type League, type User, type CommentType, type Ranking, type Comment } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Trophy, Users, MessageSquare, ArrowUpDown, ChevronUp, ChevronDown } from 'lucide-vue-next';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { computed } from 'vue';

interface Props {
    league: League;
    commentTypes: CommentType[];
    rankings: Ranking[];
    comments: Comment[];
    filters: {
        sort: string | number | null;
        direction: 'asc' | 'desc';
    };
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
const sortColumn = computed(() => props.filters.sort);
const sortDirection = computed(() => props.filters.direction);

const toggleSort = (column: number | 'name') => {
    const direction = sortColumn.value == column && sortDirection.value === 'desc' ? 'asc' : 'desc';
    
    router.get(
        `/league/${props.league.league_id}`,
        { sort: column, direction },
        { preserveState: true, preserveScroll: true }
    );
};
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
                    <p class="text-muted-foreground">{{ league.description || 'Manage and view league details' }}</p>
                </div>
            </div>

            <Tabs default-value="rankings" class="w-full">
                <TabsList class="grid w-full max-w-[400px] grid-cols-2">
                    <TabsTrigger value="rankings">
                        <Trophy class="mr-2 h-4 w-4" />
                        Rankings
                    </TabsTrigger>
                    <TabsTrigger value="comments">
                        <MessageSquare class="mr-2 h-4 w-4" />
                        Comments
                    </TabsTrigger>
                </TabsList>

                <TabsContent value="rankings" class="mt-4">
                    <Card>
                        <CardHeader>
                            <CardTitle>League Rankings</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead 
                                            class="cursor-pointer select-none hover:bg-muted/50 transition-colors"
                                            @click="toggleSort('name')"
                                        >
                                            <div class="flex items-center gap-2">
                                                User
                                                <component 
                                                    :is="sortColumn === 'name' ? (sortDirection === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown" 
                                                    class="h-4 w-4 text-muted-foreground"
                                                />
                                            </div>
                                        </TableHead>
                                        <TableHead 
                                            v-for="type in commentTypes" 
                                            :key="type.comment_type_id" 
                                            class="text-center cursor-pointer select-none hover:bg-muted/50 transition-colors"
                                            @click="toggleSort(type.comment_type_id)"
                                        >
                                            <div class="flex flex-col items-center gap-1">
                                                <div class="flex items-center gap-1">
                                                    <span>{{ type.emoji }}</span>
                                                    <component 
                                                        v-if="sortColumn == type.comment_type_id"
                                                        :is="sortDirection === 'asc' ? ChevronUp : ChevronDown" 
                                                        class="h-3 w-3 text-primary"
                                                    />
                                                    <ArrowUpDown v-else class="h-3 w-3 text-muted-foreground opacity-0 group-hover:opacity-100 transition-opacity" />
                                                </div>
                                                <span class="text-xs font-normal">{{ type.name }}</span>
                                            </div>
                                        </TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="ranking in rankings" :key="ranking.user_id">
                                        <TableCell class="font-medium">
                                            <div class="flex items-center gap-3">
                                                <Avatar class="h-8 w-8">
                                                    <AvatarImage :src="ranking.avatar" :alt="ranking.name || ''" />
                                                    <AvatarFallback>{{ (ranking.name || '').substring(0, 2).toUpperCase() }}</AvatarFallback>
                                                </Avatar>
                                                <span>{{ ranking.name }}</span>
                                            </div>
                                        </TableCell>
                                        <TableCell v-for="type in commentTypes" :key="type.comment_type_id" class="text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                <span :class="{ 'font-bold text-primary': (ranking.scores[type.comment_type_id] || 0) > 0 }">
                                                    {{ ranking.scores[type.comment_type_id] || 0 }}
                                                </span>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                    <TableRow v-if="rankings.length === 0">
                                        <TableCell :colspan="commentTypes.length + 1" class="text-center py-8 text-muted-foreground">
                                            No members in this league yet.
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </CardContent>
                    </Card>
                </TabsContent>

                <TabsContent value="comments" class="mt-4">
                    <Card>
                        <CardHeader>
                            <CardTitle>Recent Comments</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-6">
                                <div v-for="comment in comments" :key="comment.comment_id" class="flex gap-4">
                                    <Avatar class="h-10 w-10">
                                        <AvatarImage :src="comment.user.avatar" :alt="comment.user.name || ''" />
                                        <AvatarFallback>{{ (comment.user.name || '').substring(0, 2).toUpperCase() }}</AvatarFallback>
                                    </Avatar>
                                    <div class="flex-1 space-y-1">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold">{{ comment.user.name }}</span>
                                                <span class="text-xs px-2 py-0.5 rounded-full bg-primary/10 text-primary flex items-center gap-1">
                                                    {{ comment.type.emoji }} {{ comment.type.name }}
                                                </span>
                                            </div>
                                            <span class="text-xs text-muted-foreground">
                                                {{ new Date(comment.created_at).toLocaleDateString() }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-foreground leading-relaxed">
                                            {{ comment.comment }}
                                        </p>
                                        <div class="flex items-center gap-4 pt-2">
                                            <div class="flex items-center gap-1 text-xs text-green-600">
                                                <span class="font-bold">{{ comment.validated_nb }}</span> validations
                                            </div>
                                            <div class="flex items-center gap-1 text-xs text-red-600">
                                                <span class="font-bold">{{ comment.revocation_nb }}</span> revocations
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="comments.length === 0" class="text-center py-8 text-muted-foreground">
                                    No comments yet in this league.
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>
            </Tabs>
        </div>
    </AppSidebarLayout>
</template>
