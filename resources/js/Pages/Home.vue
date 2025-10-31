<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({ rankings: Array })
</script>

<style scoped>
/* Permet fins a 3 línies de títol abans de truncar */
.title-clamp {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.4em;
  height: 4.2em;
}

/* Animació de gradient lent */
@keyframes gradient-slow {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}
.animate-gradient-slow {
  animation: gradient-slow 8s ease infinite;
}
</style>

<template>
  <AppLayout>
    <Head title="RankDuel - Home" />

    <!-- Hero Section -->
    <section
      class="relative py-20 text-center transition-colors duration-500 overflow-hidden"
    >
      <!-- Fons degradat -->
      <div
        class="absolute inset-0 bg-gradient-to-b from-blue-100 via-white to-gray-50 
               dark:from-gray-800 dark:via-gray-900 dark:to-black"
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

        <!-- Botons -->
        <div class="flex justify-center space-x-4">
          <!-- Botó Crear -->
          <Link
            href="/rankings/create"
            class="relative inline-block px-6 py-3 rounded-lg text-lg font-semibold text-white shadow-lg overflow-hidden group"
          >
            <span
              class="absolute inset-0 bg-gradient-to-r from-blue-600 via-red-500 to-blue-600 
                     animate-gradient-slow bg-[length:200%_200%] transition-all duration-500"
            ></span>
            <span class="relative z-10">Crear Ranking</span>
          </Link>
          <!-- Botó Veure -->
          <Link
            href="/rankings"
            class="relative inline-block px-6 py-3 rounded-lg text-lg font-semibold text-white shadow-lg overflow-hidden group"
          >
            <span
              class="absolute inset-0 bg-gradient-to-r from-red-600 via-blue-500 to-red-600 
                     animate-gradient-slow bg-[length:200%_200%] transition-all duration-500"
            ></span>
            <span class="relative z-10">Veure Rankings</span>
          </Link>
        </div>
      </div>
    </section>

    <!-- Llistat de rànquings -->
    <section class="max-w-7xl mx-auto px-6 py-12">
      <h2 class="text-3xl font-bold mb-8 text-gray-900 dark:text-gray-100">
        Últims rànquings
      </h2>

      <div
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-8"
      >
        <div
          v-for="ranking in rankings"
          :key="ranking.id"
          class="group relative bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 
                 hover:border-blue-500 dark:hover:border-red-500 
                 hover:shadow-[0_0_20px_rgba(59,130,246,0.4)] dark:hover:shadow-[0_0_20px_rgba(220,38,38,0.5)] 
                 hover:-translate-y-1 transition-all duration-300
                 overflow-hidden"
        >
          <Link
            :href="`/rankings/${ranking.id}`"
            class="block h-full"
          >
            <!-- Imatge -->
            <div class="w-full aspect-video bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
              <img
                v-if="ranking.image"
                :src="`/storage/${ranking.image}`"
                alt="Imatge del rànquing"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              />
              <span v-else class="text-gray-400 text-5xl">🗂️</span>
            </div>

            <!-- Contingut -->
            <div class="p-4">
              <h3
                class="text-base sm:text-lg font-bold title-clamp text-gray-900 dark:text-white 
                       transition-colors duration-300 
                       group-hover:text-blue-600 dark:group-hover:text-red-400"
              >
                {{ ranking.title }}
              </h3>
            </div>
          </Link>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
