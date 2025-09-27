<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeftIcon } from '@heroicons/vue/24/outline';
</script>

<template>
  <Head title="About" />

  <div class="min-h-screen py-16 bg-gray-50">
    <div class="mx-auto max-w-5xl px-6 lg:px-8">

      <!-- Fletxa -->
      <div class="absolute top-6 left-6 z-10">
        <Link
        href="/"
        class="inline-flex items-center rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        aria-label="Back to home"
        >
        <ArrowLeftIcon class="h-5 w-5" />
        </Link>
      </div>

      <!-- Capçalera -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4">
          Sobre <span class="text-blue-600">Rank</span><span class="text-red-600">Duel</span>
        </h1>
        <p class="text-lg text-gray-600">
          Descobreix com funciona la plataforma i què hi pots fer com a usuari.
        </p>
      </div>

      <!-- Targetes amb funcionalitats -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition">
          <h3 class="text-xl font-semibold text-gray-900 mb-3">🔥 Crear rànquings</h3>
          <p class="text-gray-700 leading-relaxed">
            Genera els teus propis rànquings sobre qualsevol tema: esports, música, videojocs,
            pel·lícules... el límit és la teva imaginació!
          </p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition">
          <h3 class="text-xl font-semibold text-gray-900 mb-3">⚔️ Fer duels</h3>
          <p class="text-gray-700 leading-relaxed">
            Enfronta dues opcions i deixa que la comunitat decideixi quina és la millor.
            Els duels són ràpids, divertits i addictius.
          </p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition">
          <h3 class="text-xl font-semibold text-gray-900 mb-3">🗳️ Votar i participar</h3>
          <p class="text-gray-700 leading-relaxed">
            Participa votant en rànquings i duels ja existents. Les teves decisions
            ajudaran a establir quin és el favorit de la comunitat.
          </p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition">
          <h3 class="text-xl font-semibold text-gray-900 mb-3">🌍 Connectar amb la comunitat</h3>
          <p class="text-gray-700 leading-relaxed">
            Comparteix els teus rànquings amb amics i descobreix els gustos d’altres
            usuaris de tot arreu.
          </p>
        </div>
      </div>

      <!-- CTA -->
      <div class="text-center mt-16">
        <p class="text-lg text-gray-800 mb-4">
          Vols començar a jugar i crear els teus rànquings?
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
</template>
