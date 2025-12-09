<script setup>
import { ref } from 'vue'
import { Head, usePage, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AccountInfo from './Tabs/AccountInfo.vue'
import FavoriteRankings from './Tabs/FavoriteRankings.vue'
import YourRankings from './Tabs/YourRankings.vue'
import AdBanner from '@/Components/AdBanner.vue'

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

    <div class="max-w-5xl mx-auto py-10 px-6 text-gray-800 dark:text-gray-100">
      <h1 class="text-3xl font-bold mb-6 text-center">El meu perfil</h1>

      <div
        class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 mb-8 flex flex-col sm:flex-row items-center sm:space-x-6 transition-colors duration-300"
      >
        <div class="flex-shrink-0 mb-4 sm:mb-0">
          <img v-if="user.profile_photo_url" :src="user.profile_photo_url" alt="Avatar" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 dark:border-gray-700 shadow-md">
          <div v-else class="w-24 h-24 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center border-4 border-gray-200 dark:border-gray-700 shadow-md">
            <svg class="w-12 h-12 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
          </div>
        </div>

        <div class="flex-1 text-center sm:text-left">
          <h2 class="text-2xl font-bold mb-1">{{ user.name }}</h2>
          <p class="text-gray-600 dark:text-gray-400 mb-4">
            Compte creat el {{ new Date(user.created_at).toLocaleDateString() }}
          </p>
          <button
            @click="logout"
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow"
          >
            Tancar sessió
          </button>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md text-center">
          <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ rankings.length }}</div>
          <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Rankings Creats</div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md text-center">
          <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ favorites.length }}</div>
          <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Favorits</div>
        </div>
      </div>

      <AdBanner format="horizontal" />

      <div
        class="flex border-b mb-6 bg-gray-50 dark:bg-gray-800 dark:border-gray-700 rounded-lg shadow-sm overflow-hidden transition-colors duration-300"
      >
        <button
          class="flex-1 py-3 font-medium text-center transition border-b-4 hover:bg-gray-100 dark:hover:bg-gray-700"
          :class="currentTab === 'yours'
            ? 'border-blue-600 dark:border-red-500 text-blue-600 dark:text-red-400'
            : 'border-transparent text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-red-400'"
          @click="currentTab = 'yours'"
        >
          🏆 Els teus rankings
        </button>

        <button
          class="flex-1 py-3 font-medium text-center transition border-b-4 hover:bg-gray-100 dark:hover:bg-gray-700"
          :class="currentTab === 'account'
            ? 'border-blue-600 dark:border-red-500 text-blue-600 dark:text-red-400'
            : 'border-transparent text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-red-400'"
          @click="currentTab = 'account'"
        >
          🧑 Informació del compte
        </button>

        <button
          class="flex-1 py-3 font-medium text-center transition border-b-4 hover:bg-gray-100 dark:hover:bg-gray-700"
          :class="currentTab === 'favorites'
            ? 'border-blue-600 dark:border-red-500 text-blue-600 dark:text-red-400'
            : 'border-transparent text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-red-400'"
          @click="currentTab = 'favorites'"
        >
          ⭐ Rankings favorits
        </button>
      </div>

      <Transition name="tab-fade" mode="out-in">
        <div v-if="currentTab === 'account'" key="account">
          <AccountInfo :user="user" />
        </div>

        <div v-else-if="currentTab === 'favorites'" key="favorites">
          <FavoriteRankings :favorites="favorites" />
        </div>

        <div v-else-if="currentTab === 'yours'" key="yours">
          <YourRankings :rankings="rankings" />
        </div>
      </Transition>
    </div>
  </AppLayout>
</template>
