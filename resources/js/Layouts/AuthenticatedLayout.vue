<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingSidebar = ref(false);
</script>

<template>
    <div class="min-h-screen flex bg-gray-100 relative">
        <!-- Sidebar -->
        <aside
            :class="[
                'bg-white shadow-md sm:block sm:relative sm:w-64 transition-all duration-300 z-50',
                showingSidebar ? 'fixed inset-y-0 left-0 w-64 block' : 'hidden'
            ]"
        >
            <div class="p-4 border-b flex justify-between items-center">
                <Link :href="route('dashboard')">
                    <ApplicationLogo class="h-10 w-auto" />
                </Link>
                <!-- Close on small screens -->
                <button @click="showingSidebar = false" class="sm:hidden text-gray-500 hover:text-gray-700">
                    ✕
                </button>
            </div>

            <nav class="mt-4 space-y-2 px-4 flex flex-col">
                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</NavLink>
                <NavLink :href="route('admin.users.index')" :active="route().current('admin.users.index')">Users</NavLink>
                <NavLink :href="route('admin.tasks.index')" :active="route().current('admin.tasks.index')">Tasks</NavLink>
               
                <!-- Add more NavLink items here -->
            </nav>
        </aside>

        <!-- Optional overlay for mobile -->
        <div
            v-if="showingSidebar"
            class="fixed inset-0 bg-black bg-opacity-25 z-40 sm:hidden"
            @click="showingSidebar = false"
        ></div>

     
        <div class="flex-1 flex flex-col">
            <header class="bg-white border-b px-4 py-3 flex justify-between items-center shadow-sm">
                <div class="sm:hidden">
                    <button @click="showingSidebar = !showingSidebar" class="text-gray-500 hover:text-gray-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- User Dropdown -->
                <Dropdown align="right" width="48">
                    <template #trigger>
                        <button
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700">
                            {{ $page.props.auth.user.name }}
                            <svg class="ms-2 -me-0.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </template>
                    <template #content>
                        <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                        <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                    </template>
                </Dropdown>
            </header>

            <!-- Page Header -->
            <div class="bg-white shadow px-6 py-4" v-if="$slots.header">
                <slot name="header" />
            </div>

            <!-- Page Content -->
            <main class="p-6">
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
@media (max-width: 640px) {
    aside.fixed {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        z-index: 50;
        background-color: white;
    }
}
</style>