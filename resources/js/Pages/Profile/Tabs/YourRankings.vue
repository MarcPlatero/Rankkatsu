<script setup>
import FavoriteStar from '@/Components/FavoriteStar.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  rankings: Array
})
</script>

<template>
  <div>
    <h2 class="text-xl font-semibold mb-4">🏆 Els teus rankings</h2>

    <div v-if="rankings.length" class="grid gap-4">
      <div
        v-for="ranking in rankings"
        :key="ranking.id"
        class="flex items-center justify-between bg-white shadow rounded-lg p-4 hover:bg-gray-50 transition"
      >
        <Link
          :href="route('rankings.show', ranking.id)"
          class="text-lg font-medium text-blue-600 hover:underline"
        >
          {{ ranking.title }}
        </Link>

        <FavoriteStar :ranking="ranking" />
      </div>
    </div>

    <p v-else class="text-gray-500 text-center">Encara no has creat cap ranking.</p>
  </div>
</template>
