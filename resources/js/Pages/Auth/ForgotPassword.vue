<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/ErrorInput.vue';
import InputLabel from '@/Components/LabelInput.vue';
import TextInput from '@/Components/InputText.vue';
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ArrowLeftIcon } from '@heroicons/vue/24/outline'
import { trans } from 'laravel-vue-i18n';

defineProps({
  status: String,
})

const form = useForm({
  email: '',
})

const submit = () => {
  form.post(route('password.email'))
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
    <Head :title="$t('Recuperar contrasenya')" />

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
          {{ $t('Recuperar contrasenya') }}
        </h1>

        <p class="text-sm text-gray-600 dark:text-gray-400 text-center mb-6">
          {{ $t("Has oblidat la contrasenya? Cap problema. Introdueix el teu correu electrònic i t'enviarem un enllaç per restablir-la.") }}
        </p>

        <div
          v-if="status"
          class="mb-4 text-sm font-medium text-green-600 dark:text-green-400 text-center"
        >
          {{ status }}
        </div>

        <form @submit.prevent="submit">
          <div>
            <InputLabel for="email" :value="$t('Correu electrònic')" class="dark:text-gray-200" />
            <TextInput
              id="email"
              type="email"
              class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700"
              v-model="form.email"
              required
              autofocus
              autocomplete="username"
            />
            <InputError class="mt-2" :message="form.errors.email" />
          </div>

          <div class="mt-6 flex items-center justify-between">
            <Link
              href="/"
              class="inline-flex items-center text-sm text-gray-600 dark:text-gray-300 underline hover:text-gray-900 dark:hover:text-white"
              :aria-label="$t('Tornar a l\'inici')"
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
              <span class="relative z-10">{{ $t('Enviar enllaç') }}</span>
            </button>
          </div>
        </form>
      </div>
    </section>
  </GuestLayout>
</template>