<script setup>
import { onMounted, computed, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  format: { type: String, default: 'auto' },
  slotId: { type: String, default: '' } 
})

const page = usePage()
const isPremium = computed(() => page.props.auth.user?.is_premium)

// Detectar localhost
const isDev = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1'

onMounted(() => {
  if (!isPremium.value && !isDev) {
    try {
      // Afegim l'anunci només si existeix la variable
      if (typeof window.adsbygoogle !== 'undefined') {
          (window.adsbygoogle = window.adsbygoogle || []).push({});
      }
    } catch (e) {
      console.error('AdSense error:', e);
    }
  }
})
</script>

<template>
  <div v-if="!isPremium" class="w-full flex justify-center my-6">
    
    <div 
        v-if="isDev"
        class="w-full bg-gray-100 dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg flex flex-col items-center justify-center text-gray-400 text-sm font-mono select-none"
        :class="format === 'square' ? 'max-w-[300px] h-[250px]' : 'h-[100px]'"
    >
        <span class="font-bold text-lg">ANUNCI ADSENSE</span>
        <span class="text-xs">(Només visible si no ets premium)</span>
    </div>

    <div v-else class="w-full text-center">
        <ins class="adsbygoogle"
             style="display:block"
             :data-ad-client="'ca-pub-TU_ID_DE_CLIENTE_AQUI'" 
             :data-ad-slot="slotId || '1234567890'"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
    </div>

  </div>
</template>