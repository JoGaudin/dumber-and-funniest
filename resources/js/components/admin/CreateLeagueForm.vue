<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
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
import { ref } from 'vue';

interface User {
    id: number;
    name: string;
}

const props = defineProps<{
    users: User[];
}>();

const isOpen = ref(false);

const form = ref({
    name: '',
    selectedUsers: [] as number[],
});

const submit = () => {
    console.log('Submitting league:', form.value);
    // Here you would typically emit an event or make an API call
    isOpen.value = false;
};
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger as-child>
            <slot />
        </DialogTrigger>
        <DialogContent class="max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Add League</DialogTitle>
                <DialogDescription>
                    Create a new league and add users to it.
                </DialogDescription>
            </DialogHeader>
            <div class="grid gap-4 py-4">
                <div class="grid grid-cols-4 items-center gap-4">
                    <Label for="name" class="text-right">
                        Name <span class="text-red-500">*</span>
                    </Label>
                    <Input id="name" v-model="form.name" class="col-span-3" />
                </div>
                <div class="grid grid-cols-4 items-start gap-4">
                    <Label class="text-right pt-2">
                        Users
                    </Label>
                    <div class="col-span-3 border rounded-md p-2 h-40 overflow-y-auto space-y-2">
                        <div v-for="user in users" :key="user.id" class="flex items-center space-x-2">
                            <Checkbox 
                                :id="`user-${user.id}`" 
                                :checked="form.selectedUsers.includes(user.id)"
                                @update:checked="(checked) => {
                                    if (checked) {
                                        form.selectedUsers.push(user.id);
                                    } else {
                                        form.selectedUsers = form.selectedUsers.filter(id => id !== user.id);
                                    }
                                }"
                            />
                            <Label :for="`user-${user.id}`" class="text-sm font-normal cursor-pointer">
                                {{ user.name }}
                            </Label>
                        </div>
                    </div>
                </div>
            </div>
            <DialogFooter>
                <Button type="submit" @click="submit">Save changes</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
