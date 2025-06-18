<template>
    <div v-if="show" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white p-6 rounded shadow w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <h2 class="text-lg font-bold mb-4">
                {{ expense ? 'Edit' : 'Add' }} Expense
            </h2>

            <form @submit.prevent="submit">
                <!-- Expense Category -->
                <div class="mb-4">
                    <label class="block mb-1 text-sm">Expense Category</label>
                    <select v-model="selectedCategory" @change="filterExpenseTypes"
                        class="w-full border px-3 py-2 rounded">
                        <option value="" disabled>Select category</option>
                        <option v-for="cat in categories" :key="cat" :value="cat">
                            {{ cat }}
                        </option>
                    </select>
                </div>

                <!-- Expense Type -->
                <div class="mb-4">
                    <label class="block mb-1 text-sm">Expense Type</label>
                    <select v-model="form.expense_type_id" class="w-full border px-3 py-2 rounded">
                        <option value="">Select Type</option>
                        <option v-for="type in filteredExpenseTypes" :key="type.id" :value="type.id">
                            {{ type.name }}
                        </option>
                    </select>
                </div>

                <!-- Amount -->
                <div class="mb-4">
                    <label class="block mb-1 text-sm">Amount</label>
                    <input v-model="form.amount" type="number" step="0.01" class="w-full border px-3 py-2 rounded"
                        :class="{ 'border-red-500': form.errors.amount }" />
                    <div v-if="form.errors.amount" class="text-red-500 text-sm">{{ form.errors.amount }}</div>
                </div>

                <!-- Date -->
                <div class="mb-4">
                    <label class="block mb-1 text-sm">Date</label>
                    <input v-model="form.date" type="date" class="w-full border px-3 py-2 rounded"
                        :class="{ 'border-red-500': form.errors.date }" />
                    <div v-if="form.errors.date" class="text-red-500 text-sm">{{ form.errors.date }}</div>
                </div>

                <!-- From Bank -->
                <div class="mb-4">
                    <label class="block mb-1 text-sm">From Bank</label>
                    <select v-model="form.from_bank_id" @change="filterFromAccounts"
                        class="w-full border px-3 py-2 rounded">
                        <option value="" disabled>Select Bank</option>
                        <option v-for="bank in banks" :key="bank.id" :value="bank.id">{{ bank.name }}</option>
                    </select>
                    <div v-if="form.errors.from_bank_id" class="text-red-500 text-sm">{{ form.errors.from_bank_id }}
                    </div>
                </div>

                <!-- From Account -->
                <div class="mb-4">
                    <label class="block mb-1 text-sm">From Account</label>
                    <select v-model="form.from_account_id" class="w-full border px-3 py-2 rounded">
                        <option value="" disabled>Select Account</option>
                        <option v-for="account in filteredFromAccounts" :key="account.id" :value="account.id">
                            {{ account.account_number }}
                        </option>
                    </select>
                    <div v-if="form.errors.from_account_id" class="text-red-500 text-sm">{{ form.errors.from_account_id
                        }}</div>
                </div>

                <!-- To Bank -->
                <div class="mb-4">
                    <label class="block mb-1 text-sm">To Bank</label>
                    <select v-model="form.to_bank_id" @change="filterToAccounts"
                        class="w-full border px-3 py-2 rounded">
                        <option value="" disabled>Select Bank</option>
                        <option v-for="bank in banks" :key="bank.id" :value="bank.id">{{ bank.name }}</option>
                    </select>
                    <div v-if="form.errors.to_bank_id" class="text-red-500 text-sm">{{ form.errors.to_bank_id }}</div>
                </div>

                <!-- To Account -->
                <div class="mb-4">
                    <label class="block mb-1 text-sm">To Account</label>
                    <select v-model="form.to_account_id" class="w-full border px-3 py-2 rounded">
                        <option value="" disabled>Select Account</option>
                        <option v-for="account in filteredToAccounts" :key="account.id" :value="account.id">
                            {{ account.account_number }}
                        </option>
                    </select>
                    <div v-if="form.errors.to_account_id" class="text-red-500 text-sm">{{ form.errors.to_account_id }}
                    </div>
                </div>

                <!-- Payment Type -->
                <div class="mb-4">
                    <label class="block mb-1 text-sm">Payment Type</label>
                    <select v-model="form.payment_type" class="w-full border px-3 py-2 rounded">
                        <option value="Cash">Cash</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Transfer">Transfer</option>
                    </select>
                    <div v-if="form.errors.payment_type" class="text-red-500 text-sm">{{ form.errors.payment_type }}
                    </div>
                </div>

                <!-- File Upload -->
                <div class="mb-4">
                    <label class="block mb-1 text-sm">Attach File</label>
                    <input type="file" @change="handleFileUpload" class="w-full border px-3 py-2 rounded" />
                    <div v-if="form.errors.file" class="text-red-500 text-sm">{{ form.errors.file }}</div>
                </div>

                <!-- Remark -->
                <div class="mb-4">
                    <label class="block mb-1 text-sm">Remark</label>
                    <textarea v-model="form.remark" class="w-full border px-3 py-2 rounded" rows="3"></textarea>
                    <div v-if="form.errors.remark" class="text-red-500 text-sm">{{ form.errors.remark }}</div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="onClose"
                        class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                        {{ expense ? 'Update' : 'Submit' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    show: Boolean,
    onClose: Function,
    expense: Object,
    expenseTypes: Array,
    banks: Array,
    accounts: Array,
})

const form = useForm({
    expense_type_id: props.expense?.expense_type_id || '',
    amount: props.expense?.amount || '',
    date: props.expense?.date || '',
    from_bank_id: props.expense?.from_bank_id || '',
    from_account_id: props.expense?.from_account_id || '',
    to_bank_id: props.expense?.to_bank_id || '',
    to_account_id: props.expense?.to_account_id || '',
    payment_type: props.expense?.payment_type || 'Cash',
    remark: props.expense?.remark || '',
    file: null,
})

const selectedCategory = ref('')
const filteredExpenseTypes = ref([])
const filteredFromAccounts = ref([])
const filteredToAccounts = ref([])

const categories = computed(() => {
    return [...new Set(props.expenseTypes.map(e => e.category))]
})

const filterExpenseTypes = () => {
    filteredExpenseTypes.value = props.expenseTypes.filter(type => type.category === selectedCategory.value)
    form.expense_type_id = ''
}

const filterFromAccounts = () => {
    filteredFromAccounts.value = props.accounts.filter(acc => acc.bank_id === form.from_bank_id)
}

const filterToAccounts = () => {
    filteredToAccounts.value = props.accounts.filter(acc => acc.bank_id === form.to_bank_id)
}

const handleFileUpload = (e) => {
    form.file = e.target.files[0]
}

const submit = () => {
    const url = props.expense
        ? route('expenses.update', props.expense.id)
        : route('expenses.store')

    form.post(url, {
        forceFormData: true,
        onSuccess: () => props.onClose(),
    })
}

watch(() => props.expense, (newVal) => {
    if (newVal) {
        const selectedType = props.expenseTypes.find(t => t.id === newVal.expense_type_id)
        selectedCategory.value = selectedType?.category || ''
        filteredExpenseTypes.value = props.expenseTypes.filter(type => type.category === selectedCategory.value)
        form.expense_type_id = newVal.expense_type_id

        filterFromAccounts()
        filterToAccounts()
    }
}, { immediate: true })

watch(() => props.show, (val) => {
    if (!val) {
        form.reset()
        form.clearErrors()
        selectedCategory.value = ''
        filteredExpenseTypes.value = []
        filteredFromAccounts.value = []
        filteredToAccounts.value = []
    } else {
        if (!props.expense) {
            selectedCategory.value = categories.value[0] || ''
            filterExpenseTypes()
        }
    }
})

watch(selectedCategory, filterExpenseTypes)
</script>
