<template>
    <AppLayout>
    <div>
        <div class="flex justify-between items-center mb-4">
            <input v-model="search" @input="fetchExpenseTypes" placeholder="Search..."
                class="border px-2 py-1 rounded w-64">
            <button @click="showModal = true" class="bg-blue-600 text-white px-4 py-2 rounded">Add Expense Type</button>
        </div>

        <table class="w-full border mb-4">
            <thead>
                <tr class="bg-gray-200">
                    <th class="text-left p-2">#</th>
                    <th class="text-left p-2">Name</th>
                    <th class="text-left p-2">Categories</th>
                    <th class="text-left p-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in expenseTypes.data" :key="item.id" class="border-t">
                    <td class="p-2">{{ index + 1 }}</td>
                    <td class="p-2">{{ item.name }}</td>
                    <td class="p-2">{{ item.categories.join(', ') }}</td>
                    <td class="p-2 space-x-2">
                        <button @click="editItem(item)" class="text-blue-600">Edit</button>
                        <button @click="deleteItem(item.id)" class="text-red-600">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <Pagination :links="expenseTypes.links" />

        <ExpenseTypeModal :show="showModal" :onClose="closeModal" :expenseType="editingItem" />
    </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import ExpenseTypeModal from './ExpenseTypeModal.vue'
import Pagination from '@/components/Pagination.vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({ expenseTypes: Object })

const search = ref('')
const showModal = ref(false)
const editingItem = ref(null)

const fetchExpenseTypes = () => {
    router.get(route('expense-types.index'), { search: search.value }, { preserveState: true, replace: true })
}

const editItem = (item) => {
    editingItem.value = item
    showModal.value = true
}

const deleteItem = (id) => {
    if (confirm("Are you sure?")) {
        router.delete(route('expense-types.destroy', id))
    }
}

const closeModal = () => {
    showModal.value = false
    editingItem.value = null
}
</script>
