<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ArrowLeftIcon } from '@heroicons/vue/24/outline'

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
    <Head title="Verify Email" />

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
          Verify Email
        </h1>

        <!-- Text explicatiu -->
        <p class="text-sm text-gray-600 dark:text-gray-400 text-center mb-6">
          Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.
          If you didn’t receive the email, you can request another one below.
        </p>

        <!-- Missatge d’estat -->
        <div
          v-if="status === 'verification-link-sent'"
          class="mb-4 text-sm font-medium text-green-600 dark:text-green-400 text-center"
        >
          A new verification link has been sent to your email address.
        </div>

        <!-- Botons -->
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
            <span class="relative z-10">Resend Verification Email</span>
          </button>
        </form>
      </div>
    </section>
  </GuestLayout>
</template>
