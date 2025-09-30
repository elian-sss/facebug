<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Pagination from '@/components/Pagination.vue';

defineProps({
    notifications: Object,
});

// Função para extrair o ícone, cor, mensagem e URL com base no tipo de notificação
const getNotificationDetails = (notification) => {
    // Notificação de Curtida
    if (notification.type.includes('NewLikeNotification')) {
        return {
            icon: 'bx-heart',
            color: 'text-red-500',
            message: `<span class="font-bold">${notification.data.liker_name}</span> curtiu seu post: <span class="italic">"${notification.data.post_title}"</span>`,
            url: `/posts/${notification.data.post_id}`
        };
    }
    // Notificação de Comentário
    if (notification.type.includes('NewCommentNotification')) {
        return {
            icon: 'bx-comment-dots',
            color: 'text-green-500',
            message: `<span class="font-bold">${notification.data.commenter_name}</span> comentou no seu post: <span class="italic">"${notification.data.post_title}"</span>`,
            url: `/posts/${notification.data.post_id}`
        };
    }
    // Notificação de Seguidor
    if (notification.type.includes('NewFollowerNotification')) {
        return {
            icon: 'bx-user-plus',
            color: 'text-blue-500',
            message: `<span class="font-bold">${notification.data.follower_name}</span> começou a seguir você.`,
            url: `/users/${notification.data.follower_name}`
        };
    }
    // Notificação Padrão (fallback)
    return {
        icon: 'bx-bell',
        color: 'text-gray-500',
        message: 'Nova notificação.',
        url: '#'
    };
};
</script>

<template>
    <Head title="Minhas Notificações" />
    <AppLayout>
        <div class="p-4 md:p-6">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-6">Notificações</h1>

            <div v-if="notifications.data.length > 0" class="space-y-3">
                <div v-for="notification in notifications.data" :key="notification.id">
                    <Link :href="getNotificationDetails(notification).url" class="block rounded-lg bg-white p-4 shadow-sm transition hover:shadow-md dark:bg-gray-800 dark:hover:bg-gray-700">
                        <div class="flex items-center gap-4">
                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full" :class="getNotificationDetails(notification).color + ' bg-gray-100 dark:bg-gray-900'">
                                <i class="bx text-xl" :class="getNotificationDetails(notification).icon"></i>
                            </div>
                            <div class="flex-grow">
                                <p class="text-gray-800 dark:text-gray-200" v-html="getNotificationDetails(notification).message"></p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ new Date(notification.created_at).toLocaleString('pt-BR') }}
                                </p>
                            </div>
                        </div>
                    </Link>
                </div>

                <Pagination :links="notifications.links" class="mt-6" />
            </div>

            <div v-else class="text-center text-gray-500 py-16">
                <i class='bx bx-bell-off text-6xl text-gray-300 dark:text-gray-600'></i>
                <p class="mt-4 text-lg">Você não tem nenhuma notificação ainda.</p>
                <p class="text-sm text-gray-400">Quando alguém interagir com você, as notificações aparecerão aqui.</p>
            </div>
        </div>
    </AppLayout>
</template>
