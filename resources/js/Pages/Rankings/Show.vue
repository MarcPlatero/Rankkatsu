<script setup>
import { Head, router, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
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

// Confirmació i eliminació del rànquing
const confirmDelete = () => {
  if (confirm("⚠️ Estàs segur que vols eliminar aquest rànquing? Aquesta acció no es podrà desfer.")) {
    router.delete(`/rankings/${props.ranking.id}`)
  }
}

// Comentaris: formulari (només per a autenticats)
import { useForm } from '@inertiajs/vue3'
const commentForm = useForm({
  content: '',
})

// enviar comentari
const submitComment = () => {
  commentForm.post(route('rankings.comments.store', props.ranking.id), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      commentForm.reset('content')
    }
  })
}

// eliminar comentari (demana confirmació)
const deleteComment = (commentId) => {
  if (! confirm('Estàs segur que vols eliminar aquest comentari?')) return

  router.delete(route('rankings.comments.destroy', { ranking: props.ranking.id, comment: commentId }), {
    preserveScroll: true
  })
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

      <!-- Botó d'eliminar (només si és el creador i està logejat) -->
      <div v-if="page.props.auth?.user && ranking.user_id === page.props.auth.user.id" class="mb-6">
        <button
          @click="confirmDelete"
          class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
        >
          🗑️ Eliminar rànquing
        </button>
      </div>

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
          <button
            v-if="!userVote || userVote.ranking_option_id !== opt.id"
            @click="vote(opt.id)"
            class="mt-3 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
          >
            Votar
          </button>
          <span
            v-else
            class="mt-3 inline-block px-4 py-2 bg-green-600 text-white rounded"
          >
            ✅ Ja has votat
          </span>
        </div>
      </div>

        <!-- Form de comentar (només si usuari autenticat) -->
        <div v-if="$page.props.auth?.user" class="mt-8 mb-4">
          <form @submit.prevent="submitComment" class="space-y-2">
            <textarea
              v-model="commentForm.content"
              placeholder="Escriu el teu comentari..."
              class="w-full border rounded p-2"
              rows="3"
            ></textarea>
            <div class="flex items-center justify-between">
              <div class="text-sm text-gray-500">Máx. 1000 caràcters</div>
              <button :disabled="commentForm.processing || !commentForm.content" type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                Publicar
              </button>
            </div>
            <div v-if="commentForm.errors.content" class="text-red-600 text-sm">{{ commentForm.errors.content }}</div>
          </form>
        </div>

        <!-- Si no autenticat, enllaç a login -->
        <div v-else class="mb-4 text-sm">
          <a href="/login" class="text-blue-600 underline">Inicia sessió</a> per publicar comentaris.
        </div>

        <!-- Llista de comentaris -->
        <div v-if="ranking.comments && ranking.comments.length > 0" class="space-y-4">
         <div v-for="comment in ranking.comments" :key="comment.id" class="p-4 bg-white border rounded">
           <div class="flex items-start justify-between">
            <div>
              <div class="text-sm font-semibold">{{ comment.user?.name || 'Usuari' }}</div>
              <div class="text-xs text-gray-500">{{ new Date(comment.created_at).toLocaleString() }}</div>
            </div>

            <!-- boto eliminar visible només per autor del comentari o creador del ranking -->
            <div v-if="$page.props.auth?.user && ($page.props.auth.user.id === comment.user_id || $page.props.auth.user.id === ranking.user_id)">
              <button @click="deleteComment(comment.id)" class="text-red-600 hover:underline text-sm">Eliminar</button>
            </div>
          </div>

          <div class="mt-2 text-gray-700 whitespace-pre-line">{{ comment.content }}</div>
        </div>
      </div>

      <div v-else class="text-gray-600">Encara no hi ha comentaris. Sigues el primer!</div>
    </div>
  </AppLayout>
</template>
