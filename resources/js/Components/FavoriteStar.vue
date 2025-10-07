<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { usePage } from '@inertiajs/vue3'

const emit = defineEmits(['removed']) // <-- afegim l'event per avisar al pare

const props = defineProps({
  ranking: Object
})

const page = usePage()
const isFavorite = ref(!!props.ranking.is_favorite)
const animating = ref(false)

const toggleFavorite = async () => {
  if (!page.props.auth?.user) {
    alert('Has d’iniciar sessió per marcar favorits.')
    return
  }

  animating.value = true

  try {
    const response = await axios.post(`/rankings/${props.ranking.id}/favorite`)
    isFavorite.value = response.data.favorited

    // ⚡ Si s'ha desfavorit i estem dins la pestanya de favorits → eliminar de la llista
    if (!response.data.favorited) {
      emit('removed', props.ranking.id)
    }
  } catch (error) {
    console.error('Error al canviar el favorit:', error)
  } finally {
    setTimeout(() => (animating.value = false), 300)
  }
}
</script>

<style scoped>
@keyframes pop {
  0% { transform: scale(1); }
  50% { transform: scale(1.4); }
  100% { transform: scale(1); }
}
.animate-pop {
  animation: pop 0.3s ease-in-out;
}
</style>

<template>
  <button
    @click="toggleFavorite"
    class="transition transform hover:scale-110"
    :class="{ 'animate-pop': animating }"
  >
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 24 24"
      fill="currentColor"
      class="w-7 h-7 transition-colors duration-300"
      :class="isFavorite ? 'text-yellow-400' : 'text-gray-400 hover:text-yellow-300'"
    >
      <path
        d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"
      />
    </svg>
  </button>
</template>
