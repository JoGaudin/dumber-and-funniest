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
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { store } from '@/routes/admin/users';
import { toast } from '@/components/ui/toast';

const isOpen = ref(false);

const form = useForm({
    name: '',
    email: '',
    role: '',
    picture: null as File | null,
});

const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        form.picture = target.files[0];
    }
};

const submit = () => {
    form.post(store().url, {
        onSuccess: () => {
            isOpen.value = false;
            form.reset();
            toast({
                title: 'Success',
                description: 'User created successfully.',
            });
        },
    });
};
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger as-child>
            <slot />
        </DialogTrigger>
        <DialogContent class="max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Add User</DialogTitle>
                <DialogDescription>
                    Create a new user account here. Click save when you're done.
                </DialogDescription>
            </DialogHeader>
            <div class="grid gap-4 py-4">
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="name" class="text-right">
                        Name <span class="text-red-500">*</span>
                    </Label>
                    <div class="col-span-3">
                        <Input id="name" v-model="form.name" />
                        <p v-if="form.errors.name" class="text-sm text-red-500 mt-1">{{ form.errors.name }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="email" class="text-right">
                        Email <span class="text-red-500">*</span>
                    </Label>
                    <div class="col-span-3">
                        <Input id="email" v-model="form.email" type="email" />
                        <p v-if="form.errors.email" class="text-sm text-red-500 mt-1">{{ form.errors.email }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="role" class="text-right">
                        Role <span class="text-red-500">*</span>
                    </Label>
                    <div class="col-span-3">
                        <Select v-model="form.role">
                            <SelectTrigger>
                                <SelectValue placeholder="Select a role" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="admin">Admin</SelectItem>
                                <SelectItem value="user">User</SelectItem>
                            </SelectContent>
                        </Select>
                        <p v-if="form.errors.role" class="text-sm text-red-500 mt-1">{{ form.errors.role }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="picture" class="text-right">
                        Picture
                    </Label>
                    <div class="col-span-3">
                        <Input id="picture" type="file" @change="handleFileChange" />
                        <p v-if="form.errors.picture" class="text-sm text-red-500 mt-1">{{ form.errors.picture }}</p>
                    </div>
                </div>
            </div>
            <DialogFooter>
                <Button type="submit" :disabled="form.processing" @click="submit">
                    {{ form.processing ? 'Saving...' : 'Save changes' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
