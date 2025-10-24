<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ArrowLeftIcon } from '@heroicons/vue/24/outline'

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
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}
.animate-gradient-slow {
  animation: gradient-slow 8s ease infinite;
}
</style>

<template>
  <GuestLayout>
    <Head title="Forgot Password" />

    <section
      class="min-h-screen flex items-center justify-center bg-gradient-to-b 
             from-gray-50 via-white to-gray-100 
             dark:from-gray-900 dark:via-gray-950 dark:to-black transition-colors duration-500"
    >
      <div
        class="w-full max-w-md bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-gray-700"
      >
        <!-- Títol -->
        <h1
          class="text-3xl font-extrabold text-center mb-6 text-gray-900 dark:text-gray-100 tracking-wide"
        >
          Forgot Password
        </h1>

        <!-- Text explicatiu -->
        <p class="text-sm text-gray-600 dark:text-gray-400 text-center mb-6">
          Forgot your password? No problem. Just enter your email and we’ll send
          you a link to reset it.
        </p>

        <!-- Missatge d’estat -->
        <div
          v-if="status"
          class="mb-4 text-sm font-medium text-green-600 dark:text-green-400 text-center"
        >
          {{ status }}
        </div>

        <!-- Formulari -->
        <form @submit.prevent="submit">
          <!-- Email -->
          <div>
            <InputLabel for="email" value="Email" class="dark:text-gray-200" />
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

          <!-- Botons -->
          <div class="mt-6 flex items-center justify-between">
            <Link
              href="/"
              class="inline-flex items-center text-sm text-gray-600 dark:text-gray-300 underline hover:text-gray-900 dark:hover:text-white"
              aria-label="Back to home"
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
              <span class="relative z-10">Send Reset Link</span>
            </button>
          </div>
        </form>
      </div>
    </section>
  </GuestLayout>
</template>
