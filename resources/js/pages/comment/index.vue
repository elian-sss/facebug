<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

// Recebe o post e seus comentários do back-end
const props = defineProps({
    post: Object,
});

const form = useForm({
    content: '',
    post_id: props.post.id,
});

const submitComment = () => {
    form.post('/comments', {
        onSuccess: () => {
            form.reset('content');
        },
    });
};
</script>

<template>
    <Head title="Comments" />
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">

            <!-- Card do post original -->
            <div class="mb-6 rounded-lg border bg-white p-4 shadow-sm dark:bg-gray-800">
                <h2 class="text-xl font-bold mb-2">{{ post.title }}</h2>
                <p class="mb-4">{{ post.content }}</p>
                <p class="text-sm text-gray-500">Posted by **{{ post.user.name }}** on {{ new Date(post.created_at).toLocaleDateString() }}</p>
            </div>

            <!-- Formulário para adicionar um comentário -->
            <form @submit.prevent="submitComment" class="mb-6 rounded-lg border bg-white p-4 shadow-sm dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-bold">Adicione um comentário</h3>
                <textarea
                    v-model="form.content"
                    class="w-full resize-none rounded-lg border p-2 focus:ring focus:ring-blue-200 focus:outline-none dark:border-gray-600 dark:bg-gray-700"
                    rows="2"
                    placeholder="Escreva seu comentário aqui..."
                    required
                ></textarea>
                <button
                    type="submit"
                    class="mt-4 rounded-lg bg-green-500 px-4 py-2 text-white hover:bg-green-600 disabled:bg-green-300 dark:disabled:bg-green-700"
                    :disabled="form.processing"
                >
                    Comentar
                </button>
            </form>

            <!-- Lista de comentários -->
            <h3 class="mb-4 text-xl font-bold">Comentários ({{ post.comments.length }})</h3>
            <div v-if="post.comments.length > 0">
                <div v-for="comment in post.comments" :key="comment.id" class="mb-4 rounded-lg border bg-gray-100 p-3 shadow-sm dark:bg-gray-700">
                    <p class="font-semibold">{{ comment.user.name }}</p>
                    <p class="mt-1">{{ comment.content }}</p>
                    <p class="mt-2 text-xs text-gray-500">{{ new Date(comment.created_at).toLocaleString() }}</p>
                </div>
            </div>
            <div v-else class="text-center text-gray-500">
                Nenhum comentário ainda. Seja o primeiro a comentar!
            </div>
        </div>
    </AppLayout>
</template>
