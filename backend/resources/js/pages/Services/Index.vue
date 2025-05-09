<template>

    <Head title="Services Offered" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <EasyDataTable
                    :headers="headers"
                    :items="Service"
                    alternating
                    border-cell
                    buttons-pagination
                />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import 'vue3-easy-data-table/dist/style.css'
import { defineProps, onMounted } from 'vue'
import EasyDataTable from 'vue3-easy-data-table'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue';
// import { type BreadcrumbItem } from '@/types';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';

const props = defineProps({
  Service: Array
})

// console.log('Service:', props.Service[0]);

const breadcrumbs = [
    {
        title: 'Services Offered',
        href: '/services-offered',
    },
];

// Define headers according to your ServiceResource fields
const headers = [
  { text: 'ID', value: 'id', sortable: true },
  { text: 'Name', value: 'name', sortable: true },
  { text: 'Description', value: 'description' },
  { text: "Operation", value: "operation"},
  // Add more fields as needed
]

// onMounted(() => {
//   console.log('Service:', props.Service)
// })
</script>
