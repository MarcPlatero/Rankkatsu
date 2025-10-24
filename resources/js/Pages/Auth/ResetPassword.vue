<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ArrowLeftIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  email: {
    type: String,
    required: true,
  },
  token: {
    type: String,
    required: true,
  },
})

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post(route('password.store'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
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
    <Head title="Reset Password" />

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
          Reset Password
        </h1>

        <!-- Formulari -->
        <form @submit.prevent="submit" class="space-y-5">
          <!-- Email -->
          <div>
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
          <div>
            <InputLabel for="password" value="New Password" class="dark:text-gray-200" />
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
          <div>
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
            <InputError class="mt-2" :message="form.errors.password_confirmation" />
          </div>

          <!-- Botons -->
          <div class="mt-6 flex items-center justify-between">
            <a
              href="/"
              class="inline-flex items-center text-sm text-gray-600 dark:text-gray-300 underline hover:text-gray-900 dark:hover:text-white"
              aria-label="Back to home"
            >
              <ArrowLeftIcon class="h-5 w-5" />
            </a>

            <button
              type="submit"
              :disabled="form.processing"
              class="relative inline-block px-6 py-2 rounded-lg text-white font-semibold overflow-hidden group disabled:opacity-50"
            >
              <span
                class="absolute inset-0 bg-gradient-to-r from-blue-600 via-red-500 to-blue-600 
                       animate-gradient-slow bg-[length:200%_200%] transition-all duration-500"
              ></span>
              <span class="relative z-10">Reset Password</span>
            </button>
          </div>
        </form>
      </div>
    </section>
  </GuestLayout>
</template>
