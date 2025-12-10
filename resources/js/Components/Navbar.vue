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
      <Link href="/" class="text-3xl font-black tracking-tighter flex items-center gap-1">
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">
          Rank
        </span>
        
        <span class="text-gray-300 dark:text-gray-600 text-2xl">|</span>

        <span class="text-transparent bg-clip-text bg-gradient-to-l from-red-600 to-pink-500">
          katsu
        </span>
      </Link>

      <div class="flex items-center space-x-8">
        <Link href="/" class="text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 font-medium">Home</Link>

        <template v-if="$page.props.auth?.user">
          <Link href="/profile" class="text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400 font-medium">Perfil</Link>
        </template>
        
        <Link href="/rankings" class="text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 font-medium">Rankings</Link>

        <Link href="/rankings/create" class="text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400 font-medium">Create</Link>
        
        <Link href="/premium" class="text-gray-700 dark:text-gray-200 hover:text-yellow-500 dark:hover:text-yellow-400 font-bold flex items-center gap-1">
          Premium 👑
        </Link>

        <Link href="/about" class="text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 font-medium">About</Link>
        <Link href="/settings" class="text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400 font-medium">Configuració</Link>

        <template v-if="!user">
          <Link href="/login" class="text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400 font-medium">Login</Link>
          <Link href="/register" class="text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 font-medium">Register</Link>
        </template>

        <template v-else>
          <div class="flex items-center gap-2">
            <span class="text-gray-700 dark:text-gray-200">
              Hola, {{ user.name }}
            </span>
            <span v-if="user.is_premium" class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-gradient-to-r from-yellow-400 to-orange-500 text-white shadow-sm uppercase tracking-wider">Premium</span>
          </div>
          
          <button
            @click="logout"
            class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-semibold ml-2"
          >
            Logout
          </button>
        </template>

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