<script setup>
import { Link, router } from '@inertiajs/vue3'

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
    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">🏆 Els teus rànquings</h2>

    <div v-if="rankings.length" class="grid gap-4">
      <div
        v-for="ranking in rankings"
        :key="ranking.id"
        class="flex items-center justify-between bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl shadow-sm p-4 hover:shadow-md transition"
      >
        <Link
          :href="route('rankings.show', ranking.id)"
          class="flex-1 text-lg font-medium text-blue-600 dark:text-blue-400 hover:underline truncate min-w-0 pr-4"
        >
          {{ ranking.title }}
        </Link>

        <button
          @click="confirmDelete(ranking.id)"
          class="ml-4 px-3 py-1 bg-red-600 dark:bg-red-500 text-white rounded-lg hover:bg-red-700 dark:hover:bg-red-600 transition"
        >
          Eliminar
        </button>
      </div>
    </div>

    <p v-else class="text-gray-500 dark:text-gray-400 text-center">
      Encara no has creat cap rànquing.
    </p>
  </div>
</template>