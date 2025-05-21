<template>
  <div v-if="show" class="fixed inset-0 z-50 bg- bg-opacity-30 flex items-center justify-center">
        <div class="bg-white rounded shadow p-6 w-full max-w-md">
      <h2 class="text-lg font-semibold mb-4">{{ loadType ? 'Edit' : 'Add' }} Load Type</h2>
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Load Type Name</label>
          <input
            v-model="form.name"
            type="text"
            class="w-full border px-3 py-2 rounded"
            :class="{ 'border-red-500': form.errors.name }"
          />
          <p v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</p>
        </div>

        <div class="flex justify-end gap-2">
          <button type="button" @click="onClose" class="px-4 py-2 border rounded">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Save
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { watch } from 'vue'


const props = defineProps({
  show: Boolean,
  onClose: Function,
  loadType: Object,
})

const form = useForm({
  name: props.loadType?.name || '',
})

watch(() => props.loadType, (newValue) => {
  form.name = newValue?.name || ''
})

const submit = () => {
  if (props.loadType) {
    form.put(route('load-types.update', props.loadType.id), {
      onSuccess: () => {
        props.onClose()
      },
    })
  } else {
    form.post(route('load-types.store'), {
      onSuccess: () => {
        form.reset()
        props.onClose()
      },
    })
  }
}
</script>
