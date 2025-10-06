<template>
  <button @click="toggleFavorite" class="absolute top-3 right-3 focus:outline-none">
    <svg
      xmlns="http://www.w3.org/2000/svg"
      :class="[
        'w-7 h-7 transition-all duration-300',
        isFavorited
          ? 'fill-yellow-400 stroke-yellow-400 scale-110'
          : 'fill-none stroke-gray-400 hover:stroke-yellow-400'
      ]"
      viewBox="0 0 24 24"
      stroke-width="2"
    >
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        d="M11.48 3.499a.562.562 0 011.04 0l2.168 4.391a.563.563 0 00.424.308l4.849.705a.563.563 0 01.312.961l-3.508 3.42a.563.563 0 00-.162.498l.828 4.83a.563.563 0 01-.817.593l-4.339-2.282a.563.563 0 00-.524 0l-4.339 2.282a.563.563 0 01-.817-.593l.828-4.83a.563.563 0 00-.162-.498L2.707 9.864a.563.563 0 01.312-.961l4.849-.705a.563.563 0 00.424-.308l2.168-4.391z"
      />
    </svg>
  </button>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  ranking: Object,
})

const isFavorited = ref(false)

onMounted(async () => {
  try {
    const response = await axios.get(`/rankings/${props.ranking.id}/favorite`)
    isFavorited.value = response.data.favorited
  } catch (error) {
    console.error('Error carregant estat del favorit:', error)
  }
})

const toggleFavorite = async () => {
  try {
    const response = await axios.post(`/rankings/${props.ranking.id}/favorite`)
    isFavorited.value = response.data.favorited
  } catch (error) {
    console.error('Error canviant favorit:', error)
  }
}
</script>
