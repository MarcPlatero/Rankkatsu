<script setup>
import { ref, onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const isDarkMode = ref(false)

onMounted(() => {
  const storedTheme = localStorage.getItem('theme')
  if (storedTheme) isDarkMode.value = storedTheme === 'dark'
  else isDarkMode.value = window.matchMedia('(prefers-color-scheme: dark)').matches
})

function toggleDarkMode() {
  isDarkMode.value = !isDarkMode.value
  localStorage.setItem('theme', isDarkMode.value ? 'dark' : 'light')
  if (isDarkMode.value) document.documentElement.classList.add('dark')
  else document.documentElement.classList.remove('dark')
}
</script>

<template>
  <AppLayout>
    <div class="max-w-3xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-6">⚙️ Configuració</h1>

      <!-- Preferències d’aparença -->
      <section class="mb-8 p-6 border rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700 shadow">
        <h2 class="text-lg font-semibold mb-3 text-gray-900 dark:text-gray-100">
          Aparença
        </h2>
        <div class="flex items-center justify-between">
          <span class="text-gray-700 dark:text-gray-300">
            Mode {{ isDarkMode ? 'fosc 🌙' : 'clar ☀️' }}
          </span>
          <button
            @click="toggleDarkMode"
            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
          >
            Canviar mode
          </button>
        </div>
      </section>

      <!-- Idioma (placeholder futur) -->
      <section class="mt-8 p-6 border rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700 shadow">
        <h2 class="text-lg font-semibold mb-3 text-gray-900 dark:text-gray-100">
          Idioma
        </h2>
        <p class="text-gray-600 dark:text-gray-400">
          Properament podràs triar l’idioma de la plataforma 🌍.
        </p>
      </section>
    </div>
  </AppLayout>
</template>

<style>
/* Opcional: transició suau entre modes */
html {
  transition: background-color 0.3s, color 0.3s;
}
</style>
