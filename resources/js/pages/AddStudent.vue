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

const form = ref({ name: "", contact_number: "" });
const message = ref("");
const error = ref("");
const isEditMode = ref(false);

function goToHome() {
  router.push("/homepage");
}

async function fetchStudent(id) {
  try {
    const response = await fetch(`/students/${id}`);
    if (response.ok) {
      const data = await response.json();
      console.log("Fetched student data:", data);
      form.value = {
        name: data.name,
        contact_number: data.contact_number,
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
  try {
    let response;
    if (isEditMode.value && route.query.id) {
      // Edit mode: update student
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
        body: JSON.stringify(form.value),
      });
    } else {
      // Add mode: create student
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
        body: JSON.stringify(form.value),
      });
    }
    if (response.ok) {
      message.value = isEditMode.value
        ? "Student updated successfully!"
        : "Student added successfully!";
      form.value = { name: "", contact_number: "" };
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

onMounted(() => {
  if (route.query.id) {
    fetchStudent(route.query.id);
  }
});
</script>
