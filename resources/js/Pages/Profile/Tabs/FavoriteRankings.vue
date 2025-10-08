<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import FavoriteStar from '@/Components/FavoriteStar.vue'

const props = defineProps({ favorites: Array })
const localFavorites = ref([...props.favorites])
const alertMessage = ref('')

// Quan s’elimina un favorit
const removeFavorite = (id) => {
  localFavorites.value = localFavorites.value.filter(r => r.id !== id)
  alertMessage.value = '❌ Has tret un rànquing dels favorits.'
  setTimeout(() => (alertMessage.value = ''), 3000)
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>

<template>
  <div>
    <h2 class="text-xl font-semibold mb-4">⭐ Rankings favorits</h2>

    <!-- Missatge d’alerta -->
    <transition name="fade">
      <div
        v-if="alertMessage"
        class="p-3 bg-red-100 text-red-700 rounded text-sm border border-red-200 mb-3"
      >
        {{ alertMessage }}
      </div>
    </transition>

    <div v-if="localFavorites.length" class="grid gap-4">
      <div
        v-for="ranking in localFavorites"
        :key="ranking.id"
        class="relative bg-white shadow rounded-lg p-4 hover:bg-blue-50 transition cursor-pointer group"
      >
        <!-- Fes tota la targeta clicable -->
        <Link
          :href="route('rankings.show', ranking.id)"
          class="absolute inset-0 z-0 rounded-lg"
        />

        <!-- Contingut -->
        <div class="flex items-center justify-between relative z-10">
          <!-- Nom del ranking també clicable -->
          <Link
            :href="route('rankings.show', ranking.id)"
            class="text-lg font-medium text-blue-600 hover:underline"
          >
            {{ ranking.title }}
          </Link>

          <!-- Estrella (evita que el clic s’expandeixi a la targeta) -->
          <div @click.stop>
            <FavoriteStar :ranking="ranking" @removed="removeFavorite" />
          </div>
        </div>
      </div>
    </div>

    <p v-else class="text-gray-500 text-center">
      Encara no tens cap ranking marcat com a favorit.
    </p>
  </div>
</template>
