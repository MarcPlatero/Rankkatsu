<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({ rankings: Array })
</script>

<template>
  <AppLayout>
    <Head title="RankDuel - Home" />

    <!-- Hero Section -->
    <section class="bg-gray-50 py-20 text-center">
      <h1 class="text-5xl font-extrabold text-gray-900 mb-6">
        Competeix, Vota i Decideix qui és el millor!
      </h1>
      <p class="text-lg text-gray-600 mb-8">
        Crea els teus propis rànquings i descobreix què pensa la comunitat.
      </p>
      <Link
        href="/rankings/create"
        class="px-6 py-3 bg-blue-600 text-white text-lg rounded-lg shadow hover:bg-blue-700 transition"
      >
        Crear Ranking
      </Link>
    </section>

    <!-- Llistat de rànquings -->
    <section class="max-w-6xl mx-auto px-6 py-12">
      <h2 class="text-2xl font-bold mb-6">Últims rànquings</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="ranking in rankings"
          :key="ranking.id"
          class="bg-white rounded-lg shadow hover:shadow-xl hover:-translate-y-1 transition transform"
        >
          <Link :href="`/rankings/${ranking.id}`">
            <img
              v-if="ranking.image"
              :src="`/storage/${ranking.image}`"
              alt="Imatge del rànquing"
              class="w-full h-48 object-cover rounded-t-lg"
            />
            <div
              v-else
              class="w-full h-48 flex items-center justify-center bg-gray-100 rounded-t-lg"
            >
              <span class="text-gray-400 text-sm">Sense imatge</span>
            </div>

            <div class="p-4">
              <h3 class="text-lg font-semibold text-gray-900">{{ ranking.title }}</h3>
              <p class="text-sm text-gray-600 line-clamp-2">
                {{ ranking.description }}
              </p>
              <span class="mt-3 inline-block text-blue-600 hover:underline">
                Veure més →
              </span>
            </div>
          </Link>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
