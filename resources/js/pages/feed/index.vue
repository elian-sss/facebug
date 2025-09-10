<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    posts: Array
})

const form = useForm({
    title:'',
    content: ''
});

const submit = () => {
    form.post('/posts', {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Feed" />
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <form @submit.prevent="submit" class="mb-6 p-4 border rounded-lg shadow-sm bg-white dark:bg-gray-800">
                <h2 class="text-xl font-bold mb-4">Crie um novo post</h2>
                <input
                    type="text"
                    v-model="form.title"
                    placeholder="Título"
                    class="w-full p-2 border rounded-lg resize-none focus:outline-none focus:ring focus:ring-blue-200 dark:bg-gray-700 dark:border-gray-600 mb-3"
                />
                <textarea
                    v-model="form.content"
                    class="w-full p-2 border rounded-lg resize-none focus:outline-none focus:ring focus:ring-blue-200 dark:bg-gray-700 dark:border-gray-600"
                    rows="3"
                    placeholder="Conteúdo"
                    required
                ></textarea>
                <button
                    type="submit"
                    class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 disabled:bg-blue-300 dark:disabled:bg-blue-700"
                    :disabled="form.processing"
                >
                    Post
                </button>
            </form>

            <div v-if="posts.length > 0">
                <div v-for="post in posts" :key="post.id" class="mb-6 p-4 border rounded-lg shadow-sm bg-white dark:bg-gray-800">
                    <p class="mb-4">{{ post.content }}</p>
                    <p class="text-sm text-gray-500">Posted by **{{ post.user.name }}** on {{ new Date(post.created_at).toLocaleDateString() }}</p>
                    <div class="mt-4">
                        <button class="text-blue-500 hover:underline mr-4">Like ({{ post.likes.length }})</button>
                        <button class="text-green-500 hover:underline">Comment ({{ post.comments.length }})</button>
                    </div>
                </div>
            </div>

            <div v-else class="text-center text-gray-500">
                Nenhum post registrado.
            </div>
        </div>
    </AppLayout>
</template>
