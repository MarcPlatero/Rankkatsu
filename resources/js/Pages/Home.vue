<script setup>
import { Head, Link } from '@inertiajs/vue3'
</script>

<template>
  <Head title="RankDuel - Home" />

  <!-- Navbar -->
  <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
      <!-- Logo / Nom -->
      <div class="flex items-center">
        <span class="text-2xl font-extrabold">
          <span class="text-blue-600">Rank</span>
          <span class="text-red-600">Duel</span>
        </span>
      </div>

      <!-- Links -->
      <div class="flex space-x-8">
        <Link href="/rankings/create" class="text-gray-700 hover:text-blue-600">Create</Link>
        <Link href="/about" class="text-gray-700 hover:text-red-600">About</Link>
        <Link href="/login" class="text-gray-700 hover:text-blue-600">Login</Link>
        <Link href="/register" class="text-gray-700 hover:text-red-600">Register</Link>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="bg-gray-50 py-20">
    <div class="max-w-4xl mx-auto text-center">
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
        Crear un rànquing
      </Link>
    </div>
  </section>

  <!-- Rànquings destacats -->
  <section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-3xl font-bold text-gray-900 mb-10 text-center">Rànquings destacats</h2>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Exemple de targeta dummy -->
        <Link href="/rankings/1" class="bg-gray-50 rounded-lg shadow hover:shadow-md transition overflow-hidden">
          <img src="https://via.placeholder.com/400x200" alt="Ranking 1" class="w-full h-40 object-cover">
          <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-800">Millors jugadors de futbol 2025</h3>
            <p class="text-sm text-gray-600 mt-2">Vota per decidir qui és el número 1 ⚽</p>
          </div>
        </Link>

        <Link href="/rankings/2" class="bg-gray-50 rounded-lg shadow hover:shadow-md transition overflow-hidden">
          <img src="https://via.placeholder.com/400x200" alt="Ranking 2" class="w-full h-40 object-cover">
          <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-800">Millors sèries de Netflix</h3>
            <p class="text-sm text-gray-600 mt-2">Tria la teva favorita 📺</p>
          </div>
        </Link>

        <Link href="/rankings/3" class="bg-gray-50 rounded-lg shadow hover:shadow-md transition overflow-hidden">
          <img src="https://via.placeholder.com/400x200" alt="Ranking 3" class="w-full h-40 object-cover">
          <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-800">Millors videojocs de la dècada</h3>
            <p class="text-sm text-gray-600 mt-2">Comparteix la teva opinió 🎮</p>
          </div>
        </Link>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-white border-t border-gray-200 py-6 mt-12">
    <div class="max-w-7xl mx-auto px-4 text-center text-gray-500">
      © 2025 RankDuel. Tots els drets reservats.
    </div>
  </footer>
</template>
