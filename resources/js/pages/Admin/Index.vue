<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
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
import { Button } from '@/components/ui/button';
import { Plus } from 'lucide-vue-next';
import CreateUserForm from '@/components/admin/CreateUserForm.vue';
import CreateLeagueForm from '@/components/admin/CreateLeagueForm.vue';
import EditLeagueForm from '@/components/admin/EditLeagueForm.vue';
import {
    TagsInput,
    TagsInputInput,
    TagsInputItem,
    TagsInputItemDelete,
    TagsInputItemText,
} from '@/components/ui/tags-input';
import { ref, computed } from 'vue';

interface User {
    id: number;
    name: string;
    email: string;
    role: string;
    password_set_at: string | null;
}

interface League {
    league_id: number;
    name: string;
    description: string;
    logo: string | null;
    users: User[];
}

interface Comment {
    id: number;
    user: string;
    content: string;
    date: string;
}

interface Props {
    users: User[];
    leagues: League[];
    comments: Comment[];
}

const props = defineProps<Props>();

const breadcrumbs = [
    {
        title: 'Admin',
        href: '/admin',
    },
];

const currentTab = ref('users');

const buttonLabel = computed(() => {
    switch (currentTab.value) {
        case 'users': return 'Add User';
        case 'leagues': return 'Add League';
        case 'comments': return 'Add Comment';
        default: return 'Add';
    }
});
const sendInvitation = (userId: number) => {
    console.log(`Sending invitation to user ID: ${userId}`);
    router.post(`/admin/users/${userId}/invite`, {}, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Admin Dashboard</h1>
                
                <CreateUserForm v-if="currentTab === 'users'">
                    <Button>
                        <Plus class="mr-2 h-4 w-4" /> {{ buttonLabel }}
                    </Button>
                </CreateUserForm>

                <CreateLeagueForm v-else-if="currentTab === 'leagues'" :users="users">
                    <Button>
                        <Plus class="mr-2 h-4 w-4" /> {{ buttonLabel }}
                    </Button>
                </CreateLeagueForm>

                <Button v-else disabled>
                    <Plus class="mr-2 h-4 w-4" /> {{ buttonLabel }}
                </Button>
            </div>
            
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
                                    <TableHead>Role</TableHead>
                                    <TableHead class="text-right">Action</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="user in users" :key="user.id">
                                    <TableCell class="font-medium">{{ user.id }}</TableCell>
                                    <TableCell>{{ user.name }}</TableCell>
                                    <TableCell>{{ user.email }}</TableCell>
                                    <TableCell>{{ user.role }}</TableCell>
                                    <TableCell class="text-right">
                                        <Button 
                                            v-if="!user.password_set_at" 
                                            size="sm" 
                                            variant="outline"
                                            class="cursor-pointer"
                                            @click="sendInvitation(user.id)"
                                        >
                                            Send Invitation
                                        </Button>
                                        <span v-else class="text-xs text-muted-foreground">
                                            Subscribed
                                        </span>
                                    </TableCell>
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
                                    <TableHead>Description</TableHead>
                                    <TableHead>Users</TableHead>
                                    <TableHead class="text-right">Action</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="league in leagues" :key="league.league_id">
                                    <TableCell class="font-medium">{{ league.league_id }}</TableCell>
                                    <TableCell>{{ league.name }}</TableCell>
                                    <TableCell class="max-w-xs truncate">{{ league.description }}</TableCell>
                                    <TableCell>
                                        <TagsInput :model-value="league.users.map(u => u.name)" disabled class="border-none p-0 shadow-none bg-transparent">
                                            <div class="flex flex-wrap gap-1">
                                                <TagsInputItem v-for="user in league.users" :key="user.id" :value="user.name" class="h-6 px-2 py-0">
                                                    <TagsInputItemText class="text-[10px]" />
                                                </TagsInputItem>
                                            </div>
                                        </TagsInput>
                                    </TableCell>
                                    <TableCell class="text-right">
                                        <EditLeagueForm :league="league" :users="users">
                                            <Button size="sm" variant="outline">Edit</Button>
                                        </EditLeagueForm>
                                    </TableCell>
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
