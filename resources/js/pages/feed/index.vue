<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    posts: Array,
});

const form = useForm({
    id: '',
    title: '',
    content: '',
});

const expandedPostId = ref<number | null>(null);

const commentForm = useForm({
    content: '',
    post_id:''
});

const submit = () => {
    form.post('/posts', {
        onSuccess: () => {
            form.reset('title', 'content');
        },
    });
};

const submitComment = (postId: number) => {
    commentForm.post_id = postId
    commentForm.post('/comments', {
        onSuccess: () => {
            commentForm.reset();
        },
    });
};

const handleLike = (id) => {
    form.id = id.toString();
    form.post('/likes');
};

const toggleComments = (postId) => {
    expandedPostId.value = expandedPostId.value === postId ? null : postId;
};

const handleDelete = (postId: number) => {
    const confirmDelete = confirm('Tem certeza que deseja deletar este post?');
    if (!confirmDelete) return;
    const url = `/posts/${postId}`;
    useForm({}).delete(url, {
        preserveScroll: true,
    });
}
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
                    Postar
                </button>
            </form>

            <div v-if="posts && posts.length > 0">
                <div v-for="post in posts" :key="(post as any).id" class="mb-6 rounded-lg border bg-white p-4 shadow-sm dark:bg-gray-800">
                    <h2 class="text-xl font-bold mb-2">{{ post.title }}</h2>
                    <p class="mb-4">{{ post.content }}</p>
                    <p class="text-sm text-gray-500">Postado por **{{ (post as any).user.name }}** em {{ new Date((post as any).created_at).toLocaleDateString() }}</p>
                    <div class="mt-4">
                        <button @click="handleLike((post as any).id)" class="mr-4 text-blue-500 hover:underline">Curtir ({{ (post as any).likes.length }})</button>
                        <button @click="toggleComments((post as any).id)" class="mr-4 text-green-500 hover:underline">Comentar ({{ (post as any).comments.length }})</button>
                        <button @click="handleDelete((post as any).id)" v-if="(post as any).user_id === ($page.props as any).auth.user.id" class="text-red-500 hover:underline">
                            Deletar
                        </button>
                    </div>

                    <div v-if="expandedPostId === (post as any).id" class="mt-6">
                        <h3 class="mb-3 text-lg font-bold">Comentários</h3>

                        <form @submit.prevent="submitComment((post as any).id)" class="mb-4">
                            <textarea
                                v-model="commentForm.content"
                                class="w-full resize-none rounded-lg border p-2 focus:ring focus:ring-green-200 focus:outline-none dark:border-gray-600 dark:bg-gray-700"
                                rows="2"
                                placeholder="Adicione seu comentário..."
                                required
                            ></textarea>
                            <button
                                type="submit"
                                class="mt-2 rounded-lg bg-green-500 px-4 py-2 text-white hover:bg-green-600 disabled:bg-green-300 dark:disabled:bg-green-700"
                                :disabled="commentForm.processing"
                            >
                                Comentar
                            </button>
                        </form>

                        <div v-if="(post as any).comments.length > 0">
                            <div v-for="comment in (post as any).comments" :key="comment.id" class="mb-3 rounded-lg border bg-gray-100 p-3 shadow-sm dark:bg-gray-700">
                                <p class="font-semibold">{{ (comment as any).user.name }}</p>
                                <p class="mt-1">{{ (comment as any).content }}</p>
                                <p class="mt-2 text-xs text-gray-500">{{ new Date((comment as any).created_at).toLocaleString() }}</p>
                            </div>
                        </div>
                        <div v-else class="text-center text-gray-500">
                            Nenhum comentário ainda.
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center text-gray-500">Nenhum post registrado.</div>
        </div>
    </AppLayout>
</template>
