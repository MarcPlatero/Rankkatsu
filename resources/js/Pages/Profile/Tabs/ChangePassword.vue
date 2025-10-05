<template>
  <div class="mt-6 border-t pt-6">
    <h3 class="text-lg font-semibold mb-4">Canviar contrasenya</h3>

    <form @submit.prevent="submit">
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Contrasenya actual</label>
        <input
          v-model="form.current_password"
          type="password"
          class="w-full border rounded-md p-2"
          required
        />
        <div v-if="form.errors.current_password" class="text-red-500 text-sm mt-1">
          {{ form.errors.current_password }}
        </div>
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Nova contrasenya</label>
        <input
          v-model="form.password"
          type="password"
          class="w-full border rounded-md p-2"
          required
        />
        <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">
          {{ form.errors.password }}
        </div>
      </div>

      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-1">Confirma la nova contrasenya</label>
        <input
          v-model="form.password_confirmation"
          type="password"
          class="w-full border rounded-md p-2"
          required
        />
      </div>

      <button
        type="submit"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition disabled:opacity-50"
        :disabled="form.processing"
      >
        {{ form.processing ? 'Guardant...' : '💾 Actualitzar contrasenya' }}
      </button>

      <p v-if="successMessage" class="text-green-600 mt-4">
        {{ successMessage }}
      </p>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
})

const successMessage = ref('')

const submit = () => {
  form.patch(route('profile.password.update'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      successMessage.value = 'Contrasenya actualitzada correctament'
      setTimeout(() => (successMessage.value = ''), 3000)
    },
  })
}
</script>
