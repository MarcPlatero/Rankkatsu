<script setup>
import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'
import IntroSplash from '@/Components/IntroSplash.vue'
import CookieConsent from '@/Components/CookieConsent.vue'
import { onMounted, onBeforeUnmount } from 'vue'

function applyFromLocalStorage() {
  try {
    const storedTheme = localStorage.getItem('theme')
    if (storedTheme === 'dark') {
      document.documentElement.classList.add('dark')
    } else if (storedTheme === 'light') {
      document.documentElement.classList.remove('dark')
    } else {
      const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
      if (prefersDark) document.documentElement.classList.add('dark')
      else document.documentElement.classList.remove('dark')
    }
  } catch (e) {
    // ignore localStorage errors
  }
}

function storageListener(e) {
  if (e.key === 'theme') {
    applyFromLocalStorage()
  }
}

onMounted(() => {
  // Aplica el tema a la càrrega
  applyFromLocalStorage()
  // Escolta canvis fets des d'altres pestanyes
  window.addEventListener('storage', storageListener)
})

onBeforeUnmount(() => {
  window.removeEventListener('storage', storageListener)
})
</script>

<template>
  <div class="min-h-screen flex flex-col bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-200">
    
    <IntroSplash />

    <Navbar />

    <main class="flex-1">
      <slot />
    </main>

    <Footer />
  </div>

  <CookieConsent />
</template>