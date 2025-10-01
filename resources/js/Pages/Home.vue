<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3'
defineProps({ rankings: Array })

const user = usePage().props.auth.user

const logout = () => {
  router.post('/logout', {}, {
    onFinish: () => window.location.reload()
  })
}
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

      <!-- Si no està autenticat -->
      <template v-if="!user">
        <Link href="/login" class="text-gray-700 hover:text-blue-600">Login</Link>
        <Link href="/register" class="text-gray-700 hover:text-red-600">Register</Link>
      </template>

      <!-- Si està autenticat -->
      <template v-else>
        <span class="text-gray-600">Hola, {{ user.name }}</span>
        <button
          @click="logout"
          class="text-red-600 hover:text-red-800 font-semibold"
        >
          Logout
        </button>
      </template>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="bg-gray-50 py-20 text-center">
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
      Crear Ranking
    </Link>
  </section>

  <!-- Llistat de rànquings -->
  <section class="max-w-6xl mx-auto px-6 py-12">
    <h2 class="text-2xl font-bold mb-6">Últims rànquings</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="ranking in rankings"
        :key="ranking.id"
        class="bg-white rounded-lg shadow hover:shadow-xl hover:-translate-y-1 transition transform"
      >
        <Link :href="`/rankings/${ranking.id}`">
          <!-- Imatge o fallback -->
          <img
            v-if="ranking.image"
            :src="`/storage/${ranking.image}`"
            alt="Ranking image"
            class="w-full h-48 object-cover rounded-t-lg"
          />
          <div
            v-else
            class="w-full h-48 flex items-center justify-center bg-gray-100 rounded-t-lg"
          >
            <span class="text-gray-400 text-sm">Sense imatge</span>
          </div>

          <!-- Info -->
          <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-900">{{ ranking.title }}</h3>
            <p class="text-sm text-gray-600 line-clamp-2">
              {{ ranking.description }}
            </p>
            <span class="mt-3 inline-block text-blue-600 hover:underline">
              Veure més →
            </span>
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
