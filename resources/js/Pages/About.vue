<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
  <AppLayout>
    <Head title="Sobre RankDuel" />

    <div class="min-h-screen py-16 bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
      <div class="mx-auto max-w-5xl px-6 lg:px-8">
        
        <!-- Capçalera -->
        <div class="text-center mb-12">
          <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">
            Sobre <span class="text-blue-600">Rank</span><span class="text-red-600">Duel</span>
          </h1>
          <p class="text-lg text-gray-600 dark:text-gray-300">
            Descobreix com funciona la plataforma i què hi pots fer com a usuari.
          </p>
        </div>

        <!-- Targetes amb funcionalitats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md hover:shadow-lg transition">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3">🔥 Crear rànquings</h3>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
              Genera els teus propis rànquings sobre qualsevol tema: esports, música, videojocs,
              pel·lícules... el límit és la teva imaginació!
            </p>
          </div>

          <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md hover:shadow-lg transition">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3">⚔️ Fer duels</h3>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
              Enfronta diverses opcions i deixa que la comunitat decideixi quina és la millor.
              Els duels són ràpids, divertits i addictius.
            </p>
          </div>

          <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md hover:shadow-lg transition">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3">🗳️ Votar i participar</h3>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
              Participa votant en rànquings ja existents. Les teves decisions
              ajudaran a establir quin és el favorit de la comunitat.
            </p>
          </div>

          <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md hover:shadow-lg transition">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3">🌍 Connectar amb la comunitat</h3>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
              Comparteix els teus rànquings amb amics i descobreix els gustos d’altres
              usuaris de tot arreu.
            </p>
          </div>
        </div>

        <!-- Botó -->
        <div class="text-center mt-16">
          <p class="text-lg text-gray-800 dark:text-gray-200 mb-4">
            Vols començar a votar i crear els teus rànquings?
          </p>
          <Link
            :href="route('register')"
            class="px-6 py-3 bg-blue-600 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-blue-700 transition"
          >
            Uneix-te ara 🚀
          </Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
