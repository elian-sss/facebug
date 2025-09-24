<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import PostCard from '@/components/PostCard.vue';

const props = defineProps({
    user: Object,
    posts: Object, // A prop 'posts' também é um objeto de paginação aqui
});

// A LÓGICA DE SCROLL INFINITO É EXATAMENTE A MESMA DO FEED!
const localPosts = ref(props.posts.data);
const nextPageUrl = ref(props.posts.next_page_url);
const loadingMore = ref(false);
const loadMoreIntersect = ref(null);

const loadMorePosts = () => {
    if (!nextPageUrl.value || loadingMore.value) return;
    loadingMore.value = true;

    router.get(nextPageUrl.value, {}, {
        preserveState: true,
        preserveScroll: true,
        only: ['posts'],
        onSuccess: (page) => {
            localPosts.value.push(...(page.props.posts as any).data);
            nextPageUrl.value = (page.props.posts as any).next_page_url;
            loadingMore.value = false;
        },
        onError: () => loadingMore.value = false
    });
}

let observer: IntersectionObserver;
onMounted(() => {
    observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) loadMorePosts();
    });
    if (loadMoreIntersect.value) observer.observe(loadMoreIntersect.value);
});

onUnmounted(() => {
    if (loadMoreIntersect.value) observer.unobserve(loadMoreIntersect.value);
});

const formatJoinDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('pt-BR', {
        month: 'long',
        year: 'numeric'
    });
};
</script>

<template>
    <Head :title="'Perfil de ' + user.name" />
    <AppLayout>
        <div class="flex h-full flex-1 flex-col p-4">
            <div class="mb-8 rounded-xl border bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ (user as any).name }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Membro desde {{ formatJoinDate((user as any).created_at) }}
                </p>
            </div>

            <h2 class="mb-4 text-xl font-bold text-gray-800 dark:text-gray-200">Posts de {{ (user as any).name }}</h2>

            <div v-if="localPosts && localPosts.length > 0">
                <PostCard
                    v-for="post in localPosts"
                    :key="(post as any).id"
                    :post="post"
                />
            </div>

            <div v-else class="text-center text-gray-500 dark:text-gray-400 py-10">
                <p>{{ (user as any).name }} ainda não fez nenhuma publicação.</p>
            </div>

            <div ref="loadMoreIntersect" class="h-10 text-center">
                <span v-if="loadingMore" class="text-gray-500">Carregando mais posts...</span>
            </div>
        </div>
    </AppLayout>
</template>
