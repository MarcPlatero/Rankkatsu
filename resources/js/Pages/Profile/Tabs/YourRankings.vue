<script setup>
import { Link } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  rankings: Array
})

const confirmDelete = (rankingId) => {
  if (confirm('⚠️ Estàs segur que vols eliminar aquest rànquing? Aquesta acció no es podrà desfer.')) {
    router.delete(route('rankings.destroy', rankingId))
  }
}
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
          class="flex-1 text-lg font-medium text-blue-600 hover:underline"
        >
          {{ ranking.title }}
        </Link>

        <button
          @click="confirmDelete(ranking.id)"
          class="ml-4 px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition"
        >
          Eliminar
        </button>
      </div>
    </div>

    <p v-else class="text-gray-500 text-center">
      Encara no has creat cap ranking.
    </p>
  </div>
</template>
