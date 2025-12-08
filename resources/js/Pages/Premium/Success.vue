<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <AppLayout>
    <div class="min-h-[70vh] flex flex-col items-center justify-center text-center px-6">
      
      <div class="text-7xl mb-6 animate-bounce">🎉</div>
      
      <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">
        Benvingut al club Premium!
      </h1>
      
      <p class="text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-lg">
        La teva subscripció s'ha activat correctament. <br>
        Ara pots gaudir de <b>Rankkatsu</b> sense anuncis i amb la insígnia exclusiva.
      </p>
      
      <div class="flex gap-4">
        <Link href="/" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-full transition shadow-lg hover:-translate-y-1">
          Anar a l'Inici
        </Link>
        <Link href="/profile" class="px-8 py-3 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white font-bold rounded-full transition shadow hover:bg-gray-300 dark:hover:bg-gray-600">
          Veure el meu perfil
        </Link>
      </div>

    </div>
  </AppLayout>
</template>