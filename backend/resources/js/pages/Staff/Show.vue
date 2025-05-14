<template>
    <Head title="Staff" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min overflow-x-auto">
                <!-- Search Box aligned right with inline label -->
                <div class="container mx-auto p-4">
                    <div class="bg-white rounded-xl shadow-md p-4 w-full">
                        <h2 class="text-lg font-bold mb-2">Staff Details</h2>
                        <div class="flex flex-wrap justify-center mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold">{{ staff.name }}</h3>
                                <p class="text-gray-600">{{ staff.service.name }}</p>
                                <p class="text-gray-600">{{ staff.counter.name }}</p>
                            </div>
                        </div>
                        <div class="flex justify-between mb-4">
                            <p class="text-gray-600">Email: {{ staff.email }}</p>
                            <p class="text-gray-600">Role: {{ staff.role.name }}</p>
                        </div>
                        <p class="text-gray-600">{{ staff.bio }}</p>
                    </div>
                </div>


            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { defineProps } from 'vue';

defineProps({
    staff: {
        type: Object,
        required: true
    }
})

const breadcrumbs = [
    {
        title: 'Staff Details',
        href: "/staff/${props.staff.id}",
    },
];
</script>
