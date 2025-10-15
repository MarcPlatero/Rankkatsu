<script setup>
import { Link, router } from '@inertiajs/vue3'

defineProps({
  rankings: Array,
  success: String
})

function approveRanking(id) {
  router.post(`/admin/moderation/${id}/approve`)
}

function rejectRanking(id) {
  if (confirm('Segur que vols eliminar aquest rànquing?')) {
    router.post(`/admin/moderation/${id}/reject`)
  }
}
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Moderació de rànquings</h1>

    <p v-if="success" class="text-green-600 mb-4">{{ success }}</p>

    <div v-if="rankings.length === 0" class="text-gray-500">
      No hi ha rànquings pendents de moderació 🎉
    </div>

    <div v-else class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
      <div
        v-for="ranking in rankings"
        :key="ranking.id"
        class="border rounded-lg p-4 shadow-sm bg-white flex flex-col"
      >
        <!-- Miniatura -->
        <img
          v-if="ranking.image"
          :src="`/storage/${ranking.image}`"
          alt="Imatge principal"
          class="w-full h-40 object-cover rounded mb-3"
        />
        <div class="flex-1">
          <h2 class="text-lg font-semibold mb-1">{{ ranking.title }}</h2>
          <p class="text-sm text-gray-600 mb-3">
            Creat per: {{ ranking.user?.name || 'Desconegut' }}
          </p>
        </div>

        <div class="flex justify-between items-center mt-auto pt-2 border-t">
          <Link
            :href="`/admin/moderation/${ranking.id}`"
            class="text-blue-600 hover:underline"
          >
            Veure detalls
          </Link>

          <div class="flex gap-2">
            <button
              @click="approveRanking(ranking.id)"
              class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700"
            >
              Aprovar
            </button>
            <button
              @click="rejectRanking(ranking.id)"
              class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700"
            >
              Rebutjar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
