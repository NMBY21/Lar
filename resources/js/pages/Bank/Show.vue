<template>
  <AppLayout>
    <div class="p-4">
      <!-- Header -->
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold">Bank: {{ bank.name }}</h1>
        <button
          @click="openAddModal"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
          Add Account
        </button>
      </div>

      <!-- Search -->
      <div class="mb-4">
        <input
          type="text"
          v-model="search"
          @input="searchAccounts"
          placeholder="Search accounts..."
          class="border px-3 py-2 rounded w-full"
        />
      </div>

      <!-- Table -->
      <table class="min-w-full bg-white border">
        <thead>
          <tr>
            <th class="border px-4 py-2">#</th>
            <th class="border px-4 py-2">Bank</th>
            <th class="border px-4 py-2">Account</th>
            <th class="border px-4 py-2">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(account, index) in accounts.data" :key="account.id">
            <td class="border px-4 py-2">{{ index + 1 }}</td>
            <td class="border px-4 py-2">{{ bank.name }}</td>
            <td class="border px-4 py-2">{{ account.account }}</td>
            <td class="border px-4 py-2 flex gap-2">
              <button
                @click="openEditModal(account)"
                class="text-blue-600 hover:underline"
              >
                Edit
              </button>
              <button
                @click="deleteAccount(account.id)"
                class="text-red-600 hover:underline"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="mt-4">
        <button
          v-if="accounts.prev_page_url"
          @click="goToPage(accounts.prev_page_url)"
          class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 mr-2"
        >
          Prev
        </button>
        <button
          v-if="accounts.next_page_url"
          @click="goToPage(accounts.next_page_url)"
          class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
        >
          Next
        </button>
      </div>

      <!-- Modal -->
      <AddAccountModal
        :show="showModal"
        :onClose="closeModal"
        :bankId="bank.id"
        :accountToEdit="accountToEdit"
      />
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AddAccountModal from './AddAccountModal.vue'
import AppLayout from '@/Layouts/AppLayout.vue'

// Props from Inertia
const props = defineProps({
  bank: Object,
  accounts: Object,
})

// State
const showModal = ref(false)
const accountToEdit = ref(null)
const search = ref('')

// Methods
const openAddModal = () => {
  accountToEdit.value = null
  showModal.value = true
}

const openEditModal = (account) => {
  accountToEdit.value = account
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  accountToEdit.value = null
}

const searchAccounts = () => {
  router.get(route('admin.banks.show', props.bank.id), {
    search: search.value,
  }, {
    preserveState: true,
  })
}

const goToPage = (url) => {
  router.visit(url, { preserveState: true })
}

const deleteAccount = (id) => {
  if (confirm('Are you sure you want to delete this account?')) {
    router.delete(route('admin.accounts.destroy', id), {
      preserveScroll: true,
    })
  }
}
</script>
