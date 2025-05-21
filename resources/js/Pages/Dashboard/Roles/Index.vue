<template>
  <Head title="Roles" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Roles</h2>
        <button
          @click="openAddRoleModal"
          class="px-4 py-2 bg-green-500 rounded text-white"
        >
          + Add Role
        </button>
      </div>
    </template>

    <div>
      <table class="table-auto w-full mt-4">
        <thead>
          <tr>
            <th>Name</th>
            <th>Permissions</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="role in roles.data" :key="role.id">
            <td>{{ role.name }}</td>
            <td>
              <span
                v-for="perm in role.permissions"
                :key="perm.id"
                class="inline-block bg-blue-100 text-blue-700 px-2 py-1 rounded mr-1 text-xs"
                >{{ perm.name }}</span
              >
            </td>
            <td>
              <button
                @click="openEditRoleModal(role)"
                class="text-blue-500 mr-2"
              >
                Edit
              </button>
              <button @click="deleteRole(role.id)" class="text-red-500">
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AuthenticatedLayout>

  <RoleFormModal
    v-model="showModal"
    :role="selectedRole"
    :permissions="permissions"
  />
</template>

<script setup>
import { ref } from 'vue'
import { router, Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import RoleFormModal from '@/Components/Roles/RoleFormModal.vue'

defineProps({
  roles: Object,
  permissions: Array,
})

const showModal = ref(false)
const selectedRole = ref(null)

function openAddRoleModal() {
  selectedRole.value = null
  showModal.value = true
}

function openEditRoleModal(role) {
  selectedRole.value = { ...role }
  showModal.value = true
}

function deleteRole(id) {
  if (confirm('Are you sure?')) {
    router.delete(`/roles/${id}`)
  }
}
</script>
