<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'

const showBanner = ref(false)

// Funció per obrir el bàner (resetejant si cal)
const openBanner = () => {
    showBanner.value = true;
}

onMounted(() => {
    const consent = localStorage.getItem('cookie_consent')
    
    if (!consent) {
        setTimeout(() => { showBanner.value = true }, 1000)
    } else if (consent === 'accepted') {
        triggerAdSense()
    }

    window.addEventListener('reset-cookie-consent', openBanner)
})

onBeforeUnmount(() => {
    window.removeEventListener('reset-cookie-consent', openBanner)
})

const accept = () => {
    localStorage.setItem('cookie_consent', 'accepted')
    showBanner.value = false
    triggerAdSense()
}

const reject = () => {
    localStorage.setItem('cookie_consent', 'rejected')
    showBanner.value = false
    
    if (window.adsenseLoaded) {
        window.location.reload();
    }
}

// Funció per injectar l'script d'AdSense
const triggerAdSense = () => {
    if (window.adsenseLoaded) return; 

    // Evitar carregar en local per no ser banejat per Google
    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') return;

    const script = document.createElement('script')
    // Substitueix el meu ID aquí
    script.src = "https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-XXXXXXXXXXXXXXXX"
    script.async = true
    script.crossOrigin = "anonymous"
    document.head.appendChild(script)
    
    window.adsenseLoaded = true
}
</script>

<template>
    <transition
        enter-active-class="transition ease-out duration-500"
        enter-from-class="transform translate-y-full opacity-0"
        enter-to-class="transform translate-y-0 opacity-100"
        leave-active-class="transition ease-in duration-300"
        leave-from-class="transform translate-y-0 opacity-100"
        leave-to-class="transform translate-y-full opacity-0"
    >
        <div 
            v-if="showBanner" 
            class="fixed bottom-0 left-0 right-0 z-[100] bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 shadow-[0_-4px_20px_rgba(0,0,0,0.1)] p-6 md:p-8"
        >
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
                
                <div class="text-center md:text-left">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                        🍪 {{ $t('Usem cookies') }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 max-w-2xl leading-relaxed">
                        {{ $t('Utilitzem cookies pròpies i de tercers per millorar la teva experiència i mostrar publicitat personalitzada. Pots acceptar-les totes o rebutjar les no essencials.') }}
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <button 
                        @click="reject" 
                        class="px-6 py-2.5 rounded-lg text-sm font-semibold border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition"
                    >
                        {{ $t('Rebutjar') }}
                    </button>
                    <button 
                        @click="accept" 
                        class="px-6 py-2.5 rounded-lg text-sm font-semibold bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-500/30 transition transform active:scale-95"
                    >
                        {{ $t('Acceptar tot') }}
                    </button>
                </div>

            </div>
        </div>
    </transition>
</template>