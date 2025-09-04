<script setup>
import ProductCard from '@/components/product-card/ProductCard.vue';
import { Button } from '@/components/ui/button/index.js';
import { payload1, payload2 } from '@/lib/payloads';
import { Head } from '@inertiajs/vue3';
import { ArrowRight, Loader2 } from 'lucide-vue-next';
import { ref } from 'vue';

const secondsBetweenPayloads = 0;
const error = ref(null);
const diff = ref(null);
const loading = ref(false);

function sleep(time) {
    return new Promise((resolve) => setTimeout(resolve, time));
}

async function handleSend() {
    loading.value = true;
    try {
        await sendPayload1();
        await sleep(secondsBetweenPayloads * 1000);

        await sendPayload2();
        await fetchDiff();
    } finally {
        loading.value = false;
    }
}

async function sendPayload1() {
    const res = await fetch('/api/diff', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload1),
    });

    const data = await res.json();

    if (res.status !== 200) {
        error.value = data.message;
        throw new Error();
    }
}

async function sendPayload2() {
    const res = await fetch('/api/diff', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload2),
    });

    const data = await res.json();

    if (res.status !== 200) {
        error.value = data.message;
        throw new Error();
    }
}

async function fetchDiff() {
    const res = await fetch('/api/diff');
    const data = await res.json();

    if (res.status === 200) {
        diff.value = data;
    } else {
        error.value = data.message;
        throw new Error();
    }
}
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex min-h-screen flex-col bg-gray-100 pt-12">
        <div class="flex justify-center">
            <div class="flex flex-col items-center gap-4">
                <Button @click="handleSend" :disabled="loading">
                    <Loader2 v-if="loading" class="mr-2 h-4 w-4 animate-spin" />
                    {{ loading ? 'loading...' : 'Send Payload 1' }}
                </Button>
                <div v-if="error" class="text-red-700">{{ error }}</div>
            </div>
        </div>
        <div class="flex flex-col-reverse gap-6 p-12 lg:flex-row" v-if="diff">
            <ProductCard :payload="payload1" :diff="diff" />
            <div class="flex items-center justify-center self-stretch">
                <ArrowRight class="hidden size-8 shrink-0 lg:block" />
            </div>
            <ProductCard :payload="payload2" :diff="diff" show-changes />
        </div>
    </div>
</template>
