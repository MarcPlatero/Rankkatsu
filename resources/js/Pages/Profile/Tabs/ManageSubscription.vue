<script setup>
import { ref } from 'vue'
import { trans } from 'laravel-vue-i18n';

const isOpen = ref(false)
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
      class="flex items-center justify-between w-full text-left text-lg font-semibold text-gray-800 dark:text-gray-100 hover:text-blue-600 dark:hover:text-red-400 transition"
    >
      <span class="flex items-center gap-2">👑 {{ $t('Gestió de Subscripció Premium') }}</span>
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
        <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 shadow-sm transition">
            
            <div class="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 p-4 rounded-lg font-bold text-sm border border-green-200 dark:border-green-800 mb-4 flex items-center gap-2">
                <span>✅</span>
                <span>{{ $t('Tens una subscripció Premium activa. Gràcies pel teu suport!') }}</span>
            </div>

            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                {{ $t("Des d'aquí pots veure les teves factures, actualitzar el mètode de pagament o cancel·lar la teva subscripció.") }}
            </p>

            <div class="flex justify-end">
                <a 
                    :href="route('premium.manage')"
                    class="bg-blue-600 hover:bg-blue-700 dark:bg-red-600 dark:hover:bg-red-700 text-white px-4 py-2 rounded-lg transition shadow-sm inline-flex items-center justify-center text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-red-500 cursor-pointer"
                >
                    ⚙️ {{ $t('Gestionar a Stripe') }}
                </a>
            </div>
            
            <p class="text-xs text-gray-400 mt-3 text-right italic">* {{ $t('Si tens un pla Lifetime, no cal gestionar res.') }}</p>
        </div>
      </div>
    </transition>
  </div>
</template>