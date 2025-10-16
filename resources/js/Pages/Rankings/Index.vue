<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import ConfirmModal from '@/Components/ConfirmModal.vue'
import FavoriteStar from '@/Components/FavoriteStar.vue'

defineProps({
  rankings: Array,
  filters: Object,
})

const page = usePage()
const flash = page.props.flash || {}

const search = ref(page.props.filters?.search || '')

// Executa la cerca només quan es prem enter o el botó
function applySearch() {
  router.get(
    '/rankings',
    { search: search.value },
    { preserveState: true, replace: true }
  )
}
</script>

<template>
  <AppLayout>
    <div class="max-w-4xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-4">Rankings</h1>

      <!-- Missatges flash -->
      <div v-if="flash.success" class="mb-4 p-4 bg-green-100 text-green-800 rounded">
        {{ flash.success }}
      </div>
      <div v-if="flash.error" class="mb-4 p-4 bg-red-100 text-red-800 rounded">
        {{ flash.error }}
      </div>

      <!-- Buscador -->
      <div class="flex mb-6">
        <input
          v-model="search"
          @keyup.enter="applySearch"
          type="text"
          placeholder="Cerca rànquings o opcions..."
          class="w-full px-4 py-2 border rounded-l focus:outline-none focus:ring focus:border-indigo-400"
        />
        <button
          @click="applySearch"
          class="px-4 py-2 bg-indigo-600 text-white rounded-r hover:bg-indigo-700"
        >
          🔍
        </button>
      </div>

      <Link
        href="/rankings/create"
        class="mb-4 inline-block rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700"
      >
        + Crear nou rànquing
      </Link>

      <div v-if="!rankings || rankings.length === 0" class="text-gray-600 mt-4">
        No s’han trobat rànquings.
      </div>

      <ul v-else class="space-y-4">
        <li
          v-for="ranking in rankings"
          :key="ranking.id"
          class="relative flex flex-col sm:flex-row gap-4 items-start sm:items-center rounded border p-4 shadow hover:shadow-md transition"
        >
          <!-- Imatge principal -->
          <div class="w-full sm:w-40 h-40 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100 flex items-center justify-center">
            <img
              v-if="ranking.image"
              :src="`/storage/${ranking.image}`"
              alt="Imatge del rànquing"
              class="w-full h-48 object-cover rounded-lg border"
            />
            <div
              v-else
              class="w-full h-48 flex items-center justify-center bg-gray-100 rounded-lg text-4xl"
            >
              🖼️
            </div>
          </div>

          <!-- Contingut -->
          <div class="flex-1 w-full">
            <FavoriteStar :ranking="ranking" class="absolute top-3 right-3" />

            <h2 class="text-lg font-semibold">{{ ranking.title }}</h2>
            <p class="text-gray-700">{{ ranking.description }}</p>

            <ul class="mt-2">
              <li v-for="opt in ranking.options" :key="opt.id" class="text-sm">
                - {{ opt.name }}
              </li>
            </ul>

            <div class="mt-3 flex items-center space-x-4">
              <Link
                :href="`/rankings/${ranking.id}`"
                class="text-indigo-600 hover:underline"
              >
                Veure detalls →
              </Link>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </AppLayout>
</template>
