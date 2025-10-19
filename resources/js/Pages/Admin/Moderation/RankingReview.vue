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
  <div class="p-6 max-w-5xl mx-auto min-h-screen bg-gray-50 dark:bg-[#0d0d0d] transition-colors duration-500">
    <button
      @click="goBack"
      class="mb-6 text-sm px-3 py-1.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-md text-gray-700 dark:text-gray-100 transition"
    >
      ⬅️ Tornar a la moderació
    </button>

    <h1 class="text-3xl font-bold mb-2 text-gray-900 dark:text-gray-100">{{ ranking.title }}</h1>
    <p class="text-gray-700 dark:text-gray-300 mb-1">
      Creat per: {{ ranking.user?.name || 'Desconegut' }}
    </p>
    <p class="text-gray-600 dark:text-gray-400 mb-6">{{ ranking.description }}</p>

    <div class="flex gap-3 mb-8">
      <button
        @click="approveRanking"
        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition disabled:opacity-50"
        :disabled="isLoading"
      >
        ✅ Aprovar rànquing
      </button>

      <button
        @click="rejectRanking"
        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition disabled:opacity-50"
        :disabled="isLoading"
      >
        ❌ Eliminar rànquing
      </button>
    </div>

    <div v-if="ranking.image" class="flex justify-center mb-6">
      <img
        :src="`/storage/${ranking.image}`"
        alt="Imatge principal"
        class="max-h-96 w-auto rounded-xl shadow-lg"
      />
    </div>

    <h2 class="text-xl font-semibold mb-3 text-gray-900 dark:text-gray-100">Opcions del rànquing</h2>

    <div
      v-if="ranking.options?.length"
      class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
    >
      <div
        v-for="opt in ranking.options"
        :key="opt.id"
        class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden bg-white dark:bg-[#1a1a1a] shadow-sm hover:shadow-md transition"
      >
        <img
          v-if="opt.image"
          :src="`/storage/${opt.image}`"
          alt="Imatge opció"
          class="object-cover w-full h-40"
        />
        <div class="p-3">
          <p class="font-medium text-gray-800 dark:text-gray-200 text-center">{{ opt.name }}</p>
        </div>
      </div>
    </div>

    <p v-else class="text-gray-500 dark:text-gray-400 italic">
      Aquest rànquing no té opcions.
    </p>
  </div>
</template>
