<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import FavoriteStar from '@/Components/FavoriteStar.vue'

const props = defineProps({ favorites: Array })
const localFavorites = ref([...props.favorites])
const alertMessage = ref('')

const removeFavorite = (id) => {
  localFavorites.value = localFavorites.value.filter(r => r.id !== id)
  alertMessage.value = '❌ Has tret un rànquing dels favorits.'
  setTimeout(() => (alertMessage.value = ''), 3000)
}
</script>

<template>
  <div>
    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">⭐ Rankings favorits</h2>

    <transition name="fade">
      <div
        v-if="alertMessage"
        class="p-3 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded text-sm border border-red-200 dark:border-red-800 mb-3"
      >
        {{ alertMessage }}
      </div>
    </transition>

    <div v-if="localFavorites.length" class="grid gap-4">
      <div
        v-for="ranking in localFavorites"
        :key="ranking.id"
        class="relative bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-sm rounded-xl p-4 hover:shadow-md transition group"
      >
        <Link
          :href="route('rankings.show', ranking.id)"
          class="absolute inset-0 z-0 rounded-xl"
        />

        <div class="flex items-center justify-between relative z-10">
          <Link
            :href="route('rankings.show', ranking.id)"
            class="text-lg font-medium text-blue-600 dark:text-blue-400 hover:underline"
          >
            {{ ranking.title }}
          </Link>

          <div @click.stop>
            <FavoriteStar :ranking="ranking" @removed="removeFavorite" />
          </div>
        </div>
      </div>
    </div>

    <p v-else class="text-gray-500 dark:text-gray-400 text-center">
      Encara no tens cap rànquing marcat com a favorit.
    </p>
  </div>
</template>
