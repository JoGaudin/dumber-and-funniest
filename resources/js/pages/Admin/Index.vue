<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { ref } from 'vue';

const breadcrumbs = [
    {
        title: 'Admin',
        href: '/admin',
    },
];

const currentTab = ref('users');

interface User {
    id: number;
    name: string;
    email: string;
    role: string;
}

interface League {
    id: number;
    name: string;
    sport: string;
    teams: number;
}

interface Comment {
    id: number;
    user: string;
    content: string;
    date: string;
}

const users: User[] = [
    { id: 1, name: 'John Doe', email: 'john@example.com', role: 'Admin' },
    { id: 2, name: 'Jane Smith', email: 'jane@example.com', role: 'User' },
    { id: 3, name: 'Bob Johnson', email: 'bob@example.com', role: 'User' },
];

const leagues: League[] = [
    { id: 1, name: 'Premier League', sport: 'Soccer', teams: 20 },
    { id: 2, name: 'NBA', sport: 'Basketball', teams: 30 },
    { id: 3, name: 'NFL', sport: 'Football', teams: 32 },
];

const comments: Comment[] = [
    { id: 1, user: 'John Doe', content: 'Great match yesterday!', date: '2023-10-01' },
    { id: 2, user: 'Jane Smith', content: 'Who do you think will win?', date: '2023-10-02' },
    { id: 3, user: 'Bob Johnson', content: 'I disagree with the referee.', date: '2023-10-03' },
];
</script>

<template>
    <Head title="Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
            
            <Tabs v-model="currentTab" class="w-full">
                <TabsList class="flex w-full">
                    <TabsTrigger value="users" class="flex-1">Users</TabsTrigger>
                    <TabsTrigger value="leagues" class="flex-1">Leagues</TabsTrigger>
                    <TabsTrigger value="comments" class="flex-1">Comments</TabsTrigger>
                </TabsList>
                
                <TabsContent value="users">
                    <div class="rounded-md border mt-4">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="w-[100px]">ID</TableHead>
                                    <TableHead>Name</TableHead>
                                    <TableHead>Email</TableHead>
                                    <TableHead class="text-right">Role</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="user in users" :key="user.id">
                                    <TableCell class="font-medium">{{ user.id }}</TableCell>
                                    <TableCell>{{ user.name }}</TableCell>
                                    <TableCell>{{ user.email }}</TableCell>
                                    <TableCell class="text-right">{{ user.role }}</TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </TabsContent>
                
                <TabsContent value="leagues">
                    <div class="rounded-md border mt-4">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="w-[100px]">ID</TableHead>
                                    <TableHead>Name</TableHead>
                                    <TableHead>Sport</TableHead>
                                    <TableHead class="text-right">Teams</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="league in leagues" :key="league.id">
                                    <TableCell class="font-medium">{{ league.id }}</TableCell>
                                    <TableCell>{{ league.name }}</TableCell>
                                    <TableCell>{{ league.sport }}</TableCell>
                                    <TableCell class="text-right">{{ league.teams }}</TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </TabsContent>
                
                <TabsContent value="comments">
                    <div class="rounded-md border mt-4">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="w-[100px]">ID</TableHead>
                                    <TableHead>User</TableHead>
                                    <TableHead>Content</TableHead>
                                    <TableHead class="text-right">Date</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="comment in comments" :key="comment.id">
                                    <TableCell class="font-medium">{{ comment.id }}</TableCell>
                                    <TableCell>{{ comment.user }}</TableCell>
                                    <TableCell>{{ comment.content }}</TableCell>
                                    <TableCell class="text-right">{{ comment.date }}</TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </TabsContent>
            </Tabs>
        </div>
    </AppLayout>
</template>
