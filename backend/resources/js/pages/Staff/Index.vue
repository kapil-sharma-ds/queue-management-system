<template>

    <Head title="Staff" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <fwb-alert v-if="showSuccessAlert" type="success" closable @close="showSuccessAlert = false">
                {{ alertMessage }}
            </fwb-alert>
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min overflow-x-auto">
                <!-- Parent Flex Container -->
                <div class="flex justify-between items-center p-4 flex-wrap gap-4">
                    <!-- Button on the left -->
                    <div class="flex items-center gap-2">
                        <fwb-button color="default" @click="clearStaffCache">
                            Clear Redis Cache
                        </fwb-button>
                    </div>

                    <!-- Search box on the right -->
                    <div class="flex items-center gap-2 max-w-xs">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Search:</label>
                        <input type="text" v-model="searchValue" placeholder="Search staff..."
                            class="flex-1 rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
                    </div>
                </div>

                <EasyDataTable :headers="headers" :items="staff" :search-value="searchValue" alternating border-cell>
                    <template #item-actions="{ id, name }">
                        <div class="flex gap-2">
                            <button @click="viewItem(id)" class="text-blue-500 hover:underline">View</button>
                            <button @click="editItem(id)" class="text-yellow-500 hover:underline">Edit</button>
                            <button @click="confirmDelete(id, name)"
                                class="text-red-500 hover:underline">Delete</button>
                        </div>
                    </template>
                </EasyDataTable>
            </div>
        </div>
    </AppLayout>

    <!-- Reusable Modal -->
    <Modal v-if="selectedStaff" :show="showModal" :title="`Delete Staff`" :cancel-button="true" :confirm-button="true"
        :cancel-button-text="'Cancel'" :confirm-button-text="'Confirm'" :cancel-button-color="'alternative'"
        :confirm-button-color="'red'" :message="`Are you sure you want to delete ${selectedStaff.name}?`"
        @close="showModal = false" @confirm="handleConfirm" />
</template>

<script setup>
import 'vue3-easy-data-table/dist/style.css'
import axios from 'axios';
import { defineProps, ref, onMounted } from 'vue'
import EasyDataTable from 'vue3-easy-data-table'
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue';
import Modal from '@/components/ui/modal/Modal.vue'
import { FwbButton } from 'flowbite-vue';
import { FwbAlert } from 'flowbite-vue'
// import { type BreadcrumbItem } from '@/types';

const props = defineProps({
    staff: Array
})

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
    { text: 'Actions', value: 'actions' },
    // Add more fields as needed
]

const searchValue = ref('');
const showModal = ref(false);
const selectedStaff = ref(null);
const showSuccessAlert = ref(false);
const alertMessage = ref('');

const viewItem = (id) => {
    router.visit(route('staff.show', id))
}

const editItem = (id) => {
    router.visit(route('staff.edit', id))
}

const confirmDelete = (id, name) => {
    selectedStaff.value = { id, name }
    showModal.value = true
}

const handleConfirm = async () => {
    if (selectedStaff.value) {
        try {
            await axios.delete(route('staff.destroy', selectedStaff.value.id))
            router.visit(route('staff.index'), {
                only: ['staff'],
                preserveScroll: true,
            })
        } catch (error) {
            console.error('Failed to delete staff:', error);
        } finally {
            showModal.value = false;
        }
    }
}

const clearStaffCache = async () => {
    try {
        const response = await axios.post(route('staff.clearStaffCache'))
        // router.visit(route('staff.index'), {
        //     only: ['staff'],
        //     preserveScroll: true,
        // })
        alertMessage.value = response.data.message;
        showSuccessAlert.value = true;
    } catch (error) {
        console.error('Failed to clear cache:', error);
        alertMessage.value = 'Error clearing cache';
        showSuccessAlert.value = true;
    }
}
</script>
