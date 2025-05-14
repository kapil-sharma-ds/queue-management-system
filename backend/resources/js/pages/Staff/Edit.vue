<template>

    <Head title="Edit Staff" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min overflow-x-auto">
                <div class="container mx-auto p-4">
                    <div class="bg-white rounded-xl shadow-md p-6 w-full max-w-3xl mx-auto">
                        <h2 class="text-xl font-bold mb-6">Edit Staff</h2>

                        <form @submit.prevent="submitForm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <div class="flex items-center gap-2">
                                    <label class="w-32 font-medium">Name:</label>
                                    <input v-model="form.name" type="text" class="input-text" />
                                </div>

                                <div class="flex items-center gap-2">
                                    <label class="w-32 font-medium">Email:</label>
                                    <input v-model="form.email" type="email" class="input-text" />
                                </div>

                                <div class="flex items-center gap-2">
                                    <label class="w-32 font-medium">Service:</label>
                                    <select v-model="form.service_id" class="input-text">
                                        <option v-for="service in services" :key="service.id" :value="service.id">
                                            {{ service.name }}
                                        </option>
                                    </select>
                                </div>

                                <div class="flex items-center gap-2">
                                    <label class="w-32 font-medium">Counter:</label>
                                    <select v-model="form.counter_id" class="input-text">
                                        <option v-for="counter in counters" :key="counter.id" :value="counter.id">
                                            {{ counter.name }}
                                        </option>
                                    </select>
                                </div>

                                <div class="flex items-center gap-2">
                                    <label class="w-32 font-medium">Role:</label>
                                    <select v-model="form.role_id" class="input-text">
                                        <option v-for="role in roles" :key="role.id" :value="role.id">
                                            {{ role.name }}
                                        </option>
                                    </select>
                                </div>

                                <div class="flex items-start gap-2 md:col-span-2">
                                    <label class="w-32 font-medium mt-2">Bio:</label>
                                    <textarea v-model="form.bio" rows="4" class="input-text w-full">
                                    </textarea>
                                </div>
                            </div>

                            <div class="mt-6 text-right">
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { defineProps, reactive } from 'vue';
import { router, Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps({
    staff: Object,
    services: Array,
    counters: Array,
    roles: Array,
})

const breadcrumbs = [
    {
        title: 'Staff',
        href: "/staff",
    },
    {
        title: 'Staff Details',
        href: "/staff/${props.staff.id}",
    },
];

const form = reactive({
    name: props.staff.name,
    email: props.staff.email,
    bio: props.staff.bio,
    service_id: props.staff.service_id,
    counter_id: props.staff.counter_id,
    role_id: props.staff.role_id,
})

const submitForm = () => {
    // router.put(`/staff/${props.staff.id}`, form)
    router.put(route('staff.update', props.staff.id), form);
}
</script>

<style scoped>
@reference "@resources/css/app.css";

.input-text {
    @apply w-full border border-gray-300 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-md;
}
</style>
