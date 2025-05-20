<template>
    <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 relative">
            <h2 class="text-xl font-bold mb-4">Add New Role</h2>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium">Role Name</label>
                    <input v-model="form.name" type="text" class="w-full border border-gray-300 rounded px-3 py-2"
                        placeholder="Enter role name" />
                    <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">
                        {{ form.errors.name }}
                    </div>
                </div>
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" @click="$emit('close')"
                        class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Add Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: ''
})

function submit() {
    form.post(route('admin.roles.store'), {
        onSuccess: () => {
            form.reset()
            form.clearErrors()
            // close modal
            emit('close')
        }
    })
}
const emit = defineEmits(['close'])
</script>
