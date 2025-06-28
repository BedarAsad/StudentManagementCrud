<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 relative">
    <!-- Top left button -->
    <button
      @click="goToHome"
      class="absolute top-6 left-6 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded cursor-pointer shadow"
    >
      Go to Homepage
    </button>
    <div class="bg-white p-8 rounded shadow text-center">
      <h2 class="text-2xl font-bold mb-4 text-blue-600">Configure Custom List</h2>
      <p class="text-gray-600 mb-6">This page is under construction.</p>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
const router = useRouter();

function goToHome() {
  router.push('/homepage');
}
</script>