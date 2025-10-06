<script setup>
defineProps({
  favorites: Array,
})
</script>

<template>
  <div>
    <h2 class="text-xl font-semibold mb-4">⭐ Els teus rankings favorits</h2>

    <div v-if="favorites.length === 0" class="text-gray-600">
      Encara no tens cap rànquing marcat com a favorit.
    </div>

    <ul>
      <li
        v-for="ranking in favorites"
        :key="ranking.id"
        class="mb-3 rounded border p-4 shadow hover:bg-gray-50 transition"
      >
        <h3 class="text-lg font-semibold">{{ ranking.title }}</h3>
        <p class="text-gray-700">{{ ranking.description }}</p>
        <p class="text-sm text-gray-500 mt-1">Creat per: {{ ranking.user.name }}</p>

        <Link
          :href="`/rankings/${ranking.id}`"
          class="text-indigo-600 hover:underline mt-2 inline-block"
        >
          Veure detalls →
        </Link>
      </li>
    </ul>
  </div>
</template>
