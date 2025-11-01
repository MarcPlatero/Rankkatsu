<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import FavoriteStar from '@/Components/FavoriteStar.vue'

defineProps({
  rankings: Array,
  filters: Object,
})

const page = usePage()
const flash = page.props.flash || {}

const search = ref(page.props.filters?.search || '')

function applySearch() {
  router.get(
    '/rankings',
    { search: search.value },
    { preserveState: true, replace: true }
  )
}

function goToRanking(id) {
  router.get(`/rankings/${id}`)
}
</script>

<style scoped>
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

.line-clamp-2 {
  overflow: hidden;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  word-break: break-word; 
 
  line-height: 1.4em;
  height: 2.8em;
}

.line-clamp-3 {
  overflow: hidden;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  word-break: break-word; 

  line-height: 1.4em;
  height: 4.2em;
}
</style>

<template>
  <AppLayout>
    <div
      class="w-full mx-auto p-6 min-h-screen transition-colors duration-300 
             bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100"
    >
      <!-- Títol -->
      <h1 class="text-3xl font-extrabold mb-10 text-center">
        📊 Rànquings
      </h1>

      <!-- Flash messages -->
      <div
        v-if="flash.success"
        class="mb-4 p-4 rounded-lg bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100 shadow"
      >
        {{ flash.success }}
      </div>
      <div
        v-if="flash.error"
        class="mb-4 p-4 rounded-lg bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100 shadow"
      >
        {{ flash.error }}
      </div>

      <!-- Buscador -->
      <div class="flex mb-10 max-w-3xl mx-auto">
        <input
          v-model="search"
          @keyup.enter="applySearch"
          type="text"
          placeholder="Cerca rànquings o opcions..."
          class="w-full px-4 py-2 rounded-l-lg border border-gray-300 dark:border-gray-700
                 bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <button
          @click="applySearch"
          class="px-5 py-2 bg-gradient-to-r from-blue-600 to-red-600 text-white font-semibold
                 rounded-r-lg hover:opacity-90 transition duration-200"
        >
          🔍
        </button>
      </div>

      <!-- Botó crear -->
      <div class="text-center mb-12">
        <Link
          href="/rankings/create"
          class="relative inline-block px-7 py-3 rounded-xl font-semibold text-white text-lg shadow-lg overflow-hidden group"
        >
          <span
            class="absolute inset-0 bg-gradient-to-r from-blue-600 via-red-500 to-blue-600
                   animate-gradient-slow bg-[length:200%_200%] transition-all duration-500"
          ></span>
          <span class="relative z-10">Crear nou rànquing</span>
        </Link>
      </div>

      <div
        v-if="!rankings || rankings.length === 0"
        class="text-gray-600 dark:text-gray-400 mt-8 text-center text-lg"
      >
        No s’han trobat rànquings.
      </div>

      <!-- Llista -->
      <div
        v-else
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 
               gap-8 w-full px-4"
      >
        <div
          v-for="ranking in rankings"
          :key="ranking.id"
          class="relative flex flex-col rounded-2xl border border-gray-200 dark:border-gray-700 
                 bg-white dark:bg-neutral-900 shadow-sm hover:shadow-xl hover:-translate-y-1
                 transition-all duration-300 overflow-hidden group cursor-pointer"
          @click="goToRanking(ranking.id)"
        >
          <!-- Imatge -->
          <div
            class="w-full aspect-video overflow-hidden flex items-center justify-center 
                   bg-gray-100 dark:bg-gray-800"
          >
            <img
              v-if="ranking.image"
              :src="`/storage/${ranking.image}`"
              alt="Imatge del rànquing"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            />
            <div v-else class="text-5xl text-gray-400 dark:text-gray-500">
              🗂️
            </div>
          </div>

          <!-- Contingut -->
          <div class="flex-1 p-4 relative flex flex-col justify-between">
            <!-- Estrella -->
            <div @click.stop>
              <FavoriteStar :ranking="ranking" class="absolute top-3 right-3 z-10" />
            </div>

            <!-- Text -->
            <div class="pr-12">
              <h2
                class="text-lg font-bold mb-2 text-gray-900 dark:text-white 
                       break-words group-hover:text-blue-600 dark:group-hover:text-red-400 
                       transition-colors duration-200 line-clamp-2"
              >
                {{ ranking.title }}
              </h2>

              <p
                class="text-sm text-gray-700 dark:text-gray-300 whitespace-normal break-words line-clamp-3"
              >
                {{ ranking.description }}
              </p>

              <ul
                class="mt-3 text-xs text-gray-600 dark:text-gray-400 whitespace-normal break-words"
              >
                <li
                  v-for="opt in ranking.options.slice(0, 2)"
                  :key="opt.id"
                  class="leading-snug"
                >
                  • {{ opt.name }}
                </li>
                <li
                  v-if="ranking.options.length > 2"
                  class="text-gray-400 italic"
                >
                  ...
                </li>
              </ul>
            </div>

            <!-- Veure detalls -->
            <div class="mt-6">
              <span
                class="text-blue-600 dark:text-red-400 font-semibold hover:underline"
              >
                Veure detalls →
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>