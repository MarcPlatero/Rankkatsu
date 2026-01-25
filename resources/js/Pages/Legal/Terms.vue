<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
</script>

<template>
  <AppLayout>
    <Head title="Termes i Condicions" />

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
      <div class="max-w-4xl mx-auto px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl p-8 md:p-12 text-gray-700 dark:text-gray-300 leading-relaxed">
          
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Termes i Condicions d'Ús</h1>
          <p class="text-sm text-gray-500 mb-8">Última actualització: {{ new Date().toLocaleDateString() }}</p>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">1. Introducció</h2>
          <p>
            Benvingut a <strong>Rankkatsu</strong>. Aquests termes i condicions regeixen l'ús de la nostra plataforma. 
            En registrar-te o utilitzar els nostres serveis, acceptes complir aquestes condicions.
          </p>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">2. Comptes d'Usuari</h2>
          <ul class="list-disc pl-5 space-y-2">
            <li>Ets responsable de mantenir la seguretat de la teva contrasenya.</li>
            <li>No pots utilitzar la identitat d'una altra persona.</li>
            <li>Ens reservem el dret de suspendre o eliminar comptes que violin les nostres normes (spam, comportament abusiu, etc.).</li>
          </ul>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">3. Contingut Generat per l'Usuari</h2>
          <p>
            Els usuaris poden pujar imatges i crear rànquings.
          </p>
          <ul class="list-disc pl-5 space-y-2 mt-2">
            <li><strong>Propietat:</strong> Tu conserves els drets sobre el contingut que puges, però atorgues a Rankkatsu una llicència mundial, no exclusiva i gratuïta per mostrar-lo a la plataforma.</li>
            <li><strong>Responsabilitat:</strong> Declares que tens els drets necessaris sobre les imatges que puges.</li>
            <li><strong>Prohibicions:</strong> Està prohibit pujar contingut il·legal, pornogràfic, violent o que infringeixi drets d'autor de tercers. Utilitzem sistemes de moderació (IA i manual) i eliminarem qualsevol contingut inadequat.</li>
          </ul>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">4. Subscripcions Premium i Pagaments</h2>
          <p>
            Rankkatsu ofereix plans de pagament (Mensual i Lifetime) processats de forma segura a través de <strong>Stripe</strong>.
          </p>
          <ul class="list-disc pl-5 space-y-2 mt-2">
            <li>Els pagaments no són reemborsables, excepte en casos exigits per la llei.</li>
            <li>Pots cancel·lar la teva subscripció mensual en qualsevol moment des del teu perfil. Tindràs accés Premium fins al final del període de facturació.</li>
            <li>El pla Lifetime és un pagament únic que atorga accés de per vida mentre la plataforma estigui operativa.</li>
          </ul>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">5. Publicitat</h2>
          <p>
            La versió gratuïta de Rankkatsu mostra publicitat a través de Google AdSense. En utilitzar el servei gratuït, acceptes la visualització d'aquests anuncis. Els usuaris Premium gaudeixen d'una experiència sense publicitat.
          </p>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">6. Limitació de Responsabilitat</h2>
          <p>
            Rankkatsu s'ofereix "tal qual". No garantim que la plataforma estigui lliure d'errors o disponible ininterrompudament. No ens fem responsables dels danys derivats de l'ús del servei o del contingut pujat per altres usuaris.
          </p>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">7. Creació de Contingut i Streaming</h2>
          <p>
            Encoratgem l'ús de Rankkatsu per a la creació de contingut a plataformes de vídeo i streaming com <strong>YouTube, Twitch, Kick, TikTok</strong>, etc.
          </p>
          <ul class="list-disc pl-5 space-y-2 mt-2">
            <li>Tens permís per gravar, emetre en directe i monetitzar el contingut generat utilitzant la nostra interfície i funcionalitats.</li>
            <li>No reclamarem copyright sobre els teus vídeos o directes on aparegui la nostra web.</li>
            <li>Tanmateix, tingues en compte que les imatges dins dels rànquings són pujades pels usuaris i Rankkatsu no és propietari dels seus drets d'autor originals.</li>
          </ul>

          <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">8. Contacte</h2>
          <p>
            Per a qualsevol dubte legal o reclamació de copyright (DMCA), pots contactar amb nosaltres a: <strong>legal@rankkatsu.com</strong>.
          </p>

        </div>
      </div>
    </div>
  </AppLayout>
</template>