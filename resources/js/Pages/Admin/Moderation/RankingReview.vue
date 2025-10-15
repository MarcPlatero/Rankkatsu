<script setup>
import { router } from '@inertiajs/vue3'

defineProps({
  ranking: Object
})

function approveRanking() {
  router.post(`/admin/moderation/${ranking.id}/approve`)
}

function rejectRanking() {
  if (confirm('Segur que vols eliminar aquest rànquing?')) {
    router.post(`/admin/moderation/${ranking.id}/reject`)
  }
}
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">{{ ranking.title }}</h1>
    <p class="text-gray-700 mb-2">Creat per: {{ ranking.user.name }}</p>
    <p class="text-gray-600 mb-6">{{ ranking.description }}</p>

    <div class="flex gap-3 mb-6">
      <button
        @click="approveRanking"
        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
      >
        Aprovar rànquing
      </button>

      <button
        @click="rejectRanking"
        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
      >
        Eliminar rànquing
      </button>
    </div>

    <h2 class="text-xl font-semibold mb-3">Opcions del rànquing</h2>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <div
        v-for="opt in ranking.options"
        :key="opt.id"
        class="border rounded-lg overflow-hidden bg-white"
      >
        <img
          v-if="opt.image"
          :src="opt.image"
          alt="Imatge opció"
          class="object-cover w-full h-40"
        />
        <div class="p-3">
          <p class="font-medium text-gray-800">{{ opt.name }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
