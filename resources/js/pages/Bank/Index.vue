<template>
    <AppLayout>
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-semibold">Banks</h1>
            <button @click="showAddModal = true" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Add Bank
            </button>
        </div>

        <!-- Search Bar -->
        <div class="mb-4">
            <input type="text" v-model="search" @input="filterBanks" placeholder="Search banks..."
                class="border px-3 py-2 rounded w-full" />
        </div>

        <!-- Bank Table -->
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="border px-4 py-2">#</th>
                    <th class="border px-4 py-2">Bank Name</th>
                    <th class="border px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(bank, index) in banks.data" :key="bank.id">
                    <td class="border px-4 py-2">{{ index + 1 }}</td>
                    <td class="border px-4 py-2">
                        <a :href="`/admin/banks/${bank.id}`" class="text-blue-600 hover:underline">
                            {{ bank.name }}
                        </a>
                    </td>
                    <td class="border px-4 py-2 flex gap-2">
                        <button @click="editBank(bank)" class="text-blue-600 mr-2 hover:underline">
                            Edit
                        </button>
                        <button @click="deleteBank(bank.id)" class="text-red-600 hover:underline">
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            <button v-if="banks.prev_page_url" @click="goToPage(banks.prev_page_url)"
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 mr-2">
                Prev
            </button>
            <button v-if="banks.next_page_url" @click="goToPage(banks.next_page_url)"
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                Next
            </button>
        </div>

        <!-- Add/Edit Modal -->
        <AddBankModal :show="showAddModal || showEditModal" :onClose="closeModal" :bank="editingBank" />
    </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AddBankModal from './AddBankModal.vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    banks: Object,
})

const showAddModal = ref(false)
const showEditModal = ref(false)
const editingBank = ref(null)
const search = ref('')

// Search logic (optional server-side implementation)
const filterBanks = () => {
    router.get('/admin/banks', { search: search.value }, { preserveState: true })
}

// Edit logic
const editBank = (bank) => {
    editingBank.value = { ...bank }
    showEditModal.value = true
}

// Close modal
const closeModal = () => {
    showAddModal.value = false
    showEditModal.value = false
    editingBank.value = null
}

// Pagination
const goToPage = (url) => {
    router.visit(url, { preserveState: true })
}

// Delete logic
const deleteBank = (id) => {
    if (confirm('Are you sure you want to delete this bank?')) {
        router.delete(route('admin.banks.destroy', id))
    }
}
</script>
