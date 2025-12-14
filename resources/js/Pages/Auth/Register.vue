<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ArrowLeftIcon } from '@heroicons/vue/24/outline'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
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
    <Head title="Register" />

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
          Register
        </h1>

        <!-- Formulari -->
        <form @submit.prevent="submit">
          <!-- Nom -->
          <div>
            <InputLabel for="name" value="Name" class="dark:text-gray-200" />
            <TextInput
              id="name"
              type="text"
              class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700"
              v-model="form.name"
              required
              autofocus
              autocomplete="name"
              maxlength="30"
            />
            <InputError class="mt-2" :message="form.errors.name" />
          </div>

          <!-- Email -->
          <div class="mt-4">
            <InputLabel for="email" value="Email" class="dark:text-gray-200" />
            <TextInput
              id="email"
              type="email"
              class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700"
              v-model="form.email"
              required
              autocomplete="username"
            />
            <InputError class="mt-2" :message="form.errors.email" />
          </div>

          <!-- Password -->
          <div class="mt-4">
            <InputLabel for="password" value="Password" class="dark:text-gray-200" />
            <TextInput
              id="password"
              type="password"
              class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700"
              v-model="form.password"
              required
              autocomplete="new-password"
            />
            <InputError class="mt-2" :message="form.errors.password" />
          </div>

          <!-- Confirm Password -->
          <div class="mt-4">
            <InputLabel
              for="password_confirmation"
              value="Confirm Password"
              class="dark:text-gray-200"
            />
            <TextInput
              id="password_confirmation"
              type="password"
              class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700"
              v-model="form.password_confirmation"
              required
              autocomplete="new-password"
            />
            <InputError
              class="mt-2"
              :message="form.errors.password_confirmation"
            />
          </div>

          <!-- Botons -->
          <div class="mt-6 flex items-center justify-between">
            <!-- Tornar a home -->
            <Link
              href="/"
              class="inline-flex items-center text-sm text-gray-600 dark:text-gray-300 underline hover:text-gray-900 dark:hover:text-white"
              aria-label="Back to home"
            >
              <ArrowLeftIcon class="h-5 w-5" />
            </Link>

            <div class="flex items-center space-x-4">
              <!-- Ja registrat -->
              <Link
                :href="route('login')"
                class="text-sm text-gray-600 dark:text-gray-300 underline hover:text-gray-900 dark:hover:text-white"
              >
                Already registered?
              </Link>

              <!-- Botó principal -->
              <button
                type="submit"
                :disabled="form.processing"
                class="relative inline-block px-6 py-2 rounded-lg text-white font-semibold overflow-hidden group disabled:opacity-50"
              >
                <span
                  class="absolute inset-0 bg-gradient-to-r from-blue-600 via-red-500 to-blue-600 
                         animate-gradient-slow bg-[length:200%_200%] transition-all duration-500"
                ></span>
                <span class="relative z-10">Register</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </section>
  </GuestLayout>
</template>