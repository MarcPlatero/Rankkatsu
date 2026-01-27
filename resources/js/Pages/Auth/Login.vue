<script setup>
import Checkbox from '@/Components/Checkbox.vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/ErrorInput.vue';
import InputLabel from '@/Components/LabelInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/InputText.vue';
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ArrowLeftIcon } from '@heroicons/vue/24/outline'
import { trans } from 'laravel-vue-i18n';

defineProps({
  canResetPassword: Boolean,
  status: String,
})

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  })
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
    <Head :title="$t('Iniciar Sessió')" />

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
          {{ $t('Iniciar Sessió') }}
        </h1>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600 dark:text-green-400">
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

          <div class="mt-4">
            <InputLabel for="password" :value="$t('Contrasenya')" class="dark:text-gray-200" />
            <TextInput
              id="password"
              type="password"
              class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700"
              v-model="form.password"
              required
              autocomplete="current-password"
            />
            <InputError class="mt-2" :message="form.errors.password" />
          </div>

          <div class="mt-4 block">
            <label class="flex items-center">
              <Checkbox name="remember" v-model:checked="form.remember" />
              <span class="ms-2 text-sm text-gray-600 dark:text-gray-300">{{ $t('Recorda’m') }}</span>
            </label>
          </div>

          <div class="mt-6 flex items-center justify-between">
            <Link
              href="/"
              class="inline-flex items-center text-sm text-gray-600 dark:text-gray-300 underline hover:text-gray-900 dark:hover:text-white"
              :aria-label="$t('Tornar a l\'inici')"
            >
              <ArrowLeftIcon class="h-5 w-5" />
            </Link>

            <div class="flex items-center space-x-4">
              <Link
                v-if="canResetPassword"
                :href="route('password.request')"
                class="text-sm text-gray-600 dark:text-gray-300 underline hover:text-gray-900 dark:hover:text-white"
              >
                {{ $t('Has oblidat la contrasenya?') }}
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
                <span class="relative z-10">{{ $t('Entrar') }}</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </section>
  </GuestLayout>
</template>