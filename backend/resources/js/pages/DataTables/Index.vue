<template>
    <Head title="Services Offered" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <DataTables :heading="heading" />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { defineProps, onMounted, ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue';
import DataTables from '@/components/ui/data-tables/Index.vue';

const breadcrumbs = [
    {
        title: 'Data Tables',
        href: '/data-tables',
    },
];

const heading = 'Data Tables';
const items = ref([]);

// onMounted(async () => {
//     const response = await fetch('https://jsonplaceholder.typicode.com/todos')
//     .then(response => response.json())
//     .then(data => items.value = data);

//     console.log('Items:', items.value);

// });
</script>

