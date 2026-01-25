<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
</script>

<template>
  <AppLayout>
    <Head title="Política de Privacitat" />

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
      <div class="max-w-4xl mx-auto px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl p-8 md:p-12 text-gray-700 dark:text-gray-300 leading-relaxed">
          
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Política de Privacitat</h1>
          
          <p>
            A Rankkatsu, ens prenem molt seriosament la teva privacitat. Aquesta política descriu quines dades recollim i com les utilitzem.
          </p>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">1. Responsable del Tractament</h2>
          <p>
            El responsable de les dades és: <br>
            <strong>Nom:</strong> Marc Platero <br>
            <strong>Correu de contacte:</strong> legal@rankkatsu.com <br>
            <strong>Ubicació:</strong> Barcelona, Espanya
          </p>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">2. Dades que Recollim</h2>
          <ul class="list-disc pl-5 space-y-2">
            <li><strong>Dades de Registre:</strong> Nom, correu electrònic i contrasenya (encriptada).</li>
            <li><strong>Contingut:</strong> Rànquings, imatges i comentaris que puges voluntàriament.</li>
            <li><strong>Dades Tècniques:</strong> Adreça IP, tipus de navegador i dades d'ús per a seguretat i analítica.</li>
          </ul>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">3. Finalitat de les Dades</h2>
          <p>Utilitzem les teves dades per a:</p>
          <ul class="list-disc pl-5 space-y-2 mt-2">
            <li>Gestionar el teu compte i l'accés a la plataforma.</li>
            <li>Processar pagaments i subscripcions.</li>
            <li>Mostrar publicitat personalitzada (només si acceptes les cookies).</li>
            <li>Detectar i prevenir frau o contingut abusiu.</li>
          </ul>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">4. Cessió a Tercers</h2>
          <p>No venem les teves dades. Tanmateix, compartim informació necessària amb proveïdors de serveis:</p>
          <ul class="list-disc pl-5 space-y-2 mt-2">
            <li><strong>Stripe:</strong> Per processar els pagaments de forma segura. Rankkatsu no guarda les teves dades bancàries.</li>
            <li><strong>Google AdSense:</strong> Per mostrar publicitat. Google pot utilitzar cookies per personalitzar els anuncis.</li>
            <li><strong>Amazon S3 / R2 (si n'uses):</strong> Per a l'emmagatzematge d'imatges al núvol.</li>
          </ul>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">5. Els teus Drets</h2>
          <p>
            Sota el RGPD (Reglament General de Protecció de Dades), tens dret a accedir, rectificar, suprimir i limitar el tractament de les teves dades. 
            Pots exercir aquests drets des del teu perfil o enviant un correu a legal@rankkatsu.com.
          </p>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">6. Eliminació del Compte</h2>
          <p>
            Pots eliminar el teu compte i totes les teves dades permanentment des de la secció de configuració del teu perfil en qualsevol moment.
          </p>

        </div>
      </div>
    </div>
  </AppLayout>
</template>