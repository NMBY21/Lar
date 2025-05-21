<template>
    <div v-if="show" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex items-center justify-center">
        <div class="bg-white rounded shadow p-6 w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4">
                {{ bank ? 'Edit Bank' : 'Add Bank' }}
            </h2>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Bank Name</label>
                    <input v-model="form.name" type="text" class="w-full border px-3 py-2 rounded"
                        :class="{ 'border-red-500': form.errors.name }" />
                    <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                        {{ form.errors.name }}
                    </p>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="onClose" class="px-4 py-2 border rounded">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        {{ bank ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    show: Boolean,
    onClose: Function,
    bank: Object,
})

const form = useForm({
    name: props.bank?.name || '',
})

const submit = () => {
    if (props.bank) {
        // Update existing bank
        form.put(route('admin.banks.update', props.bank.id), {
            onSuccess: () => {
                form.reset()
                props.onClose()
            },
        })
    } else {
        // Create new bank
        form.post(route('admin.banks.store'), {
            onSuccess: () => {
                form.reset()
                props.onClose()
            },
        })
    }
}
</script>
