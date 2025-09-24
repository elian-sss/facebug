<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import PostCard from '@/components/PostCard.vue';
defineProps({
    posts: Array,
});

const form = useForm({
    title: '',
    content: '',
});

const submit = () => {
    form.post('/posts', {
        onSuccess: () => {
            form.reset('title', 'content');
        },
    });
};
</script>

<template>
    <Head title="Feed" />
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <form @submit.prevent="submit" class="mb-6 rounded-xl border bg-white p-5 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <h2 class="mb-4 text-xl font-bold dark:text-white">Crie um novo post</h2>
                <input
                    type="text"
                    v-model="form.title"
                    placeholder="Título"
                    class="mb-3 w-full resize-none rounded-lg border p-2 focus:ring focus:ring-blue-300 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
                />
                <textarea
                    v-model="form.content"
                    class="w-full resize-none rounded-lg border p-2 focus:ring focus:ring-blue-300 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
                    rows="3"
                    placeholder="O que você está pensando?"
                    required
                ></textarea>
                <button
                    type="submit"
                    class="mt-4 rounded-lg bg-blue-600 px-5 py-2 text-white font-semibold transition hover:bg-blue-700 disabled:opacity-50"
                    :disabled="form.processing"
                >
                    Postar
                </button>
            </form>

            <div v-if="posts && posts.length > 0">
                <PostCard
                    v-for="post in posts"
                    :key="(post as any).id"
                    :post="post"
                />
            </div>

            <div v-else class="text-center text-gray-500 dark:text-gray-400 py-10">
                <p class="text-xl">Nenhum post por aqui ainda.</p>
                <p>Seja o primeiro a compartilhar algo!</p>
            </div>
        </div>
    </AppLayout>
</template>
