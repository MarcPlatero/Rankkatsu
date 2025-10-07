<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import FavoriteStar from '@/Components/FavoriteStar.vue'

const props = defineProps({ favorites: Array })
const localFavorites = ref([...props.favorites])

const removeFavorite = (id) => {
  localFavorites.value = localFavorites.value.filter(r => r.id !== id)
}
</script>

<template>
  <div>
    <h2 class="text-xl font-semibold mb-4">⭐ Rankings favorits</h2>

    <div v-if="localFavorites.length" class="grid gap-4">
      <div
        v-for="ranking in localFavorites"
        :key="ranking.id"
        class="relative bg-white shadow rounded-lg p-4 hover:bg-blue-50 transition cursor-pointer"
      >
        <!-- Fes tota la targeta clicable -->
        <Link
          :href="route('rankings.show', ranking.id)"
          class="absolute inset-0 z-0 rounded-lg"
        />

        <!-- Contingut del ranking -->
        <div class="flex items-center justify-between relative z-10">
          <span class="text-lg font-medium text-blue-600 hover:underline">
            {{ ranking.title }}
          </span>
          <FavoriteStar :ranking="ranking" @removed="removeFavorite" />
        </div>
      </div>
    </div>

    <p v-else class="text-gray-500 text-center">
      Encara no tens cap ranking marcat com a favorit.
    </p>
  </div>
</template>
