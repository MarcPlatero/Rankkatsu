<script setup>
import ApplicationLogo from '@/Components/LogoApplication.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <!-- Layout senzill i net, sense el logo de Laravel -->
  <div
    class="min-h-screen bg-gradient-to-b 
           from-gray-50 via-white to-gray-100 
           dark:from-gray-900 dark:via-gray-950 dark:to-black
           transition-colors duration-500"
  >
    <slot />
  </div>
</template>

