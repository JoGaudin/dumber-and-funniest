<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Download } from 'lucide-vue-next';

const isOpen = ref(false);
const deferredPrompt = ref<any>(null);

onMounted(() => {
    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent Chrome 67 and earlier from automatically showing the prompt
        e.preventDefault();
        // Stash the event so it can be triggered later.
        deferredPrompt.value = e;
        
        // Check if we already showed it in this session
        const hasShown = sessionStorage.getItem('pwa_prompt_shown');
        if (!hasShown) {
            isOpen.value = true;
        }
    });

    window.addEventListener('appinstalled', () => {
        // Log install to analytics
        console.log('PWA was installed');
        deferredPrompt.value = null;
        isOpen.value = false;
    });
});

const handleInstall = async () => {
    if (!deferredPrompt.value) return;
    
    // Show the prompt
    deferredPrompt.value.prompt();
    
    // Wait for the user to respond to the prompt
    const { outcome } = await deferredPrompt.value.userChoice;
    console.log(`User response to the install prompt: ${outcome}`);
    
    // We've used the prompt, and can't use it again, throw it away
    deferredPrompt.value = null;
    isOpen.value = false;
    sessionStorage.setItem('pwa_prompt_shown', 'true');
};

const handleClose = () => {
    isOpen.value = false;
    sessionStorage.setItem('pwa_prompt_shown', 'true');
};
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle class="flex items-center gap-2">
                    <Download class="h-5 w-5 text-primary" />
                    Install App
                </DialogTitle>
                <DialogDescription>
                    Add 'Dumber and Funniest' to your home screen for quick access and a better experience.
                </DialogDescription>
            </DialogHeader>
            <div class="flex justify-center py-6">
                <div class="h-24 w-24 rounded-2xl bg-primary/10 flex items-center justify-center">
                    <img src="/icon-192.png" alt="App Icon" class="h-20 w-20 rounded-xl shadow-lg" />
                </div>
            </div>
            <DialogFooter class="flex flex-col sm:flex-row gap-2">
                <Button variant="outline" @click="handleClose" class="sm:flex-1">
                    Maybe later
                </Button>
                <Button @click="handleInstall" class="sm:flex-1">
                    Install Now
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
