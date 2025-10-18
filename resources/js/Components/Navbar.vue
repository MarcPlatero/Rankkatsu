<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'

const user = usePage().props.auth.user

const logout = () => {
  router.post('/logout', {}, {
    onSuccess: () => router.visit('/'),
  })
}
</script>

<template>
  <nav
    class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 transition-colors duration-300"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
      <div class="flex items-center">
        <Link href="/" class="text-2xl font-extrabold">
          <span class="text-blue-600">Rank</span>
          <span class="text-red-600">Duel</span>
        </Link>
      </div>

      <!-- Links -->
      <div class="flex space-x-8">
        <Link href="/" class="text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400">Home</Link>

        <template v-if="$page.props.auth?.user">
          <Link href="/profile" class="text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400">Perfil</Link>
        </template>
        
        <Link href="/rankings" class="text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400">Rankings</Link>

        <Link href="/rankings/create" class="text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400">Create</Link>
        <Link href="/about" class="text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400">About</Link>
        <Link href="/settings" class="text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400">Configuració</Link>

        <!-- Auth -->
        <template v-if="!user">
          <Link href="/login" class="text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400">Login</Link>
          <Link href="/register" class="text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400">Register</Link>
        </template>

        <template v-else>
          <span class="text-gray-700 dark:text-gray-200 hover:text-green-600 dark:hover:text-green-400">Hola, {{ user.name }}</span>
          <button
            @click="logout"
            class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-semibold"
          >
            Logout
          </button>
        </template>

        <!-- Només per a admin -->
        <Link
          v-if="user?.is_admin"
          href="/admin/moderation"
          class="text-red-600 dark:text-red-400 font-semibold hover:text-red-800 dark:hover:text-red-300 flex items-center"
        >
          🛡️ Moderació
        </Link>
      </div>
    </div>
  </nav>
</template>
