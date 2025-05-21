<template>
        <div v-if="show" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
            <div class="bg-white rounded p-6 w-full max-w-md">
                <h2 class="text-lg font-semibold mb-4">{{ location ? 'Edit' : 'Add' }} Location</h2>
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label class="block mb-1 text-sm font-medium">Location Name</label>
                        <input v-model="form.name" type="text" class="w-full border px-3 py-2 rounded"
                            :class="{ 'border-red-500': form.errors.name }" />
                        <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="onClose" class="px-4 py-2 border rounded">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            {{ location ? 'Update' : 'Save' }}
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
  location: Object,
})

const form = useForm({
  name: props.location?.name || '',
})

watch(() => props.location, (newVal) => {
  form.name = newVal?.name || ''
})

const submit = () => {
  if (props.location) {
    form.put(route('locations.update', props.location.id), {
      onSuccess: () => props.onClose(),
    })
  } else {
    form.post(route('locations.store'), {
      onSuccess: () => {
        form.reset()
        props.onClose()
      },
    })
  }
}
</script>
