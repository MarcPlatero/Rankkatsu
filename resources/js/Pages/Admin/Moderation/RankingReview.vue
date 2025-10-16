<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  ranking: Object
})

const isLoading = ref(false)

function approveRanking() {
  if (isLoading.value) return
  isLoading.value = true
  router.post(`/admin/moderation/${props.ranking.id}/approve`, {}, {
    onFinish: () => isLoading.value = false
  })
}

function rejectRanking() {
  if (isLoading.value) return
  if (confirm('Segur que vols eliminar aquest rànquing?')) {
    isLoading.value = true
    router.post(`/admin/moderation/${props.ranking.id}/reject`, {}, {
      onSuccess: () => {
        router.visit('/admin/moderation', { 
          replace: true, 
          preserveScroll: true 
        })
      },
      onFinish: () => isLoading.value = false
    })
  }
}

function goBack() {
  router.visit('/admin/moderation', { replace: true })
}
</script>

<template>
  <div class="p-6 max-w-5xl mx-auto">
    <!-- Botó per tornar enrere -->
    <button
      @click="goBack"
      class="mb-6 text-sm px-3 py-1.5 bg-gray-200 hover:bg-gray-300 rounded-md text-gray-700"
    >
      ⬅️ Tornar a la moderació
    </button>

    <!-- Títol i info -->
    <h1 class="text-2xl font-bold mb-2">{{ ranking.title }}</h1>
    <p class="text-gray-700 mb-1">Creat per: {{ ranking.user?.name || 'Desconegut' }}</p>
    <p class="text-gray-600 mb-6">{{ ranking.description }}</p>

    <!-- Botons de moderació -->
    <div class="flex gap-3 mb-8">
      <button
        @click="approveRanking"
        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 disabled:opacity-50"
        :disabled="isLoading"
      >
        ✅ Aprovar rànquing
      </button>

      <button
        @click="rejectRanking"
        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 disabled:opacity-50"
        :disabled="isLoading"
      >
        ❌ Eliminar rànquing
      </button>
    </div>

    <!-- Imatge principal -->
    <div v-if="ranking.image" class="flex justify-center mb-6">
      <img
        :src="`/storage/${ranking.image}`"
        alt="Imatge principal"
        class="max-h-80 w-auto rounded-lg shadow"
      />
    </div>

    <!-- Opcions -->
    <h2 class="text-xl font-semibold mb-3">Opcions del rànquing</h2>

    <div
      v-if="ranking.options?.length"
      class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
    >
      <div
        v-for="opt in ranking.options"
        :key="opt.id"
        class="border rounded-lg overflow-hidden bg-white shadow-sm"
      >
        <img
          v-if="opt.image"
          :src="`/storage/${opt.image}`"
          alt="Imatge opció"
          class="object-cover w-full h-40"
        />
        <div class="p-3">
          <p class="font-medium text-gray-800 text-center">{{ opt.name }}</p>
        </div>
      </div>
    </div>

    <p v-else class="text-gray-500 italic">Aquest rànquing no té opcions.</p>
  </div>
</template>
