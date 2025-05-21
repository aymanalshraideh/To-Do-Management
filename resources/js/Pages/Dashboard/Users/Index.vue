<script setup>
import { ref } from 'vue'
import { router, Link, Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import UserFormModal from '@/Components/Users/UserFormModal.vue'

defineProps({ users: Object })

const showModal = ref(false)
const selectedUser = ref(null)

function deleteUser(id) {
    if (confirm('Are you sure?')) {
        router.delete(`/users/${id}`)
    }
}

function openAddUserModal() {
    selectedUser.value = null
    showModal.value = true
}

function openEditUserModal(user) {
    selectedUser.value = { ...user } // clone to avoid reactivity issues
    showModal.value = true
}
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Users</h2>
                <button @click="openAddUserModal" class="px-4 py-2 bg-blue-500 rounded text-white">+ Add User</button>
            </div>
        </template>

        <div class="flex justify-center items-center mt-6 p-5 bg-white rounded shadow-md">
            <table class="table-auto w-full mt-4">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users.data" :key="user.id">
                        <td class="text-center">{{ user.name }}</td>
                        <td class="text-center">{{ user.email }}</td>
                        <td class="text-center">
                            <button @click="openEditUserModal(user)" class="px-4 py-2 bg-green-500 rounded text-white ">Edit</button>
                            <button @click="deleteUser(user.id)" class="px-4 py-2 bg-red-500 rounded text-white  ml-2">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>

    <UserFormModal v-model="showModal" :user="selectedUser" />
</template>
