<script setup>
import { ref, watch } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

const emit = defineEmits(['removed'])

const props = defineProps({
  ranking: Object
})

const page = usePage()
const isFavorite = ref(!!props.ranking.is_favorite)
const animating = ref(false)

watch(() => props.ranking.is_favorite, (newVal) => {
  isFavorite.value = !!newVal
})

const toggleFavorite = () => {
  if (!page.props.auth?.user) {
    if(confirm('Has d’iniciar sessió per marcar favorits. Vols anar al login?')) {
        router.visit(route('login'))
    }
    return
  }

  const previousState = isFavorite.value
  isFavorite.value = !isFavorite.value
  animating.value = true

  router.post(`/rankings/${props.ranking.id}/favorite`, {}, {
    preserveScroll: true,
    onSuccess: () => {
      if (!isFavorite.value) {
        emit('removed', props.ranking.id)
      }
    },
    onError: () => {
      isFavorite.value = previousState
    },
    onFinish: () => {
      setTimeout(() => (animating.value = false), 300)
    }
  })
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
    class="transition transform hover:scale-110 focus:outline-none"
    :class="{ 'animate-pop': animating }"
    title="Afegir a favorits"
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