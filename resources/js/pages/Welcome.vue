<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import { Construction } from 'lucide-vue-next';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="Bienvenue" />
    <div class="flex min-h-screen flex-col items-center justify-center bg-gradient-to-br from-primary/5 via-background to-primary/10 p-6">
        <header class="absolute top-0 right-0 p-6">
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="dashboard()"
                    class="inline-block rounded-lg border border-border px-5 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="login()"
                        class="inline-block rounded-lg px-5 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground"
                    >
                        Connexion
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="register()"
                        class="inline-block rounded-lg border border-border bg-primary px-5 py-2 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90"
                    >
                        Inscription
                    </Link>
                </template>
            </nav>
        </header>

        <main class="flex flex-col items-center justify-center text-center space-y-8 max-w-2xl">
            <div class="flex items-center justify-center w-24 h-24 rounded-full bg-primary/10">
                <Construction class="w-12 h-12 text-primary" />
            </div>
            
            <div class="space-y-4">
                <h1 class="text-4xl font-bold tracking-tight sm:text-5xl md:text-6xl">
                    Site en construction
                </h1>
                <p class="text-lg text-muted-foreground sm:text-xl">
                    Nous travaillons dur pour vous offrir une expérience exceptionnelle. 
                    Revenez bientôt pour découvrir notre application !
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <Link
                    v-if="!$page.props.auth.user"
                    :href="login()"
                    class="inline-flex items-center justify-center rounded-lg bg-primary px-8 py-3 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90"
                >
                    Se connecter
                </Link>
                <Link
                    v-if="!$page.props.auth.user && canRegister"
                    :href="register()"
                    class="inline-flex items-center justify-center rounded-lg border border-border px-8 py-3 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground"
                >
                    Créer un compte
                </Link>
                <Link
                    v-if="$page.props.auth.user"
                    :href="dashboard()"
                    class="inline-flex items-center justify-center rounded-lg bg-primary px-8 py-3 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90"
                >
                    Accéder au Dashboard
                </Link>
            </div>
        </main>

        <footer class="absolute bottom-0 left-0 right-0 p-6 text-center text-sm text-muted-foreground">
            <p>&copy; {{ new Date().getFullYear() }} Dumber and Funniest. Tous droits réservés.</p>
        </footer>
    </div>
</template>
