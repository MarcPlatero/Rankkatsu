<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({ rankings: Array })
</script>

<template>
  <AppLayout>
    <Head title="RankDuel - Home" />

    <!-- Hero Section -->
    <section
      class="relative py-20 text-center transition-colors duration-500 overflow-hidden"
    >
      <!-- Fons degradat -->
      <div
        class="absolute inset-0 bg-gradient-to-b from-blue-100 via-white to-gray-50 dark:from-gray-800 dark:via-gray-900 dark:to-black"
      ></div>

      <!-- Contingut -->
      <div class="relative z-10">
        <h1 class="text-5xl font-extrabold text-gray-900 dark:text-gray-100 mb-6">
          Competeix, Vota i Decideix qui és el millor!
        </h1>
        <p class="text-lg text-gray-700 dark:text-gray-300 mb-4">
          Crea els teus propis rànquings i descobreix què pensa la comunitat.
        </p>
        <p class="text-lg text-gray-700 dark:text-gray-300 mb-10">
          Vota als teus favorits i ajuda a coronar el millor en cada duel.
        </p>

        <div class="flex justify-center space-x-4">
          <Link
            href="/rankings/create"
            class="px-6 py-3 bg-blue-600 text-white text-lg rounded-lg shadow hover:bg-blue-700 transition"
          >
            Crear Ranking
          </Link>

          <Link
            href="/rankings"
            class="px-6 py-3 bg-red-600 text-white text-lg rounded-lg shadow hover:bg-red-700 transition"
          >
            Veure Rankings
          </Link>
        </div>
      </div>
    </section>

    <!-- Llistat de rànquings -->
    <section class="max-w-6xl mx-auto px-6 py-12">
      <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">
        Últims rànquings
      </h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="ranking in rankings"
          :key="ranking.id"
          class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-xl hover:-translate-y-1 transition transform"
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
              class="w-full h-48 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-t-lg"
            >
              <span class="text-gray-400 text-sm">Sense imatge</span>
            </div>

            <div class="p-4">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                {{ ranking.title }}
              </h3>
              <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                {{ ranking.description }}
              </p>
              <span class="mt-3 inline-block text-blue-600 dark:text-blue-400 hover:underline">
                Veure més →
              </span>
            </div>
          </Link>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
