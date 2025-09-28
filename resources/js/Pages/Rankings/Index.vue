<script setup>
import { Link } from '@inertiajs/vue3';
defineProps({ rankings: Array });
</script>

<template>
  <div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Rankings</h1>

    <Link
      href="/rankings/create"
      class="mb-4 inline-block rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700"
    >
      + Crear nou rànquing
    </Link>

    <div v-if="rankings.length === 0" class="text-gray-600">
      Encara no hi ha rànquings.
    </div>

    <ul>
      <li
        v-for="ranking in rankings"
        :key="ranking.id"
        class="mb-3 rounded border p-4 shadow"
      >
        <h2 class="text-lg font-semibold">{{ ranking.title }}</h2>
        <p class="text-gray-700">
          {{ ranking.option_one }} vs {{ ranking.option_two }}
        </p>
        <p class="mt-2 text-sm text-gray-500">
          Vots: {{ ranking.votes_one }} - {{ ranking.votes_two }}
        </p>
      </li>
    </ul>
  </div>
</template>
