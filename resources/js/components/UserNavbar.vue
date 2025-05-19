<template>
    <header class="flex justify-between items-center px-6 py-4 bg-white border-b shadow-sm relative">
        <h1 class="text-xl font-semibold text-gray-800">Selamawit Transport</h1>

        <div class="relative" ref="dropdown">
            <button @click="dropdownOpen = !dropdownOpen"
                class="flex items-center gap-2 text-gray-700 font-medium hover:text-gray-900 focus:outline-none">
                <span>{{ user.name }}</span>
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div v-show="dropdownOpen"
                class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                <a href="/settings/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Settings
                </a>
                <button @click="logout" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                    Logout
                </button>
            </div>
        </div>
    </header>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

const user = usePage().props.auth.user
const dropdownOpen = ref(false)
const dropdown = ref(null)

const handleClickOutside = (event) => {
    if (dropdown.value && !dropdown.value.contains(event.target)) {
        dropdownOpen.value = false
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside)
})

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside)
})

const logout = () => {
    router.post('/logout')
}
</script>
