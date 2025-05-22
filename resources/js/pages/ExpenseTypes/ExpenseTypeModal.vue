<template>
    <div v-if="show" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow w-full max-w-md">
            <h2 class="text-lg font-bold mb-4">{{ expenseType ? 'Edit' : 'Add' }} Expense Type</h2>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block mb-1 text-sm">Name</label>
                    <input v-model="form.name" type="text" class="w-full border px-3 py-2 rounded"
                        :class="{ 'border-red-500': form.errors.name }">
                    <div v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</div>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 text-sm">Categories</label>
                    <div class="flex flex-wrap gap-2">
                        <label v-for="cat in categoryOptions" :key="cat" class="flex items-center gap-1">
                            <input type="checkbox" :value="cat" v-model="form.categories"> {{ cat }}
                        </label>
                    </div>
                    <div v-if="form.errors.categories" class="text-red-500 text-sm">{{ form.errors.categories }}</div>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="onClose" class="px-4 py-2 border rounded">Cancel</button>
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { watch } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  show: Boolean,
  onClose: Function,
  expenseType: Object
})

const categoryOptions = ['private', 'own', 'general', 'vehicle', 'employee']

const form = useForm({
  name: props.expenseType?.name || '',
  categories: props.expenseType?.categories || [],
})

watch(() => props.expenseType, (newValue) => {
  form.name = newValue?.name || ''
  form.categories = newValue?.categories || []
})

const submit = () => {
  if (props.expenseType) {
    form.put(route('expense-types.update', props.expenseType.id), {
      onSuccess: () => props.onClose()
    })
  } else {
    form.post(route('expense-types.store'), {
      onSuccess: () => {
        form.reset()
        props.onClose()
      }
    })
  }
}
</script>
