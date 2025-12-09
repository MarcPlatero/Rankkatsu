<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AdBanner from '@/Components/AdBanner.vue'
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
</style>

<template>
  <AppLayout>
    <Head title="Sobre RankDuel" />

    <div
      class="min-h-screen py-16 
             bg-gradient-to-b from-gray-50 via-gray-100 to-gray-200
             dark:from-gray-900 dark:via-gray-950 dark:to-gray-900
             transition-colors duration-500"
    >
      <div class="mx-auto max-w-5xl px-6 lg:px-8">
        <div class="text-center mb-12">
          <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">
            Sobre <span class="text-blue-600">Rank</span><span class="text-red-600">Duel</span>
          </h1>
          <p class="text-lg text-gray-600 dark:text-gray-300">
            Descobreix com funciona la plataforma i què hi pots fer com a usuari.
          </p>
        </div>

        <!-- Targetes -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div
            class="group bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md 
                   hover:shadow-[0_0_25px_rgba(37,99,235,0.5)] 
                   transition-all duration-300 transform hover:-translate-y-1"
          >
            <h3
              class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3 
                     transition-colors duration-300 group-hover:text-blue-600"
            >
              🛠️ Crear rànquings
            </h3>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
              Genera els teus propis rànquings sobre qualsevol tema: esports, música, videojocs,
              pel·lícules... el límit és la teva imaginació!
            </p>
          </div>

          <div
            class="group bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md 
                   hover:shadow-[0_0_25px_rgba(220,38,38,0.5)] 
                   transition-all duration-300 transform hover:-translate-y-1"
          >
            <h3
              class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3 
                     transition-colors duration-300 group-hover:text-red-600"
            >
              ⚔️ Fer duels
            </h3>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
              Enfronta diverses opcions i deixa que la comunitat decideixi quina és la millor.
              Els duels són ràpids, divertits i addictius.
            </p>
          </div>

          <div
            class="group bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md 
                   hover:shadow-[0_0_25px_rgba(220,38,38,0.5)] 
                   transition-all duration-300 transform hover:-translate-y-1"
          >
            <h3
              class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3 
                     transition-colors duration-300 group-hover:text-red-600"
            >
              🗳️ Votar i participar
            </h3>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
              Participa votant en rànquings ja existents. Les teves decisions ajudaran a establir
              quin és el favorit de la comunitat.
            </p>
          </div>

          <div
            class="group bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md 
                   hover:shadow-[0_0_25px_rgba(37,99,235,0.5)] 
                   transition-all duration-300 transform hover:-translate-y-1"
          >
            <h3
              class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3 
                     transition-colors duration-300 group-hover:text-blue-600"
            >
              🌍 Connectar amb la comunitat
            </h3>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
              Comparteix els teus rànquings amb amics i descobreix els gustos d’altres usuaris de
              tot arreu.
            </p>
          </div>
        </div>

        <div class="mt-16">
          <AdBanner format="horizontal" />
        </div>

        <!-- Botó -->
        <div class="text-center mt-16">
          <p class="text-lg text-gray-800 dark:text-gray-200 mb-4">
            Vols començar a votar i crear els teus rànquings?
          </p>
          <Link
            :href="route('register')"
            class="relative inline-block px-8 py-3 rounded-xl font-semibold text-white text-lg shadow-lg overflow-hidden group"
          >
            <span
              class="absolute inset-0 bg-gradient-to-r from-blue-600 via-red-500 to-blue-600
                     animate-gradient-slow bg-[length:200%_200%] transition-all duration-500"
            ></span>
            <span class="relative z-10">Uneix-te ara 🚀</span>
          </Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
