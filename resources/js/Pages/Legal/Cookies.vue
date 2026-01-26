<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

// Funció per obrir el bàner de nou
const resetCookies = () => {
    // Disparem un event personalitzat que escoltarà el component CookieConsent
    window.dispatchEvent(new Event('reset-cookie-consent'));
    // Fem scroll cap amunt perquè es vegi bé
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

<template>
  <AppLayout>
    <Head :title="$t('Política de Cookies')" />

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
      <div class="max-w-4xl mx-auto px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl p-8 md:p-12 text-gray-700 dark:text-gray-300 leading-relaxed">
          
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">{{ $t('Política de Cookies') }}</h1>

          <p class="mb-4">
            {{ $t('Aquesta web utilitza cookies per millorar l\'experiència d\'usuari i mostrar publicitat rellevant.') }}
          </p>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ $t('Què són les Cookies?') }}</h2>
          <p class="mb-4">
            {{ $t('Les cookies són petits fitxers de text que es guarden al teu navegador quan visites una pàgina web. Serveixen per recordar les teves preferències, mantenir la teva sessió oberta o recopilar estadístiques.') }}
          </p>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ $t('Tipus de Cookies que utilitzem') }}</h2>
          
          <div class="space-y-4 mt-4">
            <div>
              <h3 class="font-bold text-lg text-gray-800 dark:text-gray-100">1. {{ $t('Essencials (Tècniques)') }}</h3>
              <p class="text-sm mb-2">
                {{ $t('Són necessàries perquè la web funcioni. Inclouen:') }}
              </p>
              <ul class="list-disc pl-5 mt-1 text-sm">
                <li>{{ $t('Mantenir la sessió iniciada (`laravel_session`).') }}</li>
                <li>{{ $t('Seguretat i prevenció de frau (XSRF-TOKEN, Stripe).') }}</li>
                <li>{{ $t('Recordar la teva preferència de cookies.') }}</li>
              </ul>
            </div>

            <div>
              <h3 class="font-bold text-lg text-gray-800 dark:text-gray-100">2. {{ $t('Publicitàries i Analítiques (Google AdSense)') }}</h3>
              <p class="text-sm">
                {{ $t('Utilitzades per Google per mostrar anuncis personalitzats basats en els teus interessos.') }}
                <strong>{{ $t('Aquestes cookies només es carreguen si dones el teu consentiment explícit al nostre bàner de cookies.') }}</strong>
              </p>
            </div>
          </div>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ $t('Gestió de les Cookies') }}</h2>
          <p class="mb-4">
            {{ $t('Pots retirar el teu consentiment o canviar les teves preferències en qualsevol moment fent clic al botó de sota o esborrant les dades de navegació del teu navegador.') }}
          </p>
          
          <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-xl border border-gray-200 dark:border-gray-600 text-center">
              <p class="text-sm font-semibold mb-4 text-gray-800 dark:text-gray-200">
                  {{ $t('Vols canviar la teva configuració actual?') }}
              </p>
              <button 
                  @click="resetCookies"
                  class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold shadow-md transition-all transform hover:-translate-y-0.5"
              >
                  🍪 {{ $t('Configurar Preferències de Cookies') }}
              </button>
          </div>

          <p class="mt-6 text-sm">
            {{ $t('També pots configurar el teu navegador per bloquejar totes les cookies, però és possible que algunes parts de Rankkatsu (com el login) deixin de funcionar correctament.') }}
          </p>

        </div>
      </div>
    </div>
  </AppLayout>
</template>