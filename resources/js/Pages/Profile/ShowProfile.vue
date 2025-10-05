<script setup>
import { Head, useForm, usePage, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { computed } from 'vue'

const props = defineProps({
  user: Object,
})

const page = usePage()
const urlParams = new URLSearchParams(window.location.search)
const updated = urlParams.has('updated')
const flash = computed(() => page.props.flash)

const form = useForm({
  name: props.user.name,
  email: props.user.email,
})

const submit = () => {
  form.patch(route('profile.update'), {
    preserveScroll: true,
    onSuccess: () => {
    },
    onError: () => {
      console.error('Error actualitzant el perfil:', form.errors)
    }
  })
}

const logout = () => {
  router.post(route('logout'))
}
</script>

<template>
  <AppLayout>
    <Head title="Perfil d'usuari" />

    <div class="max-w-2xl mx-auto py-10 px-6">
      <h1 class="text-3xl font-bold mb-6 text-center">👤 El meu perfil</h1>

      <div v-if="flash.success" class="mb-4 p-3 bg-green-100 text-green-700 rounded">
        {{ flash.success }}
      </div>

      <!-- Dades del compte -->
      <div class="bg-white shadow rounded-lg p-6 space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Nom d'usuari</label>
          <input
            v-model="form.name"
            type="text"
            class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
          />
          <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</div>
        </div>

        <input
          v-model="form.email"
          type="text"
          disabled
          class="mt-1 w-full bg-gray-100 ..."
        />
        <div v-if="form.errors.email" class="text-red-600 text-sm mt-1">{{ form.errors.email }}</div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Compte creat el</label>
          <p class="mt-1 text-gray-800">
            {{ new Date(user.created_at).toLocaleDateString() }}
          </p>
        </div>

        <div class="pt-4 flex justify-between">
          <button
            @click="submit"
            :disabled="form.processing"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition disabled:opacity-50"
          >
            {{ form.processing ? 'Guardant...' : '💾 Guardar canvis' }}
          </button>

          <button
            @click="logout"
            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition flex items-center gap-2"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1" />
            </svg>
            Tancar sessió
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
