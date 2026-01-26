<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ArrowLeftIcon } from '@heroicons/vue/24/outline'
import { trans } from 'laravel-vue-i18n';

defineProps({
  status: String,
})

const form = useForm({})

const submit = () => {
  form.post(route('verification.send'))
}
</script>

<style scoped>
@keyframes gradient-slow {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
.animate-gradient-slow {
  animation: gradient-slow 8s ease infinite;
}
</style>

<template>
  <GuestLayout>
    <Head :title="$t('Verificar correu electrònic')" />

    <section
      class="min-h-screen flex items-center justify-center bg-gradient-to-b 
             from-gray-50 via-white to-gray-100 
             dark:from-gray-900 dark:via-gray-950 dark:to-black transition-colors duration-500"
    >
      <div
        class="w-full max-w-md bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-gray-700"
      >
        <h1
          class="text-3xl font-extrabold text-center mb-6 text-gray-900 dark:text-gray-100 tracking-wide"
        >
          {{ $t('Verificar correu') }}
        </h1>

        <p class="text-sm text-gray-600 dark:text-gray-400 text-center mb-6">
          {{ $t("Gràcies per registrar-te! Abans de començar, si us plau verifica el teu correu electrònic fent clic a l'enllaç que t'acabem d'enviar. Si no l'has rebut, te'n podem enviar un altre a continuació.") }}
        </p>

        <div
          v-if="status === 'verification-link-sent'"
          class="mb-4 text-sm font-medium text-green-600 dark:text-green-400 text-center"
        >
          {{ $t("S'ha enviat un nou enllaç de verificació a la teva adreça de correu electrònic.") }}
        </div>

        <form @submit.prevent="submit" class="mt-6 flex items-center justify-between">
          <Link
            href="/"
            class="inline-flex items-center text-sm text-gray-600 dark:text-gray-300 underline hover:text-gray-900 dark:hover:text-white"
          >
            <ArrowLeftIcon class="h-5 w-5" />
          </Link>

          <button
            type="submit"
            :disabled="form.processing"
            class="relative inline-block px-6 py-2 rounded-lg text-white font-semibold overflow-hidden group disabled:opacity-50"
          >
            <span
              class="absolute inset-0 bg-gradient-to-r from-blue-600 via-red-500 to-blue-600 
                     animate-gradient-slow bg-[length:200%_200%] transition-all duration-500"
            ></span>
            <span class="relative z-10">{{ $t('Reenviar correu') }}</span>
          </button>
        </form>
      </div>
    </section>
  </GuestLayout>
</template>