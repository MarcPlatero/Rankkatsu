<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref } from 'vue'

const props = defineProps({ 
    isPremium: Boolean,
    csrfToken: String 
})

const processing = ref(false)

const subscribe = (type) => {
    processing.value = true
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = route('premium.subscribe');
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = props.csrfToken; 
    form.appendChild(csrfInput);

    const typeInput = document.createElement('input');
    typeInput.type = 'hidden';
    typeInput.name = 'type';
    typeInput.value = type;
    form.appendChild(typeInput);

    document.body.appendChild(form);
    form.submit();
}
</script>

<style scoped>
@keyframes shine {
  0% { transform: translateX(-100%) skewX(-15deg); }
  100% { transform: translateX(200%) skewX(-15deg); }
}
.animate-shine-fast {
  animation: shine 1.5s infinite linear;
}

.bg-grid {
    background-size: 40px 40px;
    background-image: radial-gradient(circle, rgba(99, 102, 241, 0.1) 1px, transparent 1px);
}
</style>

<template>
  <AppLayout>
    <div class="relative min-h-[90vh] flex flex-col items-center justify-center py-16 px-4 sm:px-6 overflow-hidden">
      
      <div class="absolute inset-0 bg-grid pointer-events-none"></div>
      <div class="absolute top-20 left-10 w-72 h-72 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute bottom-20 right-10 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

      <div class="relative z-10 w-full max-w-6xl text-center">
      
        <h1 class="text-5xl md:text-7xl font-black text-transparent bg-clip-text bg-gradient-to-r from-gray-900 via-blue-800 to-purple-900 dark:from-white dark:via-blue-300 dark:to-purple-300 mb-6 drop-shadow-sm pb-2 tracking-tight">
          Rankkatsu Premium
        </h1>
        
        <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 mb-16 max-w-2xl mx-auto leading-relaxed">
          Puja de nivell. Desbloqueja tot el potencial sense límits.
        </p>

        <div v-if="isPremium" class="max-w-xl mx-auto">
            
            <div v-if="$page.props.flash?.error" class="mb-8 p-4 bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-200 border border-red-200 dark:border-red-800 rounded-xl text-sm font-bold shadow-sm">
                {{ $page.props.flash.error }}
            </div>

            <div class="relative overflow-hidden bg-gradient-to-br from-green-500 to-emerald-700 text-white p-8 rounded-3xl shadow-2xl mb-8 transform hover:scale-105 transition-transform duration-300">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/20 rounded-full blur-xl"></div>
                <h2 class="text-3xl font-bold mb-2">🎉 Ja ets Premium!</h2>
                <p class="opacity-90">Gràcies per formar part d'això :)</p>
            </div>

            <div class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm p-8 rounded-3xl shadow-lg border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">Gestió de la Subscripció</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6 text-sm">
                    Vols veure les teves factures o gestionar el teu pla?
                </p>
                
                <a 
                    :href="route('premium.manage')"
                    class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-bold rounded-xl transition-all
                    text-white bg-gray-900 hover:bg-gray-800 hover:shadow-lg hover:-translate-y-0.5
                    dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200"
                >
                    ⚙️ Gestionar a Stripe
                </a>
                
                <p class="text-xs text-gray-500 mt-4 italic">* Els plans Lifetime no requereixen gestió.</p>
            </div>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto items-center">
            
            <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-xl border border-gray-200 dark:border-gray-700 p-8 flex flex-col relative overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 hover:border-blue-300 dark:hover:border-blue-600 z-0">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-400 to-indigo-500"></div>
                
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2 text-left mt-2">Mensual</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-6 text-left">Ideal per començar a provar-ho.</p>
                
                <div class="text-left mb-8">
                    <span class="text-4xl font-extrabold text-gray-900 dark:text-white">1,99€</span>
                    <span class="text-gray-500 dark:text-gray-400 font-medium"> / mes</span>
                </div>
                
                <ul class="space-y-4 mb-8 text-left text-gray-600 dark:text-gray-300">
                    <li class="flex items-center gap-3">
                        <div class="bg-blue-100 dark:bg-blue-900/50 p-1.5 rounded-full text-blue-600 dark:text-blue-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="font-medium">Sense anuncis</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="bg-blue-100 dark:bg-blue-900/50 p-1.5 rounded-full text-blue-600 dark:text-blue-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        Insignia Premium
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="bg-blue-100 dark:bg-blue-900/50 p-1.5 rounded-full text-blue-600 dark:text-blue-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        Icones exclusives
                    </li>
                </ul>

                <button 
                    @click="subscribe('monthly')"
                    :disabled="processing"
                    class="w-full py-3.5 px-6 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-blue-500/30 disabled:opacity-50 transform active:scale-95"
                >
                    <span v-if="processing">Carregant...</span>
                    <span v-else>Subscriure'm ara</span>
                </button>
            </div>

            <div class="relative group z-10 md:-my-4">
                <div class="absolute -inset-1 bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 rounded-[2.1rem] blur opacity-75 group-hover:opacity-100 transition duration-1000 group-hover:duration-200 animate-pulse"></div>
                
                <div class="relative bg-gray-900 text-white rounded-[2rem] p-8 md:p-10 flex flex-col h-full overflow-hidden border border-white/10">
                    
                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-purple-500/50 rounded-full blur-[60px]"></div>
                    
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-purple-100 to-white">LIFETIME</h3>
                    </div>

                    <p class="text-purple-200 text-sm mb-8 text-left">L'opció definitiva per als veritables fans.</p>
                    
                    <div class="text-left mb-10 relative">
                        <span class="text-6xl font-black tracking-tighter drop-shadow-lg">19,99€</span>
                        <span class="text-gray-400 font-medium text-lg block mt-2">pagament únic</span>
                    </div>
                    
                    <ul class="space-y-5 mb-10 text-left text-lg">
                        <li class="flex items-start gap-4">
                            <span class="text-2xl mt-0.5 filter drop-shadow-[0_0_10px_rgba(168,85,247,0.8)]">✨</span> 
                            <span class="font-bold text-gray-100">TOT el del pla mensual</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <span class="text-2xl mt-0.5 filter drop-shadow-[0_0_10px_rgba(168,85,247,0.8)]">✨</span> 
                            <span class="font-bold text-gray-100">Paga una vegada, <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-300 to-pink-300">és teu per sempre.</span></span>
                        </li>
                    </ul>

                    <button 
                        @click="subscribe('lifetime')"
                        :disabled="processing"
                        class="w-full py-4 px-6 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-500 hover:via-purple-500 hover:to-pink-500 text-white text-lg font-black rounded-xl shadow-lg hover:shadow-purple-500/40 transition-all relative overflow-hidden group disabled:opacity-50 transform hover:scale-[1.02]"
                    >
                        <div class="absolute inset-0 w-1/2 bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:animate-shine-fast"></div>
                        <span v-if="processing">Processant...</span>
                        <span v-else>OBTENIR PER SEMPRE</span>
                    </button>
                    
                </div>
            </div>

        </div>
        
        <p class="text-sm text-gray-400 mt-16 flex justify-center items-center gap-2 opacity-70 hover:opacity-100 transition-opacity">
            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
            Pagament 100% segur i encriptat via Stripe.
        </p>

      </div>
    </div>
  </AppLayout>
</template>