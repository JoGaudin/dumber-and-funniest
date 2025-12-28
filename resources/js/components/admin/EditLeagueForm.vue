<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    TagsInput,
    TagsInputInput,
    TagsInputItem,
    TagsInputItemDelete,
    TagsInputItemText,
} from '@/components/ui/tags-input';
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Spinner } from '@/components/ui/spinner';

interface User {
    id: number;
    name: string;
}

interface League {
    league_id: number;
    name: string;
    description: string;
    logo: string | null;
    users: User[];
}

const props = defineProps<{
    league: League;
    users: User[];
}>();

const isOpen = ref(false);

const form = useForm({
    name: props.league.name,
    description: props.league.description,
    logo: null as File | null,
    users: props.league.users.map(u => u.id),
    _method: 'POST',
});

const submit = () => {
    form.post(`/admin/leagues/${props.league.league_id}`, {
        onSuccess: () => {
            isOpen.value = false;
        },
    });
};

const handleLogoChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        form.logo = target.files[0];
    }
};

const availableUsers = computed(() => {
    return props.users.filter(u => !form.users.includes(u.id));
});

const addUser = (userId: any) => {
    const id = parseInt(userId);
    if (!form.users.includes(id)) {
        form.users.push(id);
    }
};

const removeUser = (userId: number) => {
    form.users = form.users.filter(id => id !== userId);
};

const getUserName = (userId: number) => {
    return props.users.find(u => u.id === userId)?.name || 'Unknown';
};
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger as-child>
            <slot />
        </DialogTrigger>
        <DialogContent class="max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Edit League</DialogTitle>
                <DialogDescription>
                    Update league details and manage members.
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="submit" class="grid gap-4 py-4">
                <div class="grid gap-2">
                    <Label for="edit-name">
                        Name <span class="text-red-500">*</span>
                    </Label>
                    <Input id="edit-name" v-model="form.name" required />
                    <div v-if="form.errors.name" class="text-xs text-red-500">{{ form.errors.name }}</div>
                </div>
                <div class="grid gap-2">
                    <Label for="edit-description">
                        Description <span class="text-red-500">*</span>
                    </Label>
                    <Textarea id="edit-description" v-model="form.description" required />
                    <div v-if="form.errors.description" class="text-xs text-red-500">{{ form.errors.description }}</div>
                </div>
                <div class="grid gap-2">
                    <Label for="edit-logo">Logo</Label>
                    <div v-if="league.logo" class="mb-2">
                        <img :src="`/storage/${league.logo}`" alt="Current Logo" class="h-12 w-12 object-cover rounded" />
                    </div>
                    <Input id="edit-logo" type="file" @change="handleLogoChange" accept="image/*" />
                    <div v-if="form.errors.logo" class="text-xs text-red-500">{{ form.errors.logo }}</div>
                </div>
                <div class="grid gap-2">
                    <Label>Members</Label>
                    
                    <div class="flex flex-col gap-2">
                        <Select @update:model-value="addUser">
                            <SelectTrigger>
                                <SelectValue placeholder="Add a user..." />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="user in availableUsers" :key="user.id" :value="user.id.toString()">
                                    {{ user.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>

                        <TagsInput :model-value="form.users.map(id => getUserName(id))" class="min-h-[80px] p-2">
                            <div class="flex flex-wrap gap-2">
                                <TagsInputItem v-for="userId in form.users" :key="userId" :value="getUserName(userId)">
                                    <TagsInputItemText />
                                    <TagsInputItemDelete @click="removeUser(userId)" />
                                </TagsInputItem>
                            </div>
                        </TagsInput>
                    </div>
                    
                    <div v-if="form.errors.users" class="text-xs text-red-500">{{ form.errors.users }}</div>
                </div>
                <DialogFooter>
                    <Button type="submit" :disabled="form.processing">
                        <Spinner v-if="form.processing" class="mr-2" />
                        Save changes
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
