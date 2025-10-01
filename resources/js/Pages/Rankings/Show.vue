<script setup>
import { Head, router, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({ ranking: Object, userVote: Object })

const page = usePage()

// Flash messages
const flash = computed(() => page.props.flash)

// Total de vots
const totalVotes = computed(() => {
  return props.ranking.options.reduce((sum, opt) => sum + opt.votes_count, 0)
})

// Percentatge per cada opció
const getPercentage = (votes) => {
  if (totalVotes.value === 0) return 0
  return Math.round((votes / totalVotes.value) * 100)
}

const vote = (optionId) => {
  router.post(`/rankings/${props.ranking.id}/vote`, { option_id: optionId })
}
</script>

<template>
  <AppLayout>
    <Head :title="ranking.title" />

    <div class="max-w-3xl mx-auto py-10 px-6">
      <!-- Alertes flash -->
      <div v-if="flash.success" class="mb-4 p-4 bg-green-100 text-green-800 rounded">
        {{ flash.success }}
      </div>
      <div v-if="flash.error" class="mb-4 p-4 bg-red-100 text-red-800 rounded">
        {{ flash.error }}
      </div>
      
      <!-- Imatge -->
      <img
        v-if="ranking.image"
        :src="`/storage/${ranking.image}`"
        alt="Ranking image"
        class="w-full h-64 object-cover rounded-lg mb-6"
      />

      <!-- Títol i descripció -->
      <h1 class="text-3xl font-bold mb-2">{{ ranking.title }}</h1>
      <p class="text-gray-600 mb-6">{{ ranking.description }}</p>

      <!-- Opcions amb resultats -->
      <div class="space-y-4">
        <div
          v-for="opt in ranking.options"
          :key="opt.id"
          class="p-4 bg-white shadow rounded-lg"
        >
          <!-- Nom + imatge -->
          <div class="flex items-center gap-4 mb-2">
            <img
              v-if="opt.image"
              :src="`/storage/${opt.image}`"
              alt="Option"
              class="w-16 h-16 object-cover rounded"
            />
            <span class="font-medium">{{ opt.name }}</span>
          </div>

          <!-- Barra de percentatge -->
          <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
            <div
              class="bg-blue-600 h-4"
              :style="{ width: getPercentage(opt.votes_count) + '%' }"
            ></div>
          </div>

          <!-- Text amb percentatge -->
          <div class="flex justify-between text-sm text-gray-600 mt-1">
            <span>{{ opt.votes_count }} vots</span>
            <span>{{ getPercentage(opt.votes_count) }}%</span>
          </div>

          <!-- Botó de votar -->
          <div class="mt-3">
            <button
              v-if="!userVote"
              @click="vote(opt.id)"
              class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
              Votar
            </button>
            <span
              v-else-if="userVote.ranking_option_id === opt.id"
              class="inline-block px-4 py-2 bg-green-600 text-white rounded"
            >
              ✅ Ja has votat
            </span>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
