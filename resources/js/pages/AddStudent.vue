<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 relative">
    <!-- Top left button -->
    <button @click="goToHome"
      class="absolute top-6 left-6 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded cursor-pointer shadow">
      Go to Homepage
    </button>
    <div class="bg-white p-8 rounded shadow text-center w-full max-w-md">
      <h2 class="text-2xl font-bold mb-4 text-green-600">
        {{ isEditMode ? "Edit Student" : "Add Student" }}
      </h2>
      <form @submit.prevent="handleSubmit">
        <div class="mb-4 text-left">
          <label class="block mb-1 font-medium text-gray-700">
            Name <span class="text-red-500">*</span>
          </label>
          <input v-model="form.name" type="text" class="form-input w-full border border-gray-300 rounded px-3 py-2"
            required />
        </div>
        <div class="mb-4 text-left">
          <label class="block mb-1 font-medium text-gray-700">
            Contact Number <span class="text-red-500">*</span>
          </label>
          <input v-model="form.contact_number" type="text"
            class="form-input w-full border border-gray-300 rounded px-3 py-2" required />
        </div>
        <!-- Custom Fields -->
        <div
          v-for="field in customFields"
          :key="field.id"
          class="mb-4 text-left"
        >
          <label class="block mb-1 font-medium text-gray-700">
            {{ field.name }}
            <span v-if="field.required" class="text-red-500">*</span>
          </label>
          <input
            v-model="form.custom_fields[field.id]"
            :type="field.data_type === 'number' ? 'number' : 'text'"
            class="form-input w-full border border-gray-300 rounded px-3 py-2"
            :required="field.required"
          />
        </div>
        <button type="submit"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded cursor-pointer w-full">
          {{ isEditMode ? "Update Student" : "Add Student" }}
        </button>
        <div v-if="message" class="mt-4 text-green-600">
          {{ message }}
        </div>
        <div v-if="error" class="mt-4 text-red-600">{{ error }}</div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
const router = useRouter();
const route = useRoute();

const form = ref({ name: "", contact_number: "", custom_fields: {} });
const message = ref("");
const error = ref("");
const isEditMode = ref(false);
const customFields = ref([]);

function goToHome() {
  router.push("/homepage");
}

async function fetchCustomFields() {
  try {
    const res = await fetch("/custom-fields");
    if (res.ok) {
      customFields.value = await res.json();
    }
  } catch (e) {
    customFields.value = [];
  }
}

async function fetchStudent(id) {
  try {
    const response = await fetch(`/students/${id}`);
    if (response.ok) {
      const data = await response.json();
      form.value = {
        name: data.name,
        contact_number: data.contact_number,
        custom_fields: { ...data.custom_fields }
      };
      isEditMode.value = true;
    } else {
      error.value = "Student not found.";
    }
  } catch (e) {
    error.value = "Error fetching student data.";
  }
}

async function handleSubmit() {
  message.value = "";
  error.value = "";

  // Convert all custom field values to strings
  const customFieldsString = {};
  for (const [key, val] of Object.entries(form.value.custom_fields)) {
    customFieldsString[key] = val !== null && val !== undefined ? String(val) : "";
  }
  const submitForm = {
    ...form.value,
    custom_fields: customFieldsString
  };

  try {
    let response;
    if (isEditMode.value && route.query.id) {
      response = await fetch(`/students/${route.query.id}`, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-TOKEN":
            document
              .querySelector("meta[name=csrf-token]")
              ?.getAttribute("content") || "",
        },
        body: JSON.stringify(submitForm),
      });
    } else {
      response = await fetch("/students", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-TOKEN":
            document
              .querySelector("meta[name=csrf-token]")
              ?.getAttribute("content") || "",
        },
        body: JSON.stringify(submitForm),
      });
    }
    if (response.ok) {
      message.value = isEditMode.value
        ? "Student updated successfully!"
        : "Student added successfully!";
      form.value = { name: "", contact_number: "", custom_fields: {} };
      setTimeout(() => router.push("/homepage"), 1000);
    } else {
      const err = await response.json();
      error.value =
        err.message ||
        (isEditMode.value
          ? "Failed to update student."
          : "Failed to add student.");
    }
  } catch (e) {
    error.value = isEditMode.value
      ? "Error occurred while updating student."
      : "Error occurred while adding student.";
  }
}

onMounted(async () => {
  await fetchCustomFields();
  if (route.query.id) {
    await fetchStudent(route.query.id);
  }
});
</script>

<style scoped>
/* Add any component-specific styles here */
</style>
