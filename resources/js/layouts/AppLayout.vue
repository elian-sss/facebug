<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { useToast } from 'vue-toastification';
import { onMounted, onUnmounted, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const toast = useToast();
const page = usePage();

const showBackToTopButton = ref(false);

watch(() => page.props.flash, (flash: any) => {
    if (flash?.success) {
        toast.success(flash.success);
    }
    if (flash?.error) {
        toast.error(flash.error);
    }
}, { deep: true });

const handleScroll = () => {
    if (window.scrollY > 300) { // Mostra o botão após rolar 300px
        showBackToTopButton.value = true;
    } else {
        showBackToTopButton.value = false;
    }
};

// 4. Função para rolar suavemente para o topo
const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
};

// 5. Adiciona e remove o "ouvinte" de scroll
onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const user = page.props.auth.user;

const userChannel = `App.Models.User.${user.id}`;

onMounted(() => {
    if (user) {
        window.Echo.private(userChannel)
            .notification((notification) => {
                console.log(notification);
                toast.info(notification.message);
            });
    }
});

onUnmounted(() => {
    if (user) {
        window.Echo.leave(userChannel);
    }
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />

        <transition name="fade">
            <button
                v-if="showBackToTopButton"
                @click="scrollToTop"
                class="fixed bottom-6 right-6 z-50 flex h-12 w-12 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg transition hover:bg-blue-700 focus:outline-none"
                aria-label="Voltar ao topo"
            >
                <i class='bx bx-up-arrow-alt text-2xl'></i>
            </button>
        </transition>
    </AppLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
