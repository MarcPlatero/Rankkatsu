<template>
  <div class="space-y-3">
    <div
      v-for="ranking in localFavorites"
      :key="ranking.id"
      class="flex items-center justify-between bg-white shadow rounded-lg p-4 hover:bg-blue-50 transition"
    >
      <Link
        :href="route('rankings.show', ranking.id)"
        class="text-lg font-medium text-blue-600 hover:underline"
      >
        {{ ranking.title }}
      </Link>

      <FavoriteStar :ranking="ranking" @removed="removeFavorite" />
    </div>
  </div>
</template>

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
