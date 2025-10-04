<script setup>
import { Head, router, usePage } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  ranking: Object,
  userVote: Object,
  comments: Array,
  sort: { type: String, default: 'likes' } // ✅ per defecte "Més likes"
});

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

// Enviar comentari
const submitComment = () => {
  commentForm.post(route('rankings.comments.store', props.ranking.id), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      commentForm.reset('content')
    }
  })
}

// Eliminar comentari
const deleteComment = (commentId) => {
  if (! confirm('Estàs segur que vols eliminar aquest comentari?')) return
  router.delete(route('rankings.comments.destroy', { ranking: props.ranking.id, comment: commentId }), {
    preserveScroll: true
  })
}

// Votar comentari
const voteComment = (commentId, isLike) => {
  router.post(`/comments/${commentId}/vote`, { is_like: isLike }, { preserveScroll: true })
}

// Estat local del filtre
const sort = ref(props.sort || 'likes') // ✅ inicial a "likes"

// Quan canvies el select → reload
watch(sort, (newSort) => {
  router.get(
    route('rankings.show', props.ranking.id),
    { sort: newSort },
    { preserveScroll: true, preserveState: true, replace: true }
  )
})
</script>

<template>
  <AppLayout>
    <Head :title="ranking.title" />

    <div class="max-w-3xl mx-auto py-10 px-6">
      <!-- Flash -->
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

      <!-- Eliminar ranking -->
      <div v-if="page.props.auth?.user && ranking.user_id === page.props.auth.user.id" class="mb-6">
        <button
          @click="confirmDelete"
          class="px-4 py-2 bg-red-600 text-white rounded transition hover:bg-red-700"
        >
          🗑️ Eliminar rànquing
        </button>
      </div>

      <!-- Opcions -->
      <div class="space-y-4">
        <div
          v-for="opt in ranking.options"
          :key="opt.id"
          class="p-4 bg-white shadow rounded-lg"
        >
          <div class="flex items-center gap-4 mb-2">
            <img
              v-if="opt.image"
              :src="`/storage/${opt.image}`"
              alt="Option"
              class="w-16 h-16 object-cover rounded"
            />
            <span class="font-medium">{{ opt.name }}</span>
          </div>

          <!-- Barra percentatges -->
          <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
            <div
              class="bg-blue-600 h-4 transition-all duration-500"
              :style="{ width: getPercentage(opt.votes_count) + '%' }"
            ></div>
          </div>

          <!-- Percentatge -->
          <div class="flex justify-between text-sm text-gray-600 mt-1">
            <span>{{ opt.votes_count }} vots</span>
            <span>{{ getPercentage(opt.votes_count) }}%</span>
          </div>

          <!-- Botó votar -->
          <button
            v-if="!userVote || userVote.ranking_option_id !== opt.id"
            @click="vote(opt.id)"
            class="mt-3 px-4 py-2 bg-blue-600 text-white rounded transition hover:bg-blue-700"
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

      <!-- Formulari comentaris -->
      <div v-if="$page.props.auth?.user" class="mt-10 mb-4">
        <form @submit.prevent="submitComment" class="space-y-2">
          <textarea
            v-model="commentForm.content"
            placeholder="Escriu el teu comentari..."
            class="w-full border rounded p-2"
            rows="3"
          ></textarea>
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500">Máx. 1000 caràcters</div>
            <button
              :disabled="commentForm.processing || !commentForm.content"
              type="submit"
              class="px-4 py-2 bg-blue-600 text-white rounded transition hover:bg-blue-700 disabled:opacity-50"
            >
              Publicar
            </button>
          </div>
          <div v-if="commentForm.errors.content" class="text-red-600 text-sm">{{ commentForm.errors.content }}</div>
        </form>
      </div>

      <!-- No autenticat -->
      <div v-else class="mt-10 mb-4 text-sm">
        <a href="/login" class="text-blue-600 underline">Inicia sessió</a> per publicar comentaris.
      </div>

      <!-- Filtrar comentaris -->
      <div class="flex justify-end mb-6">
        <select
          v-model="sort"
          class="border rounded p-2 pr-8 transition hover:border-blue-400"
        >
          <option value="recent">Més recents</option>
          <option value="oldest">Més antics</option>
          <option value="likes">Més likes</option>
        </select>
      </div>

      <!-- Comentaris -->
      <div v-if="comments && comments.length > 0" class="space-y-4">
        <div v-for="comment in comments" :key="comment.id" class="p-4 bg-white border rounded">
          <div class="flex items-start justify-between">
            <div>
              <div class="text-sm font-semibold">{{ comment.user?.name || 'Usuari' }}</div>
              <div class="text-xs text-gray-500">{{ new Date(comment.created_at).toLocaleString() }}</div>
            </div>

            <div class="flex items-center gap-3">
              <!-- Like -->
              <button
                @click="voteComment(comment.id, 1)"
                :class="['text-sm px-2 py-1 rounded transition hover:bg-green-50', comment.user_vote === true ? 'bg-green-100 text-green-700' : 'text-gray-600']"
              >
                👍 {{ comment.likes_count ?? 0 }}
              </button>

              <!-- Dislike -->
              <button
                @click="voteComment(comment.id, 0)"
                :class="['text-sm px-2 py-1 rounded transition hover:bg-red-50', comment.user_vote === false ? 'bg-red-100 text-red-700' : 'text-gray-600']"
              >
                👎 {{ comment.dislikes_count ?? 0 }}
              </button>

              <!-- Eliminar -->
              <div v-if="$page.props.auth?.user && ($page.props.auth.user.id === comment.user_id || $page.props.auth.user.id === ranking.user_id)">
                <button @click="deleteComment(comment.id)" class="text-red-600 hover:underline text-sm">Eliminar</button>
              </div>
            </div>
          </div>
          <div class="mt-2 text-gray-700 whitespace-pre-line">{{ comment.content }}</div>
        </div>
      </div>

      <div v-else class="text-gray-600">Encara no hi ha comentaris. Sigues el primer!</div>
    </div>
  </AppLayout>
</template>
