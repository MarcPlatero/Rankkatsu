<script setup>
import { Link } from '@inertiajs/vue3';
defineProps({ rankings: Array });
import AppLayout from '@/Layouts/AppLayout.vue'
</script>

<template>
  <AppLayout>
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
          <p class="text-gray-700">{{ ranking.description }}</p>

          <ul class="mt-2">
            <li v-for="opt in ranking.options" :key="opt.id" class="text-sm">
              - {{ opt.name }}
            </li>
          </ul>

          <Link
            :href="`/rankings/${ranking.id}`"
            class="mt-3 inline-block text-indigo-600 hover:underline"
          >
            Veure detalls →
          </Link>
        </li>
      </ul>
    </div>
  </AppLayout>
</template>
