<script setup>
import { usePage, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()
const favorites = computed(() => page.props.favorites || [])

const removeFavorite = (id) => {
  router.post(route('rankings.toggleFavorite', id))
}
</script>

<template>
  <div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">⭐ Rankings favorits</h2>

    <div v-if="favorites.length">
      <ul class="space-y-3">
        <li
          v-for="ranking in favorites"
          :key="ranking.id"
          class="flex justify-between items-center p-4 border rounded-lg shadow-sm hover:shadow-md transition"
        >
          <div>
            <h3 class="font-medium text-lg">{{ ranking.title }}</h3>
            <p class="text-gray-500 text-sm">De: {{ ranking.user.name }}</p>
          </div>

          <button
            class="text-red-600 hover:text-red-800 font-medium"
            @click="removeFavorite(ranking.id)"
          >
            Elimina
          </button>
        </li>
      </ul>
    </div>

    <div v-else class="text-gray-500 mt-6 text-center">
      Encara no tens cap ranking marcat com a favorit.
    </div>
  </div>
</template>
