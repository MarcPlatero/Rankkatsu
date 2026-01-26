<script setup>
import { Link, router } from '@inertiajs/vue3'
import { trans } from 'laravel-vue-i18n';

defineProps({
  rankings: Array,
  success: String
})

function approveRanking(id) {
  router.post(`/admin/moderation/${id}/approve`)
}

function rejectRanking(id) {
  if (confirm(trans('Segur que vols eliminar aquest rànquing?'))) {
    router.post(`/admin/moderation/${id}/reject`)
  }
}
</script>

<template>
  <div class="p-6 min-h-screen bg-gray-50 dark:bg-[#0d0d0d] transition-colors duration-500">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $t('Moderació de rànquings') }}</h1>
      <Link
        href="/"
        class="text-sm px-3 py-1.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-md text-gray-700 dark:text-gray-100 transition"
      >
        ⬅️ {{ $t("Tornar a l'inici") }}
      </Link>
    </div>

    <p v-if="success" class="text-green-600 dark:text-green-400 mb-4 font-medium">
      {{ success }}
    </p>

    <div v-if="rankings.length === 0" class="text-gray-600 dark:text-gray-400">
      {{ $t('No hi ha rànquings pendents de moderació.') }}
    </div>

    <div
      v-else
      class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
    >
      <div
        v-for="ranking in rankings"
        :key="ranking.id"
        class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 shadow-sm bg-white dark:bg-[#1a1a1a] flex flex-col hover:shadow-lg transition-all duration-300 hover:-translate-y-1"
      >
        <img
          v-if="ranking.image"
          :src="`/storage/${ranking.image}`"
          :alt="$t('Imatge principal')"
          class="w-full h-40 object-cover rounded-lg mb-3"
        />
        <div class="flex-1">
          <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-1">
            {{ ranking.title }}
          </h2>
          <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
            {{ $t('Creat per:') }} {{ ranking.user?.name || $t('Desconegut') }}
          </p>
        </div>

        <div class="flex justify-between items-center mt-auto pt-3 border-t border-gray-200 dark:border-gray-700">
          <Link
            :href="`/admin/moderation/${ranking.id}`"
            class="text-blue-600 dark:text-blue-400 hover:underline"
          >
            {{ $t('Veure detalls') }}
          </Link>

          <div class="flex gap-2">
            <button
              @click="approveRanking(ranking.id)"
              class="px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 transition"
            >
              {{ $t('Aprovar') }}
            </button>
            <button
              @click="rejectRanking(ranking.id)"
              class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 transition"
            >
              {{ $t('Rebutjar') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>