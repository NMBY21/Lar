<template>
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-white p-8 shadow-lg">
            <nav class="space-y-2">
                <NavLink href="/dashboard" label="Dashboard" />

                <p class="mt-4 text-sm font-semibold">Orders</p>
                <NavLink href="/orders" label="Orders" />
                <NavLink href="/admin/expenses" label="Expense" />

                <p class="mt-4 text-sm font-semibold">Report</p>
                <NavLink href="/report" label="Report" />

                <p class="mt-4 text-sm font-semibold">Setting</p>
                <NavLink href="/admin/expense-types" label="Expense Type" />
                <NavLink href="/settings/vehicles" label="Vehicles" />
                <NavLink href="/admin/employees" label="Employees" />
                <NavLink href="/admin/locations" label="Location" />
                <NavLink href="/admin/load-types" label="Load Type" />
                <NavLink href="/admin/banks" label="Bank" />

                <p class="mt-4 text-sm font-semibold">User Management</p>
                <NavLink href="/admin/client" label="Clients" />
                <NavLink href="/admin/system-users" label="System Users" />
                <NavLink href="/admin/roles" label="Roles & Permissions" />
            </nav>
        </aside>

        <!-- Main Section: Top Navbar + Content -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- Top Navbar -->
            <UserNavbar />

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import NavLink from '@/Components/NavLink.vue';
import UserNavbar from '@/Components/UserNavbar.vue';
</script>
