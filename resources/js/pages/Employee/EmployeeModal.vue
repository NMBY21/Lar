<template>
    <div v-if="show" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex items-center justify-center">
        <div class="bg-white rounded shadow p-6 w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4">{{ employee ? 'Edit' : 'Add' }} Employee</h2>
            <form @submit.prevent="submit" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input v-model="form.name" type="text" class="w-full border px-3 py-2 rounded" />
                    <p v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Phone</label>
                    <input v-model="form.phone" type="text" class="w-full border px-3 py-2 rounded" />
                    <p v-if="form.errors.phone" class="text-red-500 text-sm">{{ form.errors.phone }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Employee Type</label>
                    <select v-model="form.employee_type" class="w-full border px-3 py-2 rounded">
                        <option value="driver">Driver</option>
                        <option value="mechanic">Mechanic</option>
                        <option value="office staff">Office Staff</option>
                    </select>
                    <p v-if="form.errors.employee_type" class="text-red-500 text-sm">{{ form.errors.employee_type }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Upload File (optional)</label>
                    <input @change="handleFileChange" type="file" class="w-full border px-3 py-2 rounded" />
                    <p v-if="form.errors.file" class="text-red-500 text-sm">{{ form.errors.file }}</p>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="onClose" class="px-4 py-2 border rounded">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
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
    employee: Object,
})

const form = useForm({
    name: props.employee?.name || '',
    phone: props.employee?.phone || '',
    employee_type: props.employee?.employee_type || 'driver',
    file: null,
})

// Watch for prop updates
watch(() => props.employee, (newValue) => {
    form.name = newValue?.name || ''
    form.phone = newValue?.phone || ''
    form.employee_type = newValue?.employee_type || 'driver'
    form.file = null
})

// File change handler
const handleFileChange = (event) => {
    form.file = event.target.files[0]
}

// Submit handler
const submit = () => {
    if (props.employee) {
        form.post(route('employees.update', props.employee.id), {
            method: 'put',
            forceFormData: true,
            onSuccess: () => {
                props.onClose()
            },
        })
    } else {
        form.post(route('employees.store'), {
            forceFormData: true,
            onSuccess: () => {
                form.reset()
                props.onClose()
            },
        })
    }
}
</script>
