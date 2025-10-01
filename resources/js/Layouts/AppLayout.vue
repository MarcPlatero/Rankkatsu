<!-- resources/js/Layouts/AppLayout.vue -->
<script setup>
import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <Navbar />

    <!-- Contingut de la pàgina -->
    <main class="flex-1">
      <slot />
    </main>

    <Footer />
  </div>
</template>
