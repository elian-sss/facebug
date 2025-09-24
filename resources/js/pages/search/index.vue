<script setup lang="ts">
import { ref, watch } from 'vue'; // 1. Importe 'watch'
import { Head, Link, router } from '@inertiajs/vue3'; // 2. Importe 'router' e remova 'useForm'
import AppLayout from '@/layouts/AppLayout.vue';
import PostCard from '@/components/PostCard.vue';
import { Search } from 'lucide-vue-next';
import debounce from 'lodash-es/debounce'; // 3. Importe a função 'debounce'

const props = defineProps({
    query: String,
    posts: Object,
    users: Object,
    comments: Object,
});

const activeTab = ref('posts');

// 4. Use uma ref simples para o campo de busca
const searchQuery = ref(props.query || '');

// 5. Crie um "observador" que chama a busca com debounce
watch(searchQuery, debounce(() => {
    router.get('/search', { query: searchQuery.value }, {
        preserveState: true,
        preserveScroll: true,
    });
}, 300)); // Espera 300ms após o usuário parar de digitar
</script>

<template>
    <Head :title="query ? `Busca por ${query}` : 'Pesquisar'" />
    <AppLayout>
        <div class="p-4 md:p-6">
            <div class="mb-8">
                <h1 class="mb-4 text-3xl font-bold text-gray-800 dark:text-gray-200">Pesquisar</h1>
                <div class="relative">
                    <input
                        type="text"
                        v-model="searchQuery"
                        placeholder="Pesquisar por posts, usuários ou comentários..."
                        class="w-full rounded-lg border bg-white py-3 pl-12 pr-4 text-base focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                    />
                    <Search class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
                </div>
            </div>

            <div v-if="query">

                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">

                    Resultados para: <span class="text-blue-600">"{{ query }}"</span>

                </h2>



                <div class="my-4 border-b border-gray-200 dark:border-gray-700">

                    <nav class="-mb-px flex space-x-6" aria-label="Tabs">

                        <button @click="activeTab = 'posts'" :class="[activeTab === 'posts' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700']" class="whitespace-nowrap border-b-2 py-3 px-1 text-sm font-medium">

                            Posts ({{ posts.total }})

                        </button>

                        <button @click="activeTab = 'users'" :class="[activeTab === 'users' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700']" class="whitespace-nowrap border-b-2 py-3 px-1 text-sm font-medium">

                            Usuários ({{ users.total }})

                        </button>

                        <button @click="activeTab = 'comments'" :class="[activeTab === 'comments' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700']" class="whitespace-nowrap border-b-2 py-3 px-1 text-sm font-medium">

                            Comentários ({{ comments.total }})

                        </button>

                    </nav>

                </div>



                <div id="tab-content">

                    <div v-show="activeTab === 'posts'">

                        <div v-if="posts.data.length > 0" class="space-y-4">

                            <PostCard v-for="post in posts.data" :key="post.id" :post="post" />

                        </div>

                        <p v-else class="text-center text-gray-500 py-8">Nenhum post encontrado.</p>

                    </div>



                    <div v-show="activeTab === 'users'">

                        <div v-if="users.data.length > 0" class="space-y-3">

                            <div v-for="user in users.data" :key="user.id" class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">

                                <Link :href="`/users/${user.name}`" class="font-bold text-blue-600 hover:underline">{{ user.name }}</Link>

                            </div>

                        </div>

                        <p v-else class="text-center text-gray-500 py-8">Nenhum usuário encontrado.</p>

                    </div>



                    <div v-show="activeTab === 'comments'">

                        <div v-if="comments.data.length > 0" class="space-y-3">

                            <div v-for="comment in comments.data" :key="comment.id" class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">

                                <p class="text-gray-700 dark:text-gray-300">"{{ comment.content }}"</p>

                                <p class="mt-2 text-sm text-gray-500">

                                    - <Link :href="`/users/${comment.user.name}`" class="font-semibold hover:underline">{{ comment.user.name }}</Link>

                                    em <Link :href="`/posts/${comment.post.id}`" class="italic hover:underline">"{{ comment.post.title }}"</Link> </p>

                            </div>

                        </div>

                        <p v-else class="text-center text-gray-500 py-8">Nenhum comentário encontrado.</p>

                    </div>

                </div>

            </div>

            <div v-else class="text-center text-gray-500 pt-16">
                <p class="text-lg">Comece a sua busca para encontrar posts, usuários e mais.</p>
            </div>
        </div>
    </AppLayout>
</template>
