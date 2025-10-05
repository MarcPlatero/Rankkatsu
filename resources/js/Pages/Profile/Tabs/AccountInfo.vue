<script setup>
import { useForm } from '@inertiajs/vue3'
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import ChangePassword from './ChangePassword.vue'

const props = defineProps({
  user: Object,
})

const page = usePage()
const flash = computed(() => page.props.flash)

const form = useForm({
  name: props.user.name,
  email: props.user.email,
})

const submit = () => {
  form.patch(route('profile.update'), {
    preserveScroll: true,
  })
}
</script>

<template>
  <div class="bg-white shadow rounded-lg p-6 space-y-4">
    <h2 class="text-xl font-semibold mb-4">Informació del compte</h2>

    <div v-if="flash.success" class="p-3 bg-green-100 text-green-700 rounded">
      {{ flash.success }}
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Nom d'usuari</label>
      <input
        v-model="form.name"
        type="text"
        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
      />
      <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</div>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Correu electrònic</label>
      <input
        v-model="form.email"
        type="email"
        disabled
        class="mt-1 w-full bg-gray-100 border-gray-300 rounded-md shadow-sm text-gray-600"
      />
    </div>

    <div class="pt-4">
      <button
        @click="submit"
        :disabled="form.processing"
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition disabled:opacity-50"
      >
        {{ form.processing ? 'Guardant...' : '💾 Guardar canvis' }}
      </button>
    </div>

    <ChangePassword />
  </div>
</template>
