<script setup>
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';

const props = defineProps({
    payload: Object,
    diff: Object,
    showChanges: Boolean,
});

const variants = [...props.payload.variants].sort((a, b) => a.id - b.id);

function getDiffForVariant(id) {
    return props.diff?.variants.find((i) => i.id === id);
}

function getRowClass(variant) {
    const diff = getDiffForVariant(variant.id);
    switch (diff.sku) {
        case 'added':
            return 'bg-green-100';
        case 'removed':
            return 'bg-red-100';
    }
    return '';
}

function getCellClass(variant, key) {
    const diff = getDiffForVariant(variant.id);

    if (diff[key] === 'changed' && props.showChanges) {
        return 'outline-2 outline-yellow-600';
    }

    return '';
}
</script>

<template>
    <div class="px-6 pb-2">
        <h2 class="pb-2 text-lg font-semibold">Variants</h2>
        <Table class="min-w-96 shrink-0">
            <TableHeader>
                <TableRow>
                    <TableHead>ID</TableHead>
                    <TableHead>SKU</TableHead>
                    <TableHead>Barcode</TableHead>
                    <TableHead>Image ID</TableHead>
                    <TableHead>Inventory</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow
                    v-for="variant in variants"
                    :key="variant.id"
                    :class="getRowClass(variant)"
                >
                    <TableCell>
                        <span
                            class="rounded px-2 py-1 text-sm"
                            :class="getCellClass(variant, 'id')"
                        >
                            {{ variant.id }}
                        </span></TableCell
                    >
                    <TableCell>
                        <span
                            class="rounded px-2 py-1 text-sm"
                            :class="getCellClass(variant, 'sku')"
                        >
                            {{ variant.sku }}
                        </span>
                    </TableCell>
                    <TableCell>
                        <span
                            class="rounded px-2 py-1 text-sm"
                            :class="getCellClass(variant, 'barcode')"
                        >
                            {{ variant.barcode }}
                        </span>
                    </TableCell>
                    <TableCell>
                        <span
                            class="rounded px-2 py-1 text-sm"
                            :class="getCellClass(variant, 'image_id')"
                        >
                            {{ variant.image_id }}
                        </span>
                    </TableCell>
                    <TableCell>
                        <span
                            class="rounded px-2 py-1 text-sm"
                            :class="getCellClass(variant, 'inventory_quantity')"
                        >
                            {{ variant.inventory_quantity }}
                        </span>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>

<style scoped></style>
