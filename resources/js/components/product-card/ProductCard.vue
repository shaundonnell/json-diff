<script setup>
import ProductImages from '@/components/product-card/ProductImages.vue';
import ProductVariants from '@/components/product-card/ProductVariants.vue';
import SquareImage from '@/components/SquareImage.vue';
import { Card, CardContent } from '@/components/ui/card/index.js';

const props = defineProps({
    payload: Object,
    diff: Object,
    showChanges: Boolean,
});

const headerClass = props.showChanges
    ? ' border-b-green-300 bg-green-200 text-green-700'
    : ' border-b-yellow-300 bg-yellow-200 text-yellow-700';

const titleClass =
    props.diff?.title === 'changed' && props.showChanges ? 'outline-2 outline-yellow-600' : '';

const descriptionClass =
    props.diff?.description === 'changed' && props.showChanges
        ? 'outline-2 outline-yellow-600'
        : '';
</script>

<template>
    <Card class="gap-0 overflow-hidden p-0 pb-6">
        <div class="flex items-center justify-center border-b p-2" :class="headerClass">
            {{ props.showChanges ? 'New version' : 'Old version' }}
        </div>
        <CardContent class="p-0">
            <div class="flex flex-col gap-5">
                <div class="flex gap-4 px-6">
                    <SquareImage
                        class="mt-6 h-20 lg:h-32 xl:h-48"
                        :src="props.payload.images[0].url"
                        :alt="props.payload.title"
                    />
                    <div class="mt-4 flex flex-col gap-2">
                        <h1 class="rounded p-2 text-2xl font-bold" :class="titleClass">
                            {{ props.payload.title }}
                        </h1>
                        <div
                            class="flex max-h-96 flex-col gap-4 overflow-y-scroll rounded p-2 text-sm"
                            :class="descriptionClass"
                            v-html="props.payload.description"
                        />
                    </div>
                </div>
                <div class="border-t" />
                <ProductImages
                    :payload="props.payload"
                    :diff="props.diff"
                    :show-changes="props.showChanges"
                />
                <div class="border-t" />
                <ProductVariants
                    :payload="props.payload"
                    :diff="props.diff"
                    :show-changes="props.showChanges"
                />
            </div>
        </CardContent>
    </Card>
</template>

<style scoped></style>
