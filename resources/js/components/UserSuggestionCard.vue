<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
});

const isProcessing = ref(false);

const followUser = () => {
    isProcessing.value = true;
    router.post(`/users/${props.user.id}/follow`, {}, {
        preserveScroll: true,
        onSuccess: () => {
            // O botão ficará desabilitado, mas você poderia mudar o texto ou estado aqui
        },
        onError: () => {
            isProcessing.value = false; // Permite tentar novamente em caso de erro
        }
    });
};
</script>

<template>
    <div class="flex items-center justify-between rounded-lg bg-white p-4 shadow-sm dark:bg-gray-800">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500 text-white font-bold">
                {{ (user as any).name.charAt(0) }}
            </div>
            <div>
                <p class="font-bold text-gray-900 dark:text-white">{{ (user as any).name }}</p>
            </div>
        </div>
        <button
            @click="followUser"
            :disabled="isProcessing"
            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:opacity-60 disabled:cursor-not-allowed"
        >
            Seguir
        </button>
    </div>
</template>
