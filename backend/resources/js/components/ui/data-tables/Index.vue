<template>
    <h3>{{ heading }}</h3>

    <DataTable id="my-table" :data="data" class="display">
        <thead>
            <tr>
                <th>First</th>
                <th>Second</th>
            </tr>
        </thead>
    </DataTable>
</template>

<style>
@import 'datatables.net-dt';
</style>

<script setup lang="ts">
import { defineProps, onMounted } from 'vue';
import $ from 'jquery';
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-dt';

DataTable.use(DataTablesCore);
// onMounted(() => {
//   $('#my-table').DataTable();
// });


defineProps({
    heading: {
        type: String,
        required: true,
    },
})

const data = [
  [1, 2],
  [3, 4],
];

// onMounted(() => {
//     $('#my-table').DataTable();
// });
</script>
