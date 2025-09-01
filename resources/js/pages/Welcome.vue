<script setup>
import Button from '@/components/Button.vue';
import { payload1, payload2 } from '@/lib/payloads';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const secondsBetweenPayloads = 30;
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

function getDiffClass(diff) {
    switch (diff) {
        case 'changed':
            return 'bg-yellow-300';
        case 'added':
            return 'bg-green-300';
        case 'removed':
            return 'bg-red-300';
    }
}

function getImage(payload, id) {
    return payload.images.find((i) => i.id === id);
}

function getVariant(payload, id) {
    return payload.variants.find((i) => i.id === id);
}
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex flex-col pt-12">
        <div class="flex justify-center">
            <div class="flex flex-col items-center gap-4">
                <Button @click="handleSend" :disabled="loading">
                    {{ loading ? 'loading...' : 'Send Payload 1' }}
                </Button>
                <div v-if="error" class="text-red-700">{{ error }}</div>
            </div>
        </div>
        <div class="flex justify-center gap-6 pt-12">
            <div>Legend:</div>
            <div class="flex gap-2">
                <div class="h-6 w-12 bg-green-300" />
                <div>Added</div>
            </div>
            <div class="flex gap-2">
                <div class="h-6 w-12 bg-red-300" />
                <div>Removed</div>
            </div>
            <div class="flex gap-2">
                <div class="h-6 w-12 bg-yellow-300" />
                <div>Changed</div>
            </div>
        </div>
        <div class="p-12">
            <div class="grid grid-cols-3" v-if="diff">
                <div class="col-span-3 p-2 pt-12 text-2xl font-bold">Metadata</div>
                <div class="col-span-3 border-t" />
                <div class="p-2">Title</div>
                <div class="p-2" :class="getDiffClass(diff.title)">{{ payload1.title }}</div>
                <div class="p-2" :class="getDiffClass(diff.title)">{{ payload2.title }}</div>

                <div class="p-2">Description</div>
                <div class="p-2" :class="getDiffClass(diff.description)">
                    {{ payload1.description }}
                </div>
                <div class="p-2" :class="getDiffClass(diff.description)">
                    {{ payload2.description }}
                </div>

                <div class="col-span-3 p-2 pt-12 text-2xl font-bold">Images</div>
                <div v-for="image in diff.images" class="contents" :key="image.id">
                    <div class="col-span-3 border-t" />
                    <div v-for="key in Object.keys(image)" :key="key" class="contents">
                        <div class="p-2">{{ key }}</div>
                        <div class="p-2" :class="getDiffClass(image[key])">
                            {{ getImage(payload1, image.id)?.[key] }}
                        </div>
                        <div class="p-2" :class="getDiffClass(image[key])">
                            {{ getImage(payload2, image.id)?.[key] }}
                        </div>
                    </div>
                </div>

                <div class="col-span-3 p-2 pt-12 text-2xl font-bold">Variants</div>
                <div v-for="variant in diff.variants" class="contents" :key="variant.id">
                    <div class="col-span-3 border-t" />
                    <div v-for="key in Object.keys(variant)" :key="key" class="contents">
                        <div class="p-2">{{ key }}</div>
                        <div class="p-2" :class="getDiffClass(variant[key])">
                            {{ getVariant(payload1, variant.id)?.[key] }}
                        </div>
                        <div class="p-2" :class="getDiffClass(variant[key])">
                            {{ getVariant(payload2, variant.id)?.[key] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
