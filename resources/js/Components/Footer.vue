<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <footer
    class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 mt-auto transition-colors duration-300"
  >
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        
        <div class="text-sm text-gray-500 dark:text-gray-400 text-center md:text-left">
          © {{ new Date().getFullYear() }} Rankkatsu. {{ $t('Tots els drets reservats.') }}
        </div>

        <div class="flex flex-wrap justify-center gap-6">
          <Link 
            :href="route('legal.terms')" 
            class="text-sm text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition"
          >
            {{ $t('Termes i Condicions') }}
          </Link>
          
          <Link 
            :href="route('legal.privacy')" 
            class="text-sm text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition"
          >
            {{ $t('Política de Privacitat') }}
          </Link>
          
          <Link 
            :href="route('legal.cookies')" 
            class="text-sm text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition"
          >
            {{ $t('Política de Cookies') }}
          </Link>
        </div>

      </div>
    </div>
  </footer>
</template>