<script setup>
import { Head } from '@inertiajs/vue3'
defineProps({ ranking: Object })
</script>

<template>
  <Head :title="ranking.title" />

  <section class="max-w-4xl mx-auto py-10 px-6">
    <!-- Info principal -->
    <div class="mb-6 text-center">
      <img
        v-if="ranking.image"
        :src="`/storage/${ranking.image}`"
        alt="Ranking image"
        class="w-full h-64 object-cover rounded-lg mb-4"
      />
      <h1 class="text-4xl font-bold mb-2">{{ ranking.title }}</h1>
      <p class="text-gray-600">{{ ranking.description }}</p>
    </div>

    <!-- Opcions -->
    <h2 class="text-2xl font-semibold mb-4">Opcions</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
      <div
        v-for="option in ranking.options"
        :key="option.id"
        class="bg-white shadow rounded-lg p-4 flex flex-col items-center"
      >
        <img
          v-if="option.image"
          :src="`/storage/${option.image}`"
          alt="Option image"
          class="w-24 h-24 object-cover rounded-full mb-3"
        />
        <h3 class="text-lg font-medium">{{ option.name }}</h3>
        <!-- aquí després pots afegir un botó de votar -->
      </div>
    </div>
  </section>
</template>
