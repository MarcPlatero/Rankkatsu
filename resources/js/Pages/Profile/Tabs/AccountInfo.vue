<script setup>
import { ref, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import ChangePassword from './ChangePassword.vue'

const props = defineProps({
  user: Object,
})

const page = usePage()
const successMessage = ref('')

const form = useForm({
  name: props.user.name,
  email: props.user.email,
})

const submit = () => {
  form.patch(route('profile.update'), {
    preserveScroll: true,
    onSuccess: () => {
      successMessage.value = '✅ Canvis guardats correctament.'
      setTimeout(() => (successMessage.value = ''), 3000)
    },
  })
}

const handlePasswordUpdated = () => {
  successMessage.value = '🔐 Contrasenya actualitzada correctament.'
  setTimeout(() => (successMessage.value = ''), 3000)
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>

<template>
  <div
    class="bg-white dark:bg-gray-900 shadow-md rounded-xl p-6 space-y-4 border border-gray-100 dark:border-gray-800 transition-colors duration-300"
  >
    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">👤 Informació del compte</h2>

    <transition name="fade">
      <div
        v-if="successMessage"
        class="p-3 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded text-sm border border-green-200 dark:border-green-800"
      >
        {{ successMessage }}
      </div>
    </transition>

    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom d'usuari</label>
      <input
        v-model="form.name"
        type="text"
        class="mt-1 w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 transition"
      />
      <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</div>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correu electrònic</label>
      <input
        v-model="form.email"
        type="email"
        disabled
        class="mt-1 w-full bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-gray-600 dark:text-gray-400 cursor-not-allowed"
      />
    </div>

    <div class="pt-4 flex justify-end">
      <button
        @click="submit"
        :disabled="form.processing"
        class="px-4 py-2 rounded-lg font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition disabled:opacity-50 shadow-sm"
      >
        {{ form.processing ? 'Guardant...' : '💾 Guardar canvis' }}
      </button>
    </div>

    <ChangePassword @password-updated="handlePasswordUpdated" />
  </div>
</template>
