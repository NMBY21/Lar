<template>
    <AppLayout>
    <div>
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-semibold">Clients</h1>

            <button @click="showAddModal = true" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                + Add Client
            </button>
        </div>

        <!-- Search Box -->
        <div class="mb-4">
            <input v-model="search" @input="searchClients" type="text"
                placeholder="Search by name, contact person, or phone"
                class="w-full md:w-1/3 border border-gray-300 rounded-md px-3 py-2" />
        </div>

        <!-- Clients Table -->
        <div class="bg-white shadow rounded-md overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Contact Person</th>
                        <th class="px-4 py-2">Phone</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="client in clients.data" :key="client.id" class="border-b">
                        <td class="px-4 py-2">{{ client.name }}</td>
                        <td class="px-4 py-2">{{ client.contact_person }}</td>
                        <td class="px-4 py-2">{{ client.phone }}</td>
                        <td class="px-4 py-2 space-x-2">
                            <button @click="editClient(client)" class="text-blue-600 hover:underline">Edit</button>
                            <button @click="deleteClient(client.id)"
                                class="text-red-600 hover:underline">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            <Pagination :links="clients.links" />
        </div>

        <!-- Add Client Modal -->
        <ClientFormModal v-if="showAddModal" @close="showAddModal = false" @refresh="reloadPage" />

        <!-- Edit Client Modal -->
        <ClientFormModal v-if="selectedClient" :client="selectedClient" @close="selectedClient = null"
            @refresh="reloadPage" />
    </div>
</AppLayout>
</template>

<script setup>
import { ref, watch } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import Pagination from '@/Components/Pagination.vue'
import ClientFormModal from './FormModal.vue'
import AppLayout from '@/Layouts/AppLayout.vue';


const props = defineProps({
    clients: Object,
    filters: Object,
})

const search = ref(props.filters.search || '')
const showAddModal = ref(false)
const selectedClient = ref(null)

const searchClients = () => {
    router.get(route('admin.client.index'), { search: search.value }, {
        preserveState: true,
        replace: true
    })
}

const deleteClient = (id) => {
    if (confirm('Are you sure you want to delete this client?')) {
        router.delete(route('admin.client.destroy', id))
    }
}

const editClient = (client) => {
    selectedClient.value = client
}

const reloadPage = () => {
    router.reload({ only: ['clients'] })
}
</script>
