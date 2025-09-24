<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

defineProps({
    postId: Number,
    comments: Array,
});

const commentForm = useForm({
    content: '',
    post_id: 0
});

const submitComment = (postId: number) => {
    commentForm.post_id = postId;
    commentForm.post('/comments', {
        preserveScroll: true,
        onSuccess: () => {
            commentForm.reset();
        },
    });
};

const formatDateTime = (dateString: string) => {
    return new Date(dateString).toLocaleString('pt-BR', {
        dateStyle: 'short',
        timeStyle: 'short'
    });
};
</script>

<template>
    <div class="mt-6 border-t pt-4 dark:border-gray-700">
        <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Comentários</h3>

        <form @submit.prevent="submitComment(postId!)" class="mb-6">
            <textarea
                v-model="commentForm.content"
                class="w-full resize-none rounded-lg border p-2 focus:ring focus:ring-green-300 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
                rows="2"
                placeholder="Adicione seu comentário..."
                required
            ></textarea>
            <button
                type="submit"
                class="mt-2 rounded-lg bg-green-600 px-4 py-2 text-white transition hover:bg-green-700 disabled:opacity-50"
                :disabled="commentForm.processing"
            >
                Comentar
            </button>
        </form>

        <div v-if="comments && comments.length > 0" class="space-y-4">
            <div v-for="comment in comments" :key="(comment as any).id" class="rounded-lg bg-gray-100 p-3 dark:bg-gray-700">
                <p class="font-semibold text-gray-900 dark:text-white">{{ (comment as any).user.name }}</p>
                <p class="mt-1 text-gray-700 dark:text-gray-300">{{ (comment as any).content }}</p>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ formatDateTime((comment as any).created_at) }}</p>
            </div>
        </div>
        <div v-else class="text-center text-gray-500 dark:text-gray-400 py-4">
            Nenhum comentário ainda. Seja o primeiro a comentar!
        </div>
    </div>
</template>
