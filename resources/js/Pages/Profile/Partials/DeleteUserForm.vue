<script setup>
import { ref, nextTick } from 'vue';
import { useForm } from '@inertiajs/vue3';

const isOpen = ref(false); 
const confirmingUserDeletion = ref(false); 
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.3s ease;
}
.fade-slide-enter-from,
.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
</style>

<template>
    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
        <button
            @click="isOpen = !isOpen"
            class="flex items-center justify-between w-full text-left text-lg font-semibold 
                   text-gray-800 dark:text-gray-100 
                   hover:text-blue-600 
                   dark:hover:text-red-400 
                   transition"
        >
            <span class="flex items-center gap-2">⚠️ {{ $t('Esborrar Compte') }}</span>
            <svg
                :class="['w-5 h-5 transition-transform', { 'rotate-180': isOpen }]"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <transition name="fade-slide">
            <div v-if="isOpen" class="mt-4">
                <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-xl p-6 shadow-sm">
                    
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ $t('Esborrar Compte Permanentment') }}
                    </h3>

                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 max-w-xl">
                        {{ $t("Un cop esborrat el compte, tots els seus recursos i dades s'eliminaran permanentment. Abans d'esborrar el compte, si us plau, descarrega qualsevol dada o informació que vulguis conservar.") }}
                    </p>

                    <div class="mt-6">
                        <button 
                            @click="confirmUserDeletion" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold text-sm transition shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                        >
                            {{ $t('Esborrar Compte') }}
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <div v-if="confirmingUserDeletion" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/75 backdrop-blur-sm p-4 transition-opacity">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full p-6 border border-gray-200 dark:border-gray-700 transform transition-all scale-100">
                
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $t('Estàs segur que vols esborrar el teu compte?') }}
                </h2>

                <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                    {{ $t("Un cop esborrat el compte, tots els seus recursos i dades s'eliminaran permanentment. Si us plau, introdueix la teva contrasenya per confirmar que vols esborrar permanentment el teu compte.") }}
                </p>

                <div class="mt-6">
                    <label for="password" class="sr-only">{{ $t('Contrasenya') }}</label>

                    <input
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                        :placeholder="$t('Contrasenya')"
                        @keyup.enter="deleteUser"
                    />

                    <div v-if="form.errors.password" class="text-red-600 text-sm mt-2">
                        {{ form.errors.password }}
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button 
                        @click="closeModal" 
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg text-sm font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition"
                    >
                        {{ $t('Cancel·lar') }}
                    </button>

                    <button 
                        @click="deleteUser" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-bold shadow-lg transition disabled:opacity-50"
                        :disabled="form.processing"
                    >
                        {{ $t('Esborrar Compte') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>