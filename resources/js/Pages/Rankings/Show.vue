<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  ranking: Object
})
</script>

<template>
  <div class="max-w-3xl mx-auto p-6 bg-white shadow rounded">
    <!-- Títol -->
    <h1 class="text-3xl font-bold mb-4">{{ ranking.title }}</h1>

    <!-- Imatge -->
    <img v-if="ranking.image"
         :src="`/storage/${ranking.image}`"
         alt="Ranking image"
         class="w-full h-64 object-cover rounded mb-4" />

    <!-- Descripció -->
    <p class="text-gray-700 mb-6">{{ ranking.description }}</p>

    <!-- Opcions -->
    <h2 class="text-xl font-semibold mb-2">Opcions</h2>
    <ul class="space-y-4">
      <li v-for="opt in ranking.options" :key="opt.id"
          class="flex items-center gap-4 p-3 border rounded">
        <img v-if="opt.image"
             :src="`/storage/${opt.image}`"
             alt="Option image"
             class="w-16 h-16 object-cover rounded" />
        <span class="text-lg">{{ opt.name }}</span>
      </li>
    </ul>

    <!-- Placeholder per quan implementem votacions -->
    <div class="mt-6">
      <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Votar 🔥
      </button>
    </div>

    <!-- Tornar enrere -->
    <div class="mt-6">
      <Link href="/rankings" class="text-blue-500 hover:underline">⬅ Tornar als rànquings</Link>
    </div>
  </div>
</template>
