<script setup>
import { Head, Link } from '@inertiajs/vue3'
</script>

<template>
  <Head title="RankDuel - Home" />

  <!-- Navbar -->
  <nav class="bg-white border-b border-gray-200">
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
        <Link href="/" class="text-gray-700 hover:text-blue-600">Home</Link>
        <Link href="/about" class="text-gray-700 hover:text-blue-600">About</Link>
        <Link href="/login" class="text-gray-700 hover:text-blue-600">Login</Link>
        <Link href="/register" class="text-gray-700 hover:text-blue-600">Register</Link>
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
        href="/register"
        class="px-6 py-3 bg-blue-600 text-white text-lg rounded-lg shadow hover:bg-blue-700 transition"
      >
        Comença ara!
      </Link>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-white border-t border-gray-200 py-6 mt-12">
    <div class="max-w-7xl mx-auto px-4 text-center text-gray-500">
      © 2025 RankDuel. Tots els drets reservats.
    </div>
  </footer>
</template>
