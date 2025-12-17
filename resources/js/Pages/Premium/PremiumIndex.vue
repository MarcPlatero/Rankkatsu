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
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}
.animate-shine {
  animation: shine 2s infinite;
}
@keyframes pulse-slow {
  0%, 100% { opacity: 0.2; }
  50% { opacity: 0.3; }
}
.animate-pulse-slow {
  animation: pulse-slow 4s infinite;
}
</style>

<template>
  <AppLayout>
    <div class="max-w-6xl mx-auto py-16 px-6 text-center">
      
      <h1 class="text-4xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 mb-6 drop-shadow-sm pb-2">
        Rankkatsu Premium
      </h1>
      
      <p class="text-xl text-gray-600 dark:text-gray-300 mb-16 max-w-2xl mx-auto">
        Desbloqueja tot el potencial. Sense anuncis, amb estil exclusiu i per sempre.
      </p>

      <div v-if="isPremium" class="max-w-xl mx-auto">
        
        <div v-if="$page.props.flash?.error" class="mb-8 p-4 bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-200 border border-red-200 dark:border-red-800 rounded-xl text-sm font-bold shadow-sm">
            {{ $page.props.flash.error }}
        </div>

        <div class="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 p-6 rounded-2xl font-bold text-xl border border-green-200 dark:border-green-800 mb-8 animate-bounce">
          ✅ Ja ets membre Premium! Gràcies pel teu suport!
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Gestió de la Subscripció</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4 text-sm">
                Vols veure les teves factures, canviar el mètode de pagament o cancel·lar la subscripció?
            </p>
            
            <a 
                :href="route('premium.manage')"
                class="inline-flex items-center justify-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 dark:text-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer"
            >
                ⚙️ Gestionar Subscripció a Stripe
            </a>
            
            <p class="text-xs text-gray-500 mt-2 italic">* Si tens un pla Lifetime, no cal gestionar res.</p>
        </div>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 p-8 flex flex-col relative overflow-hidden transition-transform hover:-translate-y-1">
          <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Mensual</h3>
          <div class="text-4xl font-extrabold text-blue-600 dark:text-blue-400 mb-6">
            1,99€ <span class="text-base font-normal text-gray-500">/ mes</span>
          </div>
          
          <ul class="text-left space-y-4 mb-8 text-gray-600 dark:text-gray-300 flex-1">
            <li class="flex items-center gap-3"><span class="text-green-500">✔</span> Navegació 100% sense anuncis</li>
            <li class="flex items-center gap-3"><span class="text-green-500">✔</span> Insignia <b>Premium</b> al perfil</li>
            <li class="flex items-center gap-3"><span class="text-green-500">✔</span> Icones exclusives en votar</li>
            <li class="flex items-center gap-3"><span class="text-gray-400">✖</span> Pagament únic</li>
          </ul>

          <button 
            @click="subscribe('monthly')"
            :disabled="processing"
            class="w-full py-3 px-6 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-bold rounded-xl transition-colors disabled:opacity-50"
          >
            <span v-if="processing">Carregant...</span>
            <span v-else>Subscriure'm</span>
          </button>
        </div>

        <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white rounded-3xl shadow-2xl border border-purple-500/50 p-8 flex flex-col relative overflow-hidden transform md:scale-105 ring-4 ring-purple-500/20">
          
          <div class="absolute top-0 right-0 bg-purple-600 text-white text-xs font-bold px-3 py-1 rounded-bl-xl">BEST VALUE</div>
          
          <h3 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 mb-2">LIFETIME</h3>
          <div class="text-5xl font-extrabold mb-6">
            19,99€
            <span class="text-sm font-normal text-gray-400 block mt-1">un sol pagament</span>
          </div>
          
          <ul class="text-left space-y-4 mb-8 text-gray-200 flex-1">
            <li class="flex items-center gap-3"><span class="text-purple-400">✨</span> <b>TOT el del pla mensual</b></li>
            <li class="flex items-center gap-3"><span class="text-purple-400">✨</span> Paga una vegada, <b>és teu per sempre</b></li>
            <li class="flex items-center gap-3"><span class="text-purple-400">✨</span> Insignia <b>LEGEND</b> daurada</li>
            <li class="flex items-center gap-3"><span class="text-purple-400">✨</span> Accés prioritari a novetats</li>
          </ul>

          <button 
            @click="subscribe('lifetime')"
            :disabled="processing"
            class="w-full py-4 px-6 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-500 hover:to-blue-500 text-white font-bold rounded-xl shadow-lg transition-all relative overflow-hidden group disabled:opacity-50"
          >
            <div class="absolute inset-0 bg-white/20 group-hover:translate-x-full transition-transform duration-500 -skew-x-12 -translate-x-full"></div>
            <span v-if="processing">Processant...</span>
            <span v-else>Obtenir accés PER SEMPRE</span>
          </button>
          
          <p class="text-xs text-gray-500 mt-4">Estalvia 23€/any comparat amb el mensual.</p>
        </div>

      </div>
      
      <p class="text-sm text-gray-400 mt-12 flex justify-center items-center gap-2">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
        Pagament 100% segur processat per Stripe.
      </p>

    </div>
  </AppLayout>
</template>