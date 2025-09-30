<script setup lang="ts">
import { ref, watch, nextTick, computed, onMounted, onUnmounted } from 'vue'; // Adicione onUnmounted
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import axios from 'axios';
import { MessageSquare, Send } from 'lucide-vue-next';

const props = defineProps({
    conversations: Array,
});

const page = usePage();
const authUser = computed(() => page.props.auth.user);

const selectedConversation = ref(null);
const messages = ref([]);
const messageInput = useForm({ body: '' });
const messageContainer = ref(null);

const selectConversation = async (conversation) => {
    selectedConversation.value = conversation;
    messages.value = [];

    const response = await axios.get(`/chat/${conversation.id}`);
    messages.value = response.data.data.reverse();

    scrollToBottom();
};

const sendMessage = () => {
    if (!selectedConversation.value || messageInput.body.trim() === '') return;

    // A requisição POST agora está mais simples
    axios.post(`/chat/${selectedConversation.value.id}/messages`, {
        body: messageInput.body
    }).then(response => {
        messages.value.push(response.data); // Adiciona a própria mensagem instantaneamente
        messageInput.reset();
        scrollToBottom();
    }).catch(error => {
        console.error('Erro ao enviar mensagem:', error);
    });
};

const scrollToBottom = () => {
    nextTick(() => {
        if (messageContainer.value) {
            messageContainer.value.scrollTop = messageContainer.value.scrollHeight;
        }
    });
};

const getOtherUser = (conversation) => {
    return conversation.users.find(user => user.id !== authUser.value.id);
};

// --- PARTE 4: LÓGICA EM TEMPO REAL COM ECHO ---

// Observa mudanças na conversa selecionada para gerenciar os canais do Echo
watch(selectedConversation, (newConversation, oldConversation) => {
    // 1. Sai do canal antigo para não receber mais mensagens dele
    if (oldConversation) {
        window.Echo.leave('conversation.' + oldConversation.id);
    }

    // 2. Entra no novo canal privado da conversa
    if (newConversation) {
        window.Echo.private('conversation.' + newConversation.id)
            .listen('NewMessageSent', (event) => {
                // 3. Adiciona a mensagem recebida em tempo real à lista
                messages.value.push(event.message);
                scrollToBottom();
            });
    }
});

// Limpa o ouvinte ao sair da página para evitar vazamentos de memória
onUnmounted(() => {
    if (selectedConversation.value) {
        window.Echo.leave('conversation.' + selectedConversation.value.id);
    }
});


onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const conversationId = urlParams.get('conversation_id');
    if (conversationId) {
        const conversationToSelect = props.conversations.find(c => c.id == conversationId);
        if (conversationToSelect) {
            selectConversation(conversationToSelect);
        }
    }
});
</script>

<template>
    <Head title="Chat" />
    <AppLayout>
        <div class="flex h-screen border-t dark:border-gray-700">
            <div class="w-1/3 flex-shrink-0 border-r dark:border-gray-700 bg-white dark:bg-gray-800 flex flex-col">
                <div class="p-4 border-b dark:border-gray-700">
                    <h2 class="text-xl font-bold dark:text-white">Conversas</h2>
                </div>
                <div class="flex-1 overflow-y-auto">
                    <div
                        v-for="conversation in conversations"
                        :key="conversation.id"
                        @click="selectConversation(conversation)"
                        class="p-4 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 border-b dark:border-gray-700 flex items-center gap-3"
                        :class="{ 'bg-blue-100 dark:bg-blue-900/50': selectedConversation && selectedConversation.id === conversation.id }"
                    >
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-500 text-white font-bold flex-shrink-0">
                            {{ getOtherUser(conversation).name.charAt(0) }}
                        </div>
                        <div>
                            <p class="font-semibold dark:text-gray-200">{{ getOtherUser(conversation).name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-2/3 flex flex-col bg-gray-50 dark:bg-gray-900">
                <div v-if="selectedConversation" class="flex flex-col h-full">
                    <div class="p-4 border-b dark:border-gray-700 bg-white dark:bg-gray-800 flex-shrink-0">
                        <h2 class="text-xl font-bold dark:text-white">{{ getOtherUser(selectedConversation).name }}</h2>
                    </div>

                    <div ref="messageContainer" class="flex-1 p-6 overflow-y-auto space-y-4">
                        <div v-for="message in messages" :key="message.id" class="flex" :class="{ 'justify-end': message.user_id === authUser.id }">
                            <div class="max-w-xs md:max-w-md p-3 rounded-lg" :class="{ 'bg-blue-600 text-white': message.user_id === authUser.id, 'bg-gray-200 dark:bg-gray-700 dark:text-gray-200': message.user_id !== authUser.id }">
                                <p>{{ message.body }}</p>
                                <p class="text-xs mt-1 opacity-70 text-right">{{ new Date(message.created_at).toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' }) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700 flex-shrink-0">
                        <form @submit.prevent="sendMessage" class="flex items-center gap-3">
                            <input
                                v-model="messageInput.body"
                                type="text"
                                placeholder="Digite sua mensagem..."
                                class="w-full rounded-lg border bg-gray-100 py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                autocomplete="off"
                            />
                            <button type="submit" :disabled="messageInput.processing" class="p-2 rounded-full bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 flex-shrink-0">
                                <Send class="h-5 w-5" />
                            </button>
                        </form>
                    </div>
                </div>
                <div v-else class="flex items-center justify-center h-full text-gray-500 flex-col">
                    <MessageSquare class="h-16 w-16" />
                    <p class="mt-4 text-lg">Selecione uma conversa para começar</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
