<script setup>
import { ref } from 'vue'
import { router, Link, Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import UserFormModal from '@/Components/Users/UserFormModal.vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const roles = page.props.auth.roles[0]
const permissions = page.props.auth.permissions

const props = defineProps({
    users: Object,
    roles: Array,
    permissions: Array,
})
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
    selectedUser.value = {
        ...user,
        role: user.roles?.[0]?.name || '',
        permissions: user.permissions?.map(p => p.name) || []
    }
    showModal.value = true
}
function hasPermission(permission) {
    return permissions.includes(permission)
}
</script>

<template>

    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Users</h2>
                <button v-if="hasPermission('create user')" @click="openAddUserModal"
                    class="px-4 py-2 bg-blue-500 rounded text-white">+ Add User</button>
            </div>
        </template>

        <div class="flex justify-center items-center mt-6 p-5 bg-white rounded shadow-md">
            <table class="table-auto w-full mt-4">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Permissions</th>
                        <th>Actions</th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users.data" :key="user.id">
                        <td class="text-center">{{ user.name }}</td>
                        <td class="text-center">{{ user.email }}</td>
                        <td class="text-center">
                            <span v-if="user.roles?.length">{{ user.roles[0].name }}</span>
                            <span v-else>No Role</span>
                        </td>

                        <td class="text-center w-[200px]">
                            <span class="flex justify-center flex-wrap w-full" v-if="user.permissions?.length">
                                <span v-for="p in user.permissions" :key="p.id"
                                    class="inline-block bg-gray-100 rounded px-2 py-1 text-xs mr-1">
                                    {{ p.name }}
                                </span>
                            </span>
                            <span v-else>No Permissions</span>
                        </td>

                        <td class="text-center" v-if="user.id != 1">
                            <button @click="openEditUserModal(user)" v-if="hasPermission('edit user')"
                                class="px-4 py-2 bg-green-500 rounded text-white ">Edit</button>
                            <button @click="deleteUser(user.id)" v-if="hasPermission('remove user')"
                                class="px-4 py-2 bg-red-500 rounded text-white  ml-2">Delete</button>
                        </td>
                        <td class="text-center text-gray-500 italic" v-else>
                            First admin cannot be edited
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>

    <UserFormModal v-model="showModal" :user="selectedUser" :permissions="props.permissions" :roles="props.roles" />
</template>
