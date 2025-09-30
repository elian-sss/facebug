<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import PostCard from '@/components/PostCard.vue';
import Pagination from '@/components/Pagination.vue'; // 1. Importe o novo componente

const props = defineProps({
    user: Object,
    posts: Object, // A prop 'posts' continua sendo um objeto de paginação
    isFollowed: Boolean
});

const formatJoinDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('pt-BR', {
        month: 'long',
        year: 'numeric'
    });
};

// A lógica de seguir/deixar de seguir permanece a mesma
const follow = () => {
    router.post(`/users/${props.user.id}/follow`, {}, {
        preserveScroll: true,
    });
};

const unfollow = () => {
    router.delete(`/users/${props.user.id}/unfollow`, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="'Perfil de ' + user.name" />
    <AppLayout>
        <div class="flex h-full flex-1 flex-col p-4">
            <div class="mb-8 rounded-xl border bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ (user as any).name }}</h1>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Membro desde {{ formatJoinDate((user as any).created_at) }}
                        </p>
                    </div>
                    <div v-if="(user as any).id !== $page.props.auth.user.id">
                        <button v-if="isFollowed" @click="unfollow" class="rounded-lg bg-gray-200 px-4 py-2 font-semibold text-gray-800 transition hover:bg-gray-300 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">
                            Deixar de Seguir
                        </button>
                        <button v-else @click="follow" class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white transition hover:bg-blue-700">
                            Seguir
                        </button>

                        <Link
                            :href="`/chat/with/${(user as any).id}`"
                            method="post"
                            as="button"
                            class="ml-2 rounded-lg bg-green-600 px-4 py-2 font-semibold text-white transition hover:bg-green-700"
                        >
                            Mensagem
                        </Link>
                    </div>
                </div>

                <div class="mt-4 flex space-x-6 text-sm text-gray-600 dark:text-gray-300">
                    <span><span class="font-bold">{{ (user as any).posts_count }}</span> Posts</span>
                    <span><span class="font-bold">{{ (user as any).followers_count }}</span> Seguidores</span>
                    <span><span class="font-bold">{{ (user as any).following_count }}</span> Seguindo</span>
                </div>
            </div>

            <h2 class="mb-4 text-xl font-bold text-gray-800 dark:text-gray-200">Posts de {{ (user as any).name }}</h2>

            <div v-if="posts.data.length > 0">
                <PostCard
                    v-for="post in posts.data"
                    :key="(post as any).id"
                    :post="post"
                />
            </div>

            <div v-else class="text-center text-gray-500 dark:text-gray-400 py-10">
                <p>{{ (user as any).name }} ainda não fez nenhuma publicação.</p>
            </div>

            <Pagination :links="posts.links" class="mt-6" />

        </div>
    </AppLayout>
</template>
