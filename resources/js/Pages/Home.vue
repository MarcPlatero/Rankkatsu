<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AdBanner from '@/Components/AdBanner.vue'

defineProps({ 
  topRankings: Array,
  latestRankings: Array 
})

const page = usePage()
const isPremium = page.props.auth.user?.is_premium
</script>

<style scoped>
.title-clamp {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.4em;
  height: 4.2em;
}

@keyframes gradient-slow {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
.animate-gradient-slow {
  animation: gradient-slow 8s ease infinite;
}
</style>

<template>
  <AppLayout>
    <Head title="RankDuel - Home" />

    <section class="relative py-20 text-center transition-colors duration-500 overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-b from-blue-100 via-white to-gray-50 dark:from-gray-800 dark:via-gray-900 dark:to-black"></div>

      <div class="relative z-10 max-w-4xl mx-auto px-4">
        <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 dark:text-gray-100 mb-6 leading-tight">
          Competeix, Vota i <br> <span class="text-blue-600 dark:text-blue-400">Decideix</span> qui és el millor!
        </h1>
        <p class="text-xl text-gray-700 dark:text-gray-300 mb-10 max-w-2xl mx-auto">
          Crea els teus propis rànquings, descobreix què pensa la comunitat i ajuda a coronar el guanyador en cada duel.
        </p>

        <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
          <Link
            href="/rankings/create"
            class="w-full sm:w-auto relative inline-block px-8 py-4 rounded-xl text-lg font-bold text-white shadow-xl overflow-hidden group hover:scale-105 transition-transform"
          >
            <span class="absolute inset-0 bg-gradient-to-r from-blue-600 via-indigo-500 to-blue-600 animate-gradient-slow bg-[length:200%_200%]"></span>
            <span class="relative z-10 flex items-center justify-center gap-2">
              🚀 Crear Ranking
            </span>
          </Link>

          <Link
            v-if="!isPremium"
            href="/premium"
            class="w-full sm:w-auto relative inline-block px-8 py-4 rounded-xl text-lg font-bold text-white shadow-xl overflow-hidden group hover:scale-105 transition-transform"
          >
            <span class="absolute inset-0 bg-gradient-to-r from-yellow-500 via-amber-500 to-yellow-500 animate-gradient-slow bg-[length:200%_200%]"></span>
            <span class="relative z-10 flex items-center justify-center gap-2">
              👑 Fes-te Premium
            </span>
          </Link>
          
          <Link
            href="/rankings"
            class="w-full sm:w-auto relative inline-block px-8 py-4 rounded-xl text-lg font-bold text-white shadow-xl overflow-hidden group hover:scale-105 transition-transform"
          >
            <span class="absolute inset-0 bg-gradient-to-r from-red-600 via-orange-500 to-red-600 animate-gradient-slow bg-[length:200%_200%]"></span>
            <span class="relative z-10 flex items-center justify-center gap-2">
              Explorar Tot 🔍
            </span>
          </Link>
        </div>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-6">
      <AdBanner format="horizontal" />
    </div>

    <section class="max-w-7xl mx-auto px-6 pt-6 pb-6">
      <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
          🔥 Més populars
        </h2>
        <Link href="/rankings" class="text-blue-600 dark:text-red-400 hover:underline font-semibold transition-colors">
          Veure més &rarr;
        </Link>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-8">
        <div v-for="ranking in topRankings" :key="ranking.id" class="group relative bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-red-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col h-full">
          <Link :href="`/rankings/${ranking.id}`" class="block h-full flex flex-col">
            <div class="w-full aspect-video bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden relative">
              <img v-if="ranking.image" :src="`/storage/${ranking.image}`" alt="Imatge del rànquing" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
              <span v-else class="text-gray-400 text-5xl">🗂️</span>
              <div class="absolute inset-0 from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </div>
            <div class="p-5 flex-1">
              <h3 class="text-lg font-bold title-clamp text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-red-400 transition-colors">
                {{ ranking.title }}
              </h3>
            </div>
          </Link>
        </div>
      </div>
      
      <div v-if="topRankings.length === 0" class="text-center text-gray-500 mt-4 py-10 bg-gray-50 dark:bg-gray-900 rounded-xl">
        Encara no hi ha rànquings populars. Sigues el primer en destacar!
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-6">
      <AdBanner format="horizontal" />
    </div>

    <section class="max-w-7xl mx-auto px-6 py-12 border-t border-gray-200 dark:border-gray-800">
      <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
          🆕 Últims rànquings
        </h2>
        <Link href="/rankings" class="text-blue-600 dark:text-red-400 hover:underline font-semibold transition-colors">
          Veure més &rarr;
        </Link>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-8">
        <div v-for="ranking in latestRankings" :key="ranking.id" class="group relative bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-red-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col h-full">
          <Link :href="`/rankings/${ranking.id}`" class="block h-full flex flex-col">
            <div class="w-full aspect-video bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
              <img v-if="ranking.image" :src="`/storage/${ranking.image}`" alt="Imatge del rànquing" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
              <span v-else class="text-gray-400 text-5xl">🆕</span>
            </div>
            <div class="p-5 flex-1">
              <h3 class="text-lg font-bold title-clamp text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-red-400 transition-colors">
                {{ ranking.title }}
              </h3>
            </div>
          </Link>
        </div>
      </div>
      
      <div v-if="latestRankings.length === 0" class="text-center text-gray-500 mt-4">
        Encara no s'han creat rànquings.
      </div>

      <div v-if="!isPremium" class="mt-20 p-8 rounded-2xl bg-gradient-to-r from-gray-900 via-gray-800 to-black text-white text-center shadow-2xl relative overflow-hidden group">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-yellow-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <h3 class="text-3xl font-extrabold mb-4 relative z-10">Cansat de la publicitat? 🚫</h3>
        <p class="text-gray-300 mb-8 max-w-xl mx-auto relative z-10 text-lg">
          Fes-te <span class="text-yellow-400 font-bold">Premium</span> per només 1,99€ i gaudeix de RankDuel sense interrupcions, amb insígnies exclusives i suport prioritari.
        </p>
        <Link href="/premium" class="relative inline-block px-10 py-4 bg-gradient-to-r from-yellow-500 to-amber-600 hover:from-yellow-400 hover:to-amber-500 text-white font-bold rounded-full shadow-lg transform transition-all hover:scale-105 hover:shadow-yellow-500/30 z-10">
          Passar-me a Premium 👑
        </Link>
      </div>

      <div class="mt-12">
        <AdBanner format="horizontal" />
      </div>

    </section>

  </AppLayout>
</template>