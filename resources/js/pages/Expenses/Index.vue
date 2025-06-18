<template>
  <AppLayout>
    <div>
      <!-- Search and Add Expense Button -->
      <div class="flex justify-between items-center mb-4">
        <input
          v-model="search"
          @input="fetchExpenses"
          placeholder="Search..."
          class="border px-2 py-1 rounded w-64"
        />
        <button
          @click="showModal = true"
          class="bg-blue-600 text-white px-4 py-2 rounded"
        >
          Add Expense
        </button>
      </div>

      <!-- Date Range Filter -->
      <div class="mb-4">
        <label class="block mb-1">Date Range</label>
        <div class="flex items-center gap-2">
          <input
            type="date"
            v-model="filters.from"
            class="border px-2 py-1 rounded"
          />
          <input
            type="date"
            v-model="filters.to"
            class="border px-2 py-1 rounded"
          />
          <button
            @click="fetchExpenses"
            class="bg-green-600 text-white px-3 py-1 rounded"
          >
            Get Report
          </button>
        </div>
      </div>

      <!-- Expenses Table -->
      <table class="w-full border mb-4">
        <thead>
          <tr class="bg-gray-200">
            <th class="text-left p-2">#</th>
            <th class="text-left p-2">Category</th>
            <th class="text-left p-2">Type</th>
            <th class="text-left p-2">Amount</th>
            <th class="text-left p-2">Date</th>
            <th class="text-left p-2">From Bank</th>
            <th class="text-left p-2">From Account</th>
            <th class="text-left p-2">To Bank</th>
            <th class="text-left p-2">To Account</th>
            <th class="text-left p-2">Payment Type</th>
            <th class="text-left p-2">File</th>
            <th class="text-left p-2">Remark</th>
            <th class="text-left p-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(expense, index) in expenses.data"
            :key="expense.id"
            class="border-t"
          >
            <td class="p-2">{{ index + 1 }}</td>
            <!-- <td class="p-2">{{ expense.expense_type?.categories }}</td> -->
             <td>
                <span v-for="cat in expense.expense_type?.categories" :key="cat" class="mr-1">
                    {{ cat }}
                </span>
            </td>
            <td class="p-2">{{ expense.expense_type?.name }}</td>
            <td class="p-2">{{ expense.amount }}</td>
            <td class="p-2">{{ expense.date }}</td>
            <td class="p-2">{{ expense.from_bank?.name }}</td>
            <td class="p-2">{{ expense.from_account?.account }}</td>
            <td class="p-2">{{ expense.to_bank?.name }}</td>
            <td class="p-2">{{ expense.to_account}}</td>
            <td class="p-2">{{ expense.payment_type }}</td>
            <td class="p-2">
              <a
                v-if="expense.file_path"
                :href="`/storage/${expense.file_path}`"
                target="_blank"
                class="text-blue-600 underline"
              >
                View
              </a>
            </td>
            <td class="p-2">{{ expense.remark }}</td>
            <td class="p-2 space-x-2">
              <button @click="editExpense(expense)" class="text-blue-600">
                Edit
              </button>
              <button @click="deleteExpense(expense.id)" class="text-red-600">
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <Pagination :links="expenses.links" />

      <!-- Add/Edit Expense Modal -->
      <ExpenseModal
        v-if="showModal"
        :show="showModal"
        :onClose="closeModal"
        :expense="editingExpense"
        :expenseTypes="expenseTypes"
        :banks="banks"
        :accounts="accounts"
      />
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import ExpenseModal from './ExpenseModal.vue';

const props = defineProps({
  expenses: Object,
  expenseTypes: Array,
  banks: Array,
  accounts: Array,
  filters: Object,
});

const search = ref(props.filters.search || '');
const filters = ref({
  from: props.filters.from || '',
  to: props.filters.to || '',
});

const showModal = ref(false);
const editingExpense = ref(null);

const fetchExpenses = () => {
  router.get(
    route('expenses.index'),
    {
      search: search.value,
      from: filters.value.from,
      to: filters.value.to,
    },
    {
      preserveState: true,
      replace: true,
    }
  );
};

const editExpense = (expense) => {
  editingExpense.value = expense;
  showModal.value = true;
};

const deleteExpense = (id) => {
  if (confirm('Are you sure you want to delete this expense?')) {
    router.delete(route('expenses.destroy', id));
  }
};

const closeModal = () => {
  showModal.value = false;
  editingExpense.value = null;
};
</script>
