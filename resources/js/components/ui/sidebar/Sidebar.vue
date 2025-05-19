<template>
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside v-show="isSidebarOpen"
            class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg p-8 transform transition-transform duration-300 ease-in-out md:relative md:translate-x-0"
            :class="{ '-translate-x-full': !isSidebarOpen }">
            <nav class="space-y-2">
                <NavLink href="/dashboard" label="Dashboard" />
                <!-- ... other nav links -->
            </nav>
        </aside>

        <!-- Overlay (for mobile) -->
        <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-30 md:hidden">
        </div>

        <!-- Main Section: Top Navbar + Content -->
        <div class="flex-1 flex flex-col overflow-hidden transition-margin duration-300"
            :class="{ 'md:ml-64': isSidebarOpen }">
            <!-- Pass toggle event down -->
            <UserNavbar @toggle-sidebar="isSidebarOpen = !isSidebarOpen" />

            <main class="flex-1 overflow-y-auto p-6">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import NavLink from '@/Components/NavLink.vue'
import UserNavbar from '@/Components/UserNavbar.vue'

const isSidebarOpen = ref(true)
</script>
