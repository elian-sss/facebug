<script setup lang="ts">
import { ref, computed } from 'vue'; // 1. Importe 'computed' from Vue
import { useForm, usePage } from '@inertiajs/vue3';
import CommentSection from '@/components/CommentSection.vue';

const props = defineProps({
    post: Object,
});

const page = usePage();
const isCommentsVisible = ref(false);
const authUser = (page.props as any).auth.user;

const isLikedByMe = computed(() => {
    if (!authUser || !(props.post as any).likes) {
        return false;
    }
    return (props.post as any).likes.some((like: any) => like.user_id === authUser.id);
});

const handleLike = () => {
    useForm({ id: props.post!.id }).post('/likes', { preserveScroll: true });
};

const handleDelete = () => {
    if (confirm('Tem certeza que deseja deletar este post?')) {
        useForm({}).delete(`/posts/${props.post!.id}`, { preserveScroll: true });
    }
};

const formatDateTime = (dateString: string) => {
    return new Date(dateString).toLocaleString('pt-BR', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <div class="mb-6 break-inside-avoid rounded-xl border bg-white p-5 shadow-lg dark:border-gray-700 dark:bg-gray-800">
        <div class="mb-4 flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500 text-white font-bold">
                {{ (post as any).user.name.charAt(0) }}
            </div>
            <div>
                <p class="font-bold text-gray-900 dark:text-white">{{ (post as any).user.name }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ formatDateTime((post as any).created_at) }}</p>
            </div>
        </div>

        <h2 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">{{ (post as any).title }}</h2>
        <p class="mb-4 text-gray-700 dark:text-gray-300">{{ (post as any).content }}</p>

        <div class="flex items-center gap-6 text-gray-600 dark:text-gray-400">
            <button
                @click="handleLike"
                class="flex items-center gap-1 transition"
                :class="isLikedByMe ? 'text-red-500' : 'hover:text-red-500'"
            >
                <i class='bx text-xl' :class="isLikedByMe ? 'bxs-heart' : 'bx-heart'"></i>
                <span>{{ (post as any).likes.length }}</span>
            </button>

            <button @click="isCommentsVisible = !isCommentsVisible" class="flex items-center gap-1 transition hover:text-green-500">
                <i class='bx bxs-comment text-xl'></i>
                <span>{{ (post as any).comments.length }}</span>
            </button>
            <button @click="handleDelete" v-if="(post as any).user_id === (page.props as any).auth.user.id" class="ml-auto flex items-center gap-1 transition hover:text-red-500">
                <i class='bx bx-trash text-xl'></i>
            </button>
        </div>

        <CommentSection
            v-if="isCommentsVisible"
            :post-id="(post as any).id"
            :comments="(post as any).comments"
        />
    </div>
</template>
