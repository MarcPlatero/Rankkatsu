<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import ConfirmModal from '@/Components/ConfirmModal.vue'
import FavoriteStar from '@/Components/FavoriteStar.vue'

defineProps({ rankings: Array })

const page = usePage()
const flash = page.props.flash || {}

const showModal = ref(false)
const rankingToDelete = ref(null)

function askDelete(rankingId) {
  rankingToDelete.value = rankingId
  showModal.value = true
}

function confirmDelete() {
  if (rankingToDelete.value) {
    router.delete(`/rankings/${rankingToDelete.value}`, {
      preserveScroll: true,
      onSuccess: () => {
        showModal.value = false
        rankingToDelete.value = null
      },
      onError: () => {
        showModal.value = false
      },
    })
  }
}
</script>

<template>
  <AppLayout>
    <div class="max-w-3xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-4">Rankings</h1>

      <!-- Flash messages -->
      <div v-if="flash.success" class="mb-4 p-4 bg-green-100 text-green-800 rounded">
        {{ flash.success }}
      </div>
      <div v-if="flash.error" class="mb-4 p-4 bg-red-100 text-red-800 rounded">
        {{ flash.error }}
      </div>

      <Link
        href="/rankings/create"
        class="mb-4 inline-block rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700"
      >
        + Crear nou rànquing
      </Link>

      <div v-if="rankings.length === 0" class="text-gray-600">
        Encara no hi ha rànquings.
      </div>

      <ul>
        <li
          v-for="ranking in rankings"
          :key="ranking.id"
          class="relative mb-3 rounded border p-4 shadow hover:shadow-md transition"
        >
          <!-- Estrella de Favorit -->
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

            <!-- Botó eliminar només si és el creador -->
            <button
              v-if="$page.props.auth?.user && ranking.user_id === $page.props.auth.user.id"
              @click="askDelete(ranking.id)"
              class="text-red-600 hover:underline"
            >
              🗑️ Eliminar
            </button>
          </div>
        </li>
      </ul>
    </div>

    <!-- Modal confirmació -->
    <ConfirmModal
      :show="showModal"
      title="Eliminar rànquing"
      message="Estàs segur que vols eliminar aquest rànquing? Aquesta acció és irreversible."
      @cancel="showModal = false"
      @confirm="confirmDelete"
    />
  </AppLayout>
</template>
