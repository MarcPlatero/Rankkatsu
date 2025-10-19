<script setup>
import { ref } from 'vue'
import { Head, usePage, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AccountInfo from './Tabs/AccountInfo.vue'
import FavoriteRankings from './Tabs/FavoriteRankings.vue'
import YourRankings from './Tabs/YourRankings.vue'

const props = defineProps({
  user: Object,
  rankings: Array,
  favorites: Array,
})

const page = usePage()
const currentTab = ref('account')

const logout = () => {
  router.post(route('logout'))
}
</script>

<template>
  <AppLayout>
    <Head title="Perfil d'usuari" />

    <div class="max-w-3xl mx-auto py-10 px-6 text-gray-800 dark:text-gray-100">
      <h1 class="text-3xl font-bold mb-6 text-center">El meu perfil</h1>

      <!-- Targeta principal -->
      <div
        class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-8 text-center transition-colors duration-300"
      >
        <h2 class="text-xl font-semibold mb-2">{{ user.name }}</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-4">
          Compte creat el {{ new Date(user.created_at).toLocaleDateString() }}
        </p>
        <button
          @click="logout"
          class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition"
        >
          Tancar sessió
        </button>
      </div>

      <!-- Pestanyes -->
      <div
        class="flex border-b mb-6 bg-gray-50 dark:bg-gray-800 dark:border-gray-700 rounded-lg shadow-sm overflow-hidden transition-colors duration-300"
      >
        <button
          class="flex-1 py-3 font-medium text-center transition border-b-2 bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800"
          :class="currentTab === 'yours'
            ? 'border-blue-500 text-blue-600 dark:text-blue-400'
            : 'border-transparent hover:text-blue-500 dark:hover:text-blue-400'"
          @click="currentTab = 'yours'"
        >
          🏆 Els teus rankings
        </button>

        <button
          class="flex-1 py-3 font-medium text-center transition border-b-2 bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800"
          :class="currentTab === 'account'
            ? 'border-blue-500 text-blue-600 dark:text-blue-400'
            : 'border-transparent hover:text-blue-500 dark:hover:text-blue-400'"
          @click="currentTab = 'account'"
        >
          🧑 Informació del compte
        </button>

        <button
          class="flex-1 py-3 font-medium text-center transition border-b-2 bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800"
          :class="currentTab === 'favorites'
            ? 'border-blue-500 text-blue-600 dark:text-blue-400'
            : 'border-transparent hover:text-blue-500 dark:hover:text-blue-400'"
          @click="currentTab = 'favorites'"
        >
          ⭐ Rankings favorits
        </button>
      </div>

      <!-- Contingut segons pestanya -->
      <div v-if="currentTab === 'account'">
        <AccountInfo :user="user" />
      </div>

      <div v-else-if="currentTab === 'favorites'">
        <FavoriteRankings :favorites="favorites" />
      </div>

      <div v-else-if="currentTab === 'yours'">
        <YourRankings :rankings="rankings" />
      </div>
    </div>
  </AppLayout>
</template>
