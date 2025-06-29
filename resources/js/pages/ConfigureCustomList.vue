<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 relative">
        <!-- Top left button -->
        <button
            @click="goToHome"
            class="absolute top-6 left-6 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded cursor-pointer shadow"
        >
            Go to Homepage
        </button>
        <div class="w-full max-w-2xl">
            <!-- Add Custom Field Section -->
            <div class="bg-white p-8 rounded shadow text-center mb-8">
                <h2 class="text-2xl font-bold mb-4 text-blue-600">Add New Custom Field</h2>
                <form @submit.prevent="addCustomField" class="flex flex-col md:flex-row gap-4 mb-4 items-end justify-center">
                    <div class="w-full md:w-1/2">
                        <label class="block mb-1 font-medium text-gray-700">Field Name <span class="text-red-500">*</span></label>
                        <input v-model="form.name" type="text" class="form-input w-full border border-gray-300 rounded px-3 py-2" required />
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="block mb-1 font-medium text-gray-700">Data Type <span class="text-red-500">*</span></label>
                        <select v-model="form.data_type" class="form-select w-full border border-gray-300 rounded px-3 py-2" required>
                            <option value="">Select</option>
                            <option value="string">String</option>
                            <option value="number">Number</option>
                            <option value="date">Date</option>
                            <option value="boolean">Boolean</option>
                        </select>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="block mb-1 font-medium text-gray-700">Required</label>
                        <input v-model="form.required" type="checkbox" class="form-checkbox w-full border border-gray-300 rounded px-3 py-2" />
                    </div>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded cursor-pointer min-w-[100px]">
                        {{ editId ? 'Update' : 'Add' }}
                    </button>
                </form>
                <div v-if="message" class="mb-2 text-green-600">{{ message }}</div>
                <div v-if="error" class="mb-2 text-red-600">{{ error }}</div>
            </div>

            <!-- Custom Fields Table Section -->
            <div class="bg-white p-8 rounded shadow text-center">
                <h2 class="text-2xl font-bold mb-4 text-blue-600">Custom Fields</h2>
                <table class="min-w-full bg-white rounded shadow mb-6">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border-b text-left">#</th>
                            <th class="px-4 py-2 border-b text-left">Name</th>
                            <th class="px-4 py-2 border-b text-left">Data Type</th>
                            <th class="px-4 py-2 border-b text-left">Required</th>
                            <th class="px-4 py-2 border-b text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(field, idx) in customFields" :key="field.id" class="hover:bg-gray-50">
                            <td class="px-4 py-2 border-b">{{ idx + 1 }}</td>
                            <td class="px-4 py-2 border-b">{{ field.name }}</td>
                            <td class="px-4 py-2 border-b">{{ field.data_type }}</td>
                            <td class="px-4 py-2 border-b text-center">
                                <input type="checkbox" :checked="field.required" disabled />
                            </td>
                            <td class="px-4 py-2 border-b flex gap-2 justify-center">
                                <button @click="editField(field)" class="text-blue-500 hover:text-blue-700 cursor-pointer" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536M9 13l6-6m2 2l-6 6m-2 2h6a2 2 0 002-2v-6a2 2 0 00-2-2h-6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                    </svg>
                                </button>
                                <button @click="deleteField(field.id)" class="text-red-500 hover:text-red-700 cursor-pointer" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="!customFields.length">
                            <td colspan="5" class="py-4 text-gray-500">No custom fields found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
const router = useRouter();

const customFields = ref([]);
const form = ref({ name: '', data_type: '', required: false });
const editId = ref(null);
const message = ref('');
const error = ref('');

function goToHome() {
    router.push('/homepage');
}

async function fetchCustomFields() {
    const res = await fetch('/custom-fields');
    if (res.ok) {
        customFields.value = await res.json();
    }
}

async function addCustomField() {
    message.value = '';
    error.value = '';
    try {
        let url = '/custom-fields';
        let method = 'POST';
        if (editId.value) {
            url += `/${editId.value}`;
            method = 'PUT';
        }
        const res = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(form.value)
        });
        if (res.ok) {
            message.value = editId.value ? 'Custom field updated!' : 'Custom field added!';
            form.value = { name: '', data_type: '', required: false }; 
            editId.value = null;
            await fetchCustomFields();
        } else {
            const err = await res.json();
            error.value = err.message || 'Failed to save custom field.';
        }
    } catch (e) {
        error.value = 'Error occurred while saving custom field.';
    }
}

function editField(field) {
    form.value = { name: field.name, data_type: field.data_type, required: field.required };
    editId.value = field.id;
    message.value = '';
    error.value = '';
}

async function deleteField(id) {
    if (!confirm('Are you sure you want to delete this field?')) return;
    const res = await fetch(`/custom-fields/${id}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || ''
        }
    });
    if (res.ok) {
        await fetchCustomFields();
    }
}

onMounted(fetchCustomFields);
</script>