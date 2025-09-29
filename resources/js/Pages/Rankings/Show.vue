<script setup>
import { Head, router } from '@inertiajs/vue3'
defineProps({ ranking: Object })

const vote = (optionId) => {
  router.post(`/rankings/${ranking.id}/vote`, { option_id: optionId })
}
</script>

<template>
  <Head :title="ranking.title" />

  <div class="max-w-3xl mx-auto py-10 px-6">
    <!-- Imatge -->
    <img
      v-if="ranking.image"
      :src="`/storage/${ranking.image}`"
      alt="Ranking image"
      class="w-full h-64 object-cover rounded-lg mb-6"
    />

    <!-- Títol i descripció -->
    <h1 class="text-3xl font-bold mb-2">{{ ranking.title }}</h1>
    <p class="text-gray-600 mb-6">{{ ranking.description }}</p>

    <!-- Opcions per votar -->
    <div class="space-y-4">
      <div
        v-for="opt in ranking.options"
        :key="opt.id"
        class="flex items-center justify-between p-4 bg-white shadow rounded-lg"
      >
        <div class="flex items-center gap-4">
          <img
            v-if="opt.image"
            :src="`/storage/${opt.image}`"
            alt="Option"
            class="w-16 h-16 object-cover rounded"
          />
          <span class="font-medium">{{ opt.name }}</span>
        </div>
        <button
          @click="vote(opt.id)"
          class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
          Votar
        </button>
      </div>
    </div>
  </div>
</template>
