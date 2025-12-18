<script setup>
import { onMounted, ref } from 'vue'

const emit = defineEmits(['finished'])

const isVisible = ref(false)
const isExiting = ref(false)

const particles = Array.from({ length: 25 }, (_, i) => ({
  id: i,
  color: i % 2 === 0 ? 'bg-blue-400' : 'bg-red-400',
  left: Math.random() * 100 + '%',
  delay: Math.random() * 2 + 's',
  duration: 4 + Math.random() * 6 + 's',
  size: Math.random() * 3 + 1 + 'px'
}))

if (typeof window !== 'undefined') {
  const hasSeenIntro = sessionStorage.getItem('rankkatsu_fixed_k_final')
  if (!hasSeenIntro) {
    isVisible.value = true
  }
}

onMounted(() => {
  if (!isVisible.value) return

  document.body.style.overflow = 'hidden'

  setTimeout(() => {
    isExiting.value = true
    
    setTimeout(() => {
      isVisible.value = false
      document.body.style.overflow = ''
      sessionStorage.setItem('rankkatsu_fixed_k_final', 'true')
      emit('finished')
    }, 800) 
  }, 3500) 
})
</script>

<style scoped>
@keyframes flash-bang {
  0% { opacity: 1; }
  100% { opacity: 0; }
}
.animate-flash-bang {
  animation: flash-bang 0.6s ease-out forwards;
}

@keyframes shake {
  0%, 100% { transform: translate(0, 0); }
  10%, 30%, 50%, 70%, 90% { transform: translate(-4px, 2px); }
  20%, 40%, 60%, 80% { transform: translate(4px, -2px); }
}
.animate-shake-impact {
  animation: shake 0.4s ease-out forwards 0.7s; 
}

@keyframes shine-blue {
  0% { background-position: 200% center; opacity: 0; transform: scale(0.9); }
  10% { opacity: 1; transform: scale(1); }
  100% { background-position: -200% center; opacity: 1; transform: scale(1); }
}
.animate-shine-blue {
  background-size: 300% auto;
  animation: shine-blue 7s cubic-bezier(0.22, 1, 0.36, 1) forwards 0.8s; 
  opacity: 0;
}

@keyframes shine-red {
  0% { background-position: 200% center; opacity: 0; transform: scale(0.9); }
  10% { opacity: 1; transform: scale(1); }
  100% { background-position: -200% center; opacity: 1; transform: scale(1); }
}
.animate-shine-red {
  background-size: 300% auto;
  animation: shine-red 7s cubic-bezier(0.22, 1, 0.36, 1) forwards 0.9s; 
  opacity: 0;
}

@keyframes line-shoot {
  0% { width: 0; opacity: 0; }
  10% { opacity: 1; } 
  100% { width: 280px; opacity: 1; }
}
.animate-line-shoot {
  animation: line-shoot 1.5s ease-out forwards 1s;
}

@keyframes float-up {
  0% { transform: translateY(0) rotate(0deg); opacity: 0; }
  20% { opacity: 0.6; }
  100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
}
.animate-float {
  animation: float-up linear infinite;
}

@keyframes fade-up {
  0% { opacity: 0; transform: translateY(20px); letter-spacing: 0.2em; }
  100% { opacity: 1; transform: translateY(0); letter-spacing: 0.8em; }
}
.animate-fade-up {
  animation: fade-up 1.5s ease-out forwards 1.2s;
}
</style>

<template>
  <div 
    v-if="isVisible" 
    class="fixed inset-0 z-[9999] flex items-center justify-center overflow-hidden bg-black transition-all duration-700 ease-in-out"
    :class="{ 'opacity-0 scale-110 pointer-events-none': isExiting }"
  >
    
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-slate-900 via-black to-black"></div>
    
    <div class="absolute inset-0 opacity-20 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:50px_50px]"></div>

    <div v-for="p in particles" :key="p.id" 
         class="absolute bottom-0 rounded-full opacity-30 animate-float"
         :class="p.color"
         :style="{ left: p.left, width: p.size, height: p.size, animationDelay: p.delay, animationDuration: p.duration }">
    </div>

    <div class="relative z-10 flex flex-col items-center justify-center w-full px-4 animate-shake-impact">
      
      <h1 class="flex justify-center items-center py-6 px-4 text-6xl md:text-8xl lg:text-9xl font-black tracking-tighter text-center uppercase italic transform -skew-x-12 leading-tight drop-shadow-2xl">
        
        <span class="inline-block relative py-2 pl-4 pr-12 text-transparent bg-clip-text bg-gradient-to-r from-blue-800 via-blue-400 via-white via-blue-400 to-blue-800 animate-shine-blue z-20">
          Rank
        </span>

        <span class="inline-block relative py-2 pl-0 -ml-10 pr-8 text-transparent bg-clip-text bg-gradient-to-r from-red-800 via-red-400 via-white via-red-400 to-red-800 animate-shine-red z-10">
          katsu
        </span>
        
      </h1>

      <div class="relative mt-8">
        <div class="w-0 h-1 bg-gradient-to-r from-blue-600 to-red-600 rounded-full animate-line-shoot shadow-[0_0_25px_rgba(255,255,255,0.6)]"></div>
      </div>

      <p class="mt-10 text-sm md:text-xl font-bold text-gray-400 tracking-[0.8em] uppercase animate-fade-up opacity-0">
        The Ultimate Rank
      </p>

    </div>

    <div class="absolute inset-0 bg-white animate-flash-bang pointer-events-none"></div>
  </div>
</template>