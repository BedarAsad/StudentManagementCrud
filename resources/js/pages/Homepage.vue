<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100">
        <h1 class="text-3xl font-bold text-green-600 mb-8">Student List</h1>

        <div class="flex gap-4 mb-8">
            <button
                @click="goToConfigure"
                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded cursor-pointer"
            >
                Configure Custom List
            </button>
            <button
                @click="goToAdd"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded cursor-pointer"
            >
                Add Student
            </button>
        </div>

        <!-- Loader -->
        <div v-if="loading" class="flex items-center justify-center mb-6">
            <svg class="animate-spin h-8 w-8 text-blue-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
            <span class="text-blue-500 font-medium">Loading...</span>
        </div>

        <table v-else-if="students.length" class="min-w-full bg-white rounded shadow mb-6">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b text-left">#</th>
                    <th class="px-4 py-2 border-b text-left">Name</th>
                    <th class="px-4 py-2 border-b text-left">Contact Number</th>
                    <th class="px-4 py-2 border-b text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(student, idx) in students" :key="student.id" class="hover:bg-gray-50">
                    <td class="px-4 py-2 border-b">{{ idx + 1 }}</td>
                    <td class="px-4 py-2 border-b">{{ student.name }}</td>
                    <td class="px-4 py-2 border-b">{{ student.contact_number }}</td>
                    <td class="px-4 py-2 border-b flex gap-2">
                        <!-- Edit Icon -->
                        <button @click="editStudent(student)" class="text-blue-500 hover:text-blue-700 cursor-pointer" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6-6m2 2l-6 6m-2 2h6a2 2 0 002-2v-6a2 2 0 00-2-2h-6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                            </svg>
                        </button>
                        <!-- Delete Icon -->
                        <button @click="deleteStudent(student.id)" class="text-red-500 hover:text-red-700 cursor-pointer" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-else class="text-gray-500 mb-6">No students found.</div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router'
const router = useRouter();

const students = ref([]);
const loading = ref(true);

function goToConfigure() {
    router.push('/configure-custom-list');
}
function goToAdd() {
    router.push('/add-student');
}

async function fetchStudents() {
    loading.value = true;
    try {
        const response = await fetch('/students');
        if (response.ok) {
            students.value = await response.json();
        }
    } catch (e) {
        students.value = [];
    }
    loading.value = false;
}

function editStudent(student) {
    router.push({ path: '/add-student', query: { id: student.id } });
}

// Delete functionality
async function deleteStudent(id) {
    if (!confirm('Are you sure you want to delete this student?')) return;
    try {
        const response = await fetch(`/students/${id}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || ''
            }
        });
        if (response.ok) {
            await fetchStudents();
        }
    } catch (e) {
        console.error('Error deleting student:', e);
        alert('Failed to delete student. Please try again.');
    }
}

onMounted(fetchStudents);
</script>