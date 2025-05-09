<template>
    <Head title="Staff" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min overflow-x-auto">

                <!-- Search Box aligned right with inline label -->
                <div class="flex justify-end p-8">
                    <div class="flex items-center gap-2 w-64">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Search:</label>
                        <input
                            type="text"
                            v-model="searchValue"
                            placeholder="Search staff..."
                            class="flex-1 rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                        />
                    </div>
                </div>

                <!-- Data Table -->
                <EasyDataTable
                    :headers="headers"
                    :items="staff"
                    :search-value="searchValue"
                    alternating
                    border-cell
                />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import 'vue3-easy-data-table/dist/style.css'
import { defineProps, ref, onMounted } from 'vue'
import EasyDataTable from 'vue3-easy-data-table'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue';
// import { type BreadcrumbItem } from '@/types';

const props = defineProps({
    staff: Array
})

const searchValue = ref('');

const breadcrumbs = [
    {
        title: 'Staff',
        href: '/staff',
    },
];

// Define headers according to your ServiceResource fields
const headers = [
  { text: 'Id', value: 'id', sortable: true },
  { text: 'Name', value: 'name' },
  { text: 'Email', value: 'email' },
  // Add more fields as needed
]

// onMounted(() => {
//   console.log('Service:', props.staff)
// })
</script>
