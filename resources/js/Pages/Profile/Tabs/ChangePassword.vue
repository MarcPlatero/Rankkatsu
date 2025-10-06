<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const emit = defineEmits(['password-updated'])
const isOpen = ref(false)

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.patch(route('profile.password.update'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      isOpen.value = false
      emit('password-updated')
    },
  })
}
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
  <div class="mt-6 border-t pt-6">
    <button
      @click="isOpen = !isOpen"
      class="flex items-center justify-between w-full text-left text-lg font-semibold text-gray-800 hover:text-blue-600 transition"
    >
      <span>🔐 Canviar contrasenya</span>
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
        <form @submit.prevent="submit" class="bg-gray-50 border rounded-lg p-4 space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contrasenya actual</label>
            <input
              v-model="form.current_password"
              type="password"
              class="w-full border rounded-md p-2 focus:ring-blue-500 focus:border-blue-500"
              required
            />
            <div v-if="form.errors.current_password" class="text-red-500 text-sm mt-1">
              {{ form.errors.current_password }}
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nova contrasenya</label>
            <input
              v-model="form.password"
              type="password"
              class="w-full border rounded-md p-2 focus:ring-blue-500 focus:border-blue-500"
              required
            />
            <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">
              {{ form.errors.password }}
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirma la nova contrasenya</label>
            <input
              v-model="form.password_confirmation"
              type="password"
              class="w-full border rounded-md p-2 focus:ring-blue-500 focus:border-blue-500"
              required
            />
          </div>

          <div class="pt-2 flex justify-end">
            <button
              type="submit"
              class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition disabled:opacity-50"
              :disabled="form.processing"
            >
              {{ form.processing ? 'Guardant...' : '💾 Actualitzar contrasenya' }}
            </button>
          </div>
        </form>
      </div>
    </transition>
  </div>
</template>
