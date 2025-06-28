<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100">
        <h1 class="text-3xl font-bold text-green-600 mb-4">Student List</h1>

        <!-- Add Student Form -->
        <form @submit.prevent="addStudent" class="bg-white p-6 rounded shadow mb-8 w-full max-w-md">
            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Name</label>
                <input v-model="form.name" type="text" class="form-input w-full border border-gray-300 rounded px-3 py-2" required />
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Contact Number</label>
                <input v-model="form.contact_number" type="text" class="form-input w-full border border-gray-300 rounded px-3 py-2" required />
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add Student
            </button>
            <div v-if="message" class="mt-2 text-green-600">{{ message }}</div>
            <div v-if="error" class="mt-2 text-red-600">{{ error }}</div>
        </form>

        <!-- Student Table -->
        <table v-if="students.length" class="min-w-full bg-white rounded shadow mb-6">
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
                        <button @click="editStudent(student)" class="text-blue-500 hover:text-blue-700" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6-6m2 2l-6 6m-2 2h6a2 2 0 002-2v-6a2 2 0 00-2-2h-6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                            </svg>
                        </button>
                        <!-- Delete Icon -->
                        <button @click="deleteStudent(student.id)" class="text-red-500 hover:text-red-700" title="Delete">
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

const students = ref([]);
const form = ref({ name: '', contact_number: '' });
const message = ref('');
const error = ref('');

async function fetchStudents() {
    try {
        const response = await fetch('/students');
        if (response.ok) {
            students.value = await response.json();
        }
    } catch (e) {
        students.value = [];
    }
}

async function addStudent() {
    message.value = '';
    error.value = '';
    try {
        const response = await fetch('/students', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(form.value)
        });
        if (response.ok) {
            const data = await response.json();
            message.value = data.message || 'Student added successfully!';
            form.value.name = '';
            form.value.contact_number = '';
            await fetchStudents();
        } else {
            const err = await response.json();
            error.value = err.message || 'Failed to add student.';
        }
    } catch (e) {
        error.value = 'Error occurred while adding student.';
    }
}

// Placeholder for edit functionality
function editStudent(student) {
    alert('Edit functionality not implemented yet.');
}

// Delete functionality
async function deleteStudent(id) {
    if (!confirm('Are you sure you want to delete this student?')) return;
    message.value = '';
    error.value = '';
    try {
        const response = await fetch(`/students/${id}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || ''
            }
        });
        if (response.ok) {
            message.value = 'Student deleted successfully!';
            await fetchStudents();
        } else {
            const err = await response.json();
            error.value = err.message || 'Failed to delete student.';
        }
    } catch (e) {
        error.value = 'Error occurred while deleting student.';
    }
}

onMounted(fetchStudents);
</script>

<style scoped>
div {
    font-family: sans-serif;
    padding: 20px;
    text-align: center;
}
h1 {
    color: #42b983;
}
</style>