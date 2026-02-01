<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
</script>

<template>
  <AppLayout>
    <Head :title="$t('Política de Privacitat')" />

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
      <div class="max-w-4xl mx-auto px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl p-8 md:p-12 text-gray-700 dark:text-gray-300 leading-relaxed">
          
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">{{ $t('Política de Privacitat') }}</h1>
          
          <p>
            {{ $t('A Rankkatsu, ens prenem molt seriosament la teva privacitat. Aquesta política descriu quines dades recollim i com les utilitzem.') }}
          </p>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">1. {{ $t('Responsable del Tractament') }}</h2>
          <p>
            {{ $t('El responsable de les dades és:') }} <br>
            <strong>{{ $t('Nom') }}:</strong> Marc Platero <br>
            <strong>{{ $t('Correu de contacte') }}:</strong> rankkatsu.contact@gmail.com <br>
            <strong>{{ $t('Ubicació') }}:</strong> Barcelona, {{ $t('Espanya') }}
          </p>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">2. {{ $t('Dades que Recollim') }}</h2>
          <ul class="list-disc pl-5 space-y-2">
            <li><strong>{{ $t('Dades de Registre') }}:</strong> {{ $t('Nom, correu electrònic i contrasenya (encriptada).') }}</li>
            <li><strong>{{ $t('Contingut') }}:</strong> {{ $t('Rànquings, imatges i comentaris que puges voluntàriament.') }}</li>
            <li><strong>{{ $t('Dades Tècniques') }}:</strong> {{ $t('Adreça IP, tipus de navegador i dades d\'ús per a seguretat i analítica.') }}</li>
          </ul>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">3. {{ $t('Finalitat de les Dades') }}</h2>
          <p>{{ $t('Utilitzem les teves dades per a:') }}</p>
          <ul class="list-disc pl-5 space-y-2 mt-2">
            <li>{{ $t('Gestionar el teu compte i l\'accés a la plataforma.') }}</li>
            <li>{{ $t('Processar pagaments i subscripcions.') }}</li>
            <li>{{ $t('Mostrar publicitat personalitzada (només si acceptes les cookies).') }}</li>
            <li>{{ $t('Detectar i prevenir frau o contingut abusiu.') }}</li>
          </ul>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">4. {{ $t('Cessió a Tercers') }}</h2>
          <p>{{ $t('No venem les teves dades. Tanmateix, compartim informació necessària amb proveïdors de serveis:') }}</p>
          <ul class="list-disc pl-5 space-y-2 mt-2">
            <li><strong>Stripe:</strong> {{ $t('Per processar els pagaments de forma segura. Rankkatsu no guarda les teves dades bancàries.') }}</li>
            <li><strong>Google AdSense:</strong> {{ $t('Per mostrar publicitat. Google pot utilitzar cookies per personalitzar els anuncis.') }}</li>
          </ul>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">5. {{ $t('Els teus Drets') }}</h2>
          <p>
            {{ $t('Sota el RGPD (Reglament General de Protecció de Dades), tens dret a accedir, rectificar, suprimir i limitar el tractament de les teves dades. Pots exercir aquests drets des del teu perfil o enviant un correu a rankkatsu.contact@gmail.com') }}
          </p>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">6. {{ $t('Eliminació del Compte') }}</h2>
          <p>
            {{ $t('Pots eliminar el teu compte i totes les teves dades permanentment des de la secció de configuració del teu perfil en qualsevol moment.') }}
          </p>

        </div>
      </div>
    </div>
  </AppLayout>
</template>