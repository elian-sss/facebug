<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    posts: Array,
});

const form = useForm({
    id:'',
    title: '',
    content: '',
});

const submit = () => {
    form.post('/posts', {
        onSuccess: () => {
            form.reset();
        },
    });
};

const handleLike = (id) => {
    form.id = id;
    form.post(`/likes`);
};
</script>

<template>
    <Head title="Feed" />
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <form @submit.prevent="submit" class="mb-6 rounded-lg border bg-white p-4 shadow-sm dark:bg-gray-800">
                <h2 class="mb-4 text-xl font-bold">Crie um novo post</h2>
                <input
                    type="text"
                    v-model="form.title"
                    placeholder="Título"
                    class="mb-3 w-full resize-none rounded-lg border p-2 focus:ring focus:ring-blue-200 focus:outline-none dark:border-gray-600 dark:bg-gray-700"
                />
                <textarea
                    v-model="form.content"
                    class="w-full resize-none rounded-lg border p-2 focus:ring focus:ring-blue-200 focus:outline-none dark:border-gray-600 dark:bg-gray-700"
                    rows="3"
                    placeholder="Conteúdo"
                    required
                ></textarea>
                <button
                    type="submit"
                    class="mt-4 rounded-lg bg-blue-500 px-4 py-2 text-white hover:bg-blue-600 disabled:bg-blue-300 dark:disabled:bg-blue-700"
                    :disabled="form.processing"
                >
                    Post
                </button>
            </form>

            <div v-if="posts.length > 0">
                <div v-for="post in posts" :key="post.id" class="mb-6 rounded-lg border bg-white p-4 shadow-sm dark:bg-gray-800">
                    <p class="mb-4">{{ post.content }}</p>
                    <p class="text-sm text-gray-500">Posted by **{{ post.user.name }}** on {{ new Date(post.created_at).toLocaleDateString() }}</p>
                    <div class="mt-4">
                        <button @click="handleLike(post.id)" class="mr-4 text-blue-500 hover:underline">Like ({{ post.likes.length }})</button>
                        <button class="mr-4 text-green-500 hover:underline">Comment ({{ post.comments.length }})</button>
                        <button v-if="post.user_id === $page.props.auth.user.id" class="text-red-500 hover:underline">Delete</button>
                    </div>
                </div>
            </div>

            <div v-else class="text-center text-gray-500">Nenhum post registrado.</div>
        </div>
    </AppLayout>
</template>
