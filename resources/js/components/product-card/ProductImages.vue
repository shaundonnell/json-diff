<script setup>
import SquareImage from '@/components/SquareImage.vue';

const props = defineProps({
    payload: Object,
    diff: Object,
    showChanges: Boolean,
});

const images = [...props.payload.images].sort((a, b) => a.position - b.position);

function getDiffForImage(id) {
    return props.diff?.images.find((i) => i.id === id);
}

function getImageClass(image) {
    const diff = getDiffForImage(image.id);
    switch (diff.url) {
        case 'added':
            return 'outline-2 outline-green-600';
        case 'removed':
            return 'outline-2 outline-red-600';
        case 'changed':
            return props.showChanges ? 'outline-2 outline-yellow-600' : '';
    }
}
</script>

<template>
    <div class="px-6">
        <h2 class="pb-2 text-lg font-semibold">Images</h2>
        <div class="flex flex-wrap gap-4 pt-4">
            <SquareImage
                v-for="image in images"
                :src="image.url"
                :alt="props.payload.title"
                :key="image.src"
                class="h-16 border-2 border-white"
                :class="getImageClass(image)"
            />
        </div>
    </div>
</template>

<style scoped></style>
