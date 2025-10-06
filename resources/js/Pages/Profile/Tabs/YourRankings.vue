<script setup>
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()
const rankings = computed(() => page.props.rankings || [])
</script>

<template>
  <div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">🏆 Els teus rankings</h2>

    <div v-if="rankings.length">
      <ul class="space-y-3">
        <li
          v-for="ranking in rankings"
          :key="ranking.id"
          class="p-4 border rounded-lg shadow-sm hover:shadow-md transition"
        >
          <h3 class="font-medium text-lg">{{ ranking.title }}</h3>
          <p class="text-gray-500 text-sm">Categoria: {{ ranking.category || 'Sense categoria' }}</p>
        </li>
      </ul>
    </div>

    <div v-else class="text-gray-500 mt-6 text-center">
      Encara no has creat cap ranking.
    </div>
  </div>
</template>
