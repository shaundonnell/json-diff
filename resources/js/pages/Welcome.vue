<script setup lang="ts">
import { payload1, payload2 } from '@/lib/payloads';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const payload1Status = ref<string | null>(null);
const payload1Error = ref<string | null>(null);

const payload2Status = ref<string | null>(null);
const payload2Error = ref<string | null>(null);

const diff = ref<any | null>(null);

async function handleSend() {
    await sendPayload1();
    await sendPayload2();
    await fetchDiff();
}

async function sendPayload1() {
    const res = await fetch('/api/diff', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload1),
    });

    const data = await res.json();

    if (res.status === 200) {
        payload1Status.value = 'success';
    } else {
        payload1Error.value = data.error;
    }
}

async function sendPayload2() {
    const res = await fetch('/api/diff', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload2),
    });

    const data = await res.json();

    if (res.status === 200) {
        payload2Status.value = 'success';
    } else {
        payload2Error.value = data.error;
    }

    setTimeout(fetchDiff, 1000);
}

async function fetchDiff() {
    const res = await fetch('/api/diff');
    const data = await res.json();

    console.log(data);

    if (res.status === 200) {
        diff.value = JSON.stringify(data, null, 2);
    } else {
        payload1Error.value = data.error;
    }
}
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex flex-col pt-12">
        <div class="flex justify-center">
            <button
                class="cursor-pointer rounded bg-blue-700 px-4 py-2 text-white"
                @click="handleSend"
            >
                Send Payload 1
            </button>
        </div>
        <div class="grid"></div>
    </div>
</template>
