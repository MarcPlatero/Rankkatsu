<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/ErrorInput.vue';
import InputLabel from '@/Components/LabelInput.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
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
        <Head :title="$t('Confirmar contrasenya')" />

        <section
            class="min-h-screen flex items-center justify-center bg-gradient-to-b 
                   from-gray-50 via-white to-gray-100 
                   dark:from-gray-900 dark:via-gray-950 dark:to-black transition-colors duration-500"
        >
            <div class="w-full max-w-md bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-gray-700">
                
                <h1 class="text-3xl font-extrabold text-center mb-6 text-gray-900 dark:text-gray-100 tracking-wide">
                    {{ $t('Confirmar accés') }}
                </h1>

                <div class="mb-4 text-sm text-gray-600 dark:text-gray-400 text-center">
                    {{ $t('Aquesta és una àrea segura. Si us plau, confirma la teva contrasenya abans de continuar.') }}
                </div>

                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="password" :value="$t('Contrasenya')" class="dark:text-gray-200" />
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            autofocus
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full relative inline-block px-6 py-2 rounded-lg text-white font-semibold overflow-hidden group disabled:opacity-50"
                        >
                            <span class="absolute inset-0 bg-gradient-to-r from-blue-600 via-red-500 to-blue-600 animate-gradient-slow bg-[length:200%_200%] transition-all duration-500"></span>
                            <span class="relative z-10">{{ $t('Confirmar') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </GuestLayout>
</template>