<template>
  <!-- <fwb-button @click="showModal">
    Open modal
  </fwb-button> -->

  <fwb-modal v-if="isShowModal" @close="closeModal">
    <template #header>
      <div class="flex items-center text-lg">
        {{ props.title }}
      </div>
    </template>
    <template #body>
      <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
        {{ props.message }}
      </p>
    </template>
    <template #footer>
      <div class="flex justify-between">
        <fwb-button @click="closeModal" color="alternative">
          Cancel
        </fwb-button>
        <fwb-button @click="confirmAction" color="green">
          Confirm
        </fwb-button>
      </div>
    </template>
  </fwb-modal>
</template>

<script lang="ts" setup>
import { ref, defineProps, defineEmits, watch } from 'vue'
import { FwbModal, FwbButton } from 'flowbite-vue'

const props = defineProps({
  show: Boolean,
  title: String,
  message: String
})

const emit = defineEmits(['close', 'confirm'])

const isShowModal = ref(props.show)

watch(() => props.show, (val) => {
  isShowModal.value = val
})

function closeModal() {
  isShowModal.value = false
  emit('close')
}

function confirmAction() {
  isShowModal.value = false
  emit('confirm')
}
</script>
