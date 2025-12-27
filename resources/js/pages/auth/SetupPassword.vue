<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { useForm, Head } from '@inertiajs/vue3';
import { type User } from '@/types';

const props = defineProps<{
    user: User;
}>();

const form = useForm({
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(window.location.href, {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase
        title="Set your password"
        description="Please choose a password to complete your registration"
    >
        <Head title="Set Password" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="password">New Password</Label>
                    <Input
                        id="password"
                        type="password"
                        v-model="form.password"
                        required
                        autofocus
                        autocomplete="new-password"
                        placeholder="Password"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm Password</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm Password"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button
                    type="submit"
                    class="mt-4 w-full"
                    :disabled="form.processing"
                >
                    <Spinner v-if="form.processing" />
                    Set Password
                </Button>
            </div>
        </form>
    </AuthBase>
</template>
