<template>
    <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 relative">
            <h2 class="text-lg font-semibold mb-4">
                {{ client ? 'Edit Client' : 'Add Client' }}
            </h2>

            <form @submit.prevent="submit">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium">Name</label>
                        <input v-model="form.name" type="text"
                            class="mt-1 w-full border border-gray-300 rounded px-3 py-2" />
                        <p v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Contact Person</label>
                        <input v-model="form.contact_person" type="text"
                            class="mt-1 w-full border border-gray-300 rounded px-3 py-2" />
                        <p v-if="form.errors.contact_person" class="text-red-500 text-sm">{{ form.errors.contact_person
                            }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Phone</label>
                        <input v-model="form.phone" type="text"
                            class="mt-1 w-full border border-gray-300 rounded px-3 py-2" />
                        <p v-if="form.errors.phone" class="text-red-500 text-sm">{{ form.errors.phone }}</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" @click="$emit('close')"
                        class="px-4 py-2 text-gray-600 hover:underline">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        {{ client ? 'Update' : 'Save' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
const props = defineProps({ client: Object })

const form = useForm({
    name: props.client?.name || '',
    contact_person: props.client?.contact_person || '',
    phone: props.client?.phone || '',
})

const submit = () => {
    if (props.client) {
        form.put(route('admin.client.update', props.client.id), {
            onSuccess: () => emitCloseAndRefresh(),
        })
    } else {
        form.post(route('admin.client.store'), {
            onSuccess: () => emitCloseAndRefresh(),
        })
    }
}

const emitCloseAndRefresh = () => {
    form.reset()
    emit('close')
    emit('refresh')
}

const emit = defineEmits(['close', 'refresh'])
</script>
