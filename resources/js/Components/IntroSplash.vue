<script setup>
import { onMounted, ref } from 'vue'

const emit = defineEmits(['finished'])

const isVisible = ref(false)
const isExiting = ref(false)

if (typeof window !== 'undefined') {
  const hasSeenIntro = sessionStorage.getItem('rankduel_intro_seen')
  if (!hasSeenIntro) {
    isVisible.value = true
  }
}

onMounted(() => {
  if (!isVisible.value) return

  document.body.style.overflow = 'hidden'

  // Esperem que passi l'animació d'entrada (2.5s)
  setTimeout(() => {
    // Activem l'esvaïment (Fade Out)
    isExiting.value = true
    
    // Esperem que acabi la transició d'opacitat (1s)
    setTimeout(() => {
      isVisible.value = false
      document.body.style.overflow = ''
      sessionStorage.setItem('rankduel_intro_seen', 'true')
      emit('finished')
    }, 1000) 
  }, 2800) 
})
</script>

<template>
  <div 
    v-if="isVisible" 
    class="fixed inset-0 z-[9999] flex items-center justify-center overflow-hidden transition-opacity duration-1000 ease-out"
    :class="{ 'opacity-0 pointer-events-none': isExiting }"
  >
    
    <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-slate-900 to-red-900 animate-gradient-bg"></div>
    
    <div class="absolute inset-0 opacity-20 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPgo8cmVjdCB3aWR0aD0iNCIgaGVpZ2h0PSI0IiBmaWxsPSIjZmZmIiBmaWxsLW9wYWNpdHk9IjAuMDUiLz4KPC9zdmc+')]"></div>

    <div class="relative z-10 flex flex-col items-center">
      
      <div class="relative w-32 h-32 mb-6 flex items-center justify-center">
        <div class="absolute text-6xl animate-clash filter drop-shadow-[0_0_20px_rgba(255,255,255,0.4)]">
          ⚔️
        </div>
      </div>

      <h1 class="text-6xl md:text-8xl font-black tracking-tighter text-white uppercase italic transform -skew-x-6 animate-title-zoom">
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-white to-red-400 animate-shine">
          RankDuel
        </span>
      </h1>

      <div class="w-0 h-1 bg-white mt-4 rounded-full animate-line-expand shadow-[0_0_15px_rgba(255,255,255,0.6)]"></div>

      <p class="mt-6 text-lg md:text-xl font-medium text-gray-300 tracking-[0.5em] uppercase animate-fade-up opacity-0">
        VOTA I CREA
      </p>

    </div>
  </div>
</template>

<style scoped>
/* 1. Animació de fons (Mou el gradient) */
@keyframes gradient-bg {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
.animate-gradient-bg {
  background-size: 200% 200%;
  animation: gradient-bg 6s ease infinite;
}

/* 2. Animació de xoc (Més impactant) */
@keyframes clash {
  0% {
    opacity: 0;
    transform: scale(3);
    filter: blur(10px);
  }
  40% {
    opacity: 1;
    transform: scale(0.8);
    filter: blur(0);
  }
  60% {
    transform: scale(1.1);
  }
  100% {
    transform: scale(1);
  }
}
.animate-clash {
  animation: clash 1s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}

/* 3. Animació del títol */
@keyframes title-enter {
  0% {
    opacity: 0;
    transform: translateY(30px) scale(0.9);
    filter: blur(10px);
  }
  100% {
    opacity: 1;
    transform: translateY(0) scale(1);
    filter: blur(0);
  }
}
.animate-title-zoom {
  animation: title-enter 1s cubic-bezier(0.34, 1.56, 0.64, 1) forwards 0.4s;
  opacity: 0;
}

/* 4. Brillantor del text */
@keyframes shine {
  0% { background-position: 200% center; }
  100% { background-position: -200% center; }
}
.animate-shine {
  background-size: 200% auto;
  animation: shine 3s linear infinite;
}

/* 5. Línia */
@keyframes expand-line {
  0% { width: 0; opacity: 0; }
  100% { width: 120px; opacity: 1; }
}
.animate-line-expand {
  animation: expand-line 0.8s ease-out forwards 0.8s;
}

/* 6. Subtítol */
@keyframes fade-up {
  0% {
    opacity: 0;
    transform: translateY(15px);
    letter-spacing: 0.2em;
  }
  100% {
    opacity: 1;
    transform: translateY(0);
    letter-spacing: 0.5em;
  }
}
.animate-fade-up {
  animation: fade-up 1s ease-out forwards 1.2s;
}
</style>