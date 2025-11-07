<script setup>
import { Head, router, usePage } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import FavoriteStar from '@/Components/FavoriteStar.vue'
import { useForm } from '@inertiajs/vue3'
import { vAutoAnimate } from '@formkit/auto-animate'
import { onMounted, nextTick } from 'vue'

const props = defineProps({
  ranking: Object,
  userVote: Object,
  votedOptionId: [Number, null],
  comments: Array,
  sort: { type: String, default: 'likes' }
})

const page = usePage()

// Flash messages
const flash = ref({ success: null, error: null })
watch(
  () => page.props.flash,
  (newFlash) => {
    if (newFlash.success) {
      flash.value.success = newFlash.success
      setTimeout(() => (flash.value.success = null), 3500)
    }
    if (newFlash.error) {
      flash.value.error = newFlash.error
      setTimeout(() => (flash.value.error = null), 3500)
    }
  },
  { immediate: true }
)

// Total vots
const totalVotes = computed(() =>
  props.ranking.options.reduce((sum, opt) => sum + opt.votes_count, 0)
)
const getPercentage = (votes) =>
  totalVotes.value === 0 ? 0 : Math.round((votes / totalVotes.value) * 100)
  
const sortedOptions = computed(() => {
  return [...props.ranking.options].sort((a, b) => b.votes_count - a.votes_count)
})

// Calcula el "rang de competició" (per als empats)
const rankedOptions = computed(() => {
  let lastScore = -1
  let currentRank = 0

  return sortedOptions.value.map((option, index) => {
    // Comprovem si els vots són diferents a l'anterior
    if (option.votes_count !== lastScore) {
      currentRank = index + 1 // Si no hi ha empat, el rang és la posició (index + 1)
    }
    // Si són iguals (empat), 'currentRank' no canvia i s'assigna el mateix que l'anterior

    lastScore = option.votes_count

    return {
      ...option,
      rank: currentRank
    }
  })
})

// Votar / treure vot
const vote = (optionId) => {
  if (props.votedOptionId === optionId)
    router.post(`/rankings/${props.ranking.id}/unvote`, {}, { preserveScroll: true })
  else
    router.post(`/rankings/${props.ranking.id}/vote`, { option_id: optionId }, { preserveScroll: true })
}

// Lògica per als colors del podi
const getPodiumClasses = (rank, votes) => {
  // Si no té vots, color base (ni or, ni plata, ni bronze)
  if (votes === 0) {
    return {
      card: 'bg-white dark:bg-gray-800 hover:shadow-blue-500/30 dark:hover:shadow-red-500/30',
      badge: 'bg-blue-600 dark:bg-red-600'
    }
  }

  // Assignar colors segons el 'rank' calculat
  switch (rank) {
    case 1: // 1r Lloc - Or
      return {
        card: 'bg-yellow-50 dark:bg-yellow-900/50 border-2 border-yellow-400 dark:border-yellow-600 hover:shadow-yellow-500/40 dark:hover:shadow-yellow-400/40',
        badge: 'bg-yellow-500 dark:bg-yellow-600'
      }
    case 2: // 2n Lloc - Plata
      return {
        card: 'bg-gray-50 dark:bg-gray-700/50 border-2 border-gray-300 dark:border-gray-500 hover:shadow-gray-500/40 dark:hover:shadow-gray-400/40',
        badge: 'bg-gray-500 dark:bg-gray-600'
      }
    case 3: // 3r Lloc - Bronze
      return {
        card: 'bg-orange-50 dark:bg-orange-900/50 border-2 border-orange-400 dark:border-orange-600 hover:shadow-orange-500/40 dark:hover:shadow-orange-400/40',
        badge: 'bg-orange-600 dark:bg-orange-700' // Colors bronze/taronja
      }
    default: // 4t+ Lloc - Base
      return {
        card: 'bg-white dark:bg-gray-800 hover:shadow-blue-500/30 dark:hover:shadow-red-500/30',
        badge: 'bg-blue-600 dark:bg-red-600'
      }
  }
}

const unvoteRanking = () =>
  router.post(`/rankings/${props.ranking.id}/unvote`, {}, { preserveScroll: true })

// Eliminar rànquing
const confirmDelete = () => {
  if (confirm("⚠️ Estàs segur que vols eliminar aquest rànquing? Aquesta acció no es podrà desfer."))
    router.delete(`/rankings/${props.ranking.id}`)
}

// Formulari comentaris
const commentForm = useForm({ content: '' })
const textareaRef = ref(null)
const charCount = ref(0)
const charLimit = 1000

const submitComment = () => {
  commentForm.post(route('rankings.comments.store', props.ranking.id), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      commentForm.reset('content')
      charCount.value = 0
      resetTextareaHeight()
    }
  })
}

// Limitar a 1000 caràcters + comptador
watch(() => commentForm.content, (val) => {
  if (val) {
    if (val.length > charLimit) {
      commentForm.content = val.slice(0, charLimit)
    }
    charCount.value = commentForm.content.length
  } else {
    charCount.value = 0
  }
})

// Auto-resize textarea
const autoResize = (event) => {
  const textarea = event.target
  textarea.style.height = 'auto'
  textarea.style.transition = 'height 0.15s ease'
  const newHeight = Math.min(textarea.scrollHeight, 300)
  textarea.style.height = newHeight + 'px'
}

const resetTextareaHeight = () => {
  if (textareaRef.value) {
    textareaRef.value.style.height = 'auto'
  }
}

// Eliminar comentari
const deleteComment = (commentId) => {
  if (!confirm('Estàs segur que vols eliminar aquest comentari?')) return
  router.delete(route('rankings.comments.destroy', { ranking: props.ranking.id, comment: commentId }), {
    preserveScroll: true
  })
}

// Like/dislike comentaris
const voteComment = (commentId, isLike) => {
  const comment = props.comments.find(c => c.id === commentId)
  if ((comment.user_vote === true && isLike === 1) || (comment.user_vote === false && isLike === 0))
    router.post(`/comments/${commentId}/unvote`, {}, { preserveScroll: true })
  else
    router.post(`/comments/${commentId}/vote`, { is_like: isLike }, { preserveScroll: true })
}

// Filtre comentaris
const sort = ref(props.sort || 'likes')
const loadingComments = ref(false)

watch(sort, async (newSort) => {
  loadingComments.value = true
  await router.get(
    route('rankings.show', props.ranking.id),
    { sort: newSort },
    {
      preserveScroll: true,
      preserveState: true,
      replace: true,
      onFinish: () => (loadingComments.value = false)
    }
  )
})

// Control d'expansió de comentaris
const expandedComments = ref({})

function toggleExpanded(commentId) {
  expandedComments.value[commentId] = !expandedComments.value[commentId]
}

function isExpanded(commentId) {
  return !!expandedComments.value[commentId]
}

// Veure més / menys comentaris
const longComments = ref({}) // Guarda si cada comentari és llarg (necessita "veure més")

onMounted(() => {
  nextTick(() => checkOverflow()) // Esperem que es renderitzi el contingut
})

// Recalcular quan canviïn els comentaris
watch(() => props.comments, () => {
  nextTick(() => checkOverflow())
})

function checkOverflow() {
  longComments.value = {}
  document.querySelectorAll('.comment-content').forEach((el) => {
    const id = el.getAttribute('data-id')

    // Guardem l'alçada original
    const originalMaxHeight = el.style.maxHeight
    const parent = el.closest('.comment-clamped')

    // Traiem límits per mesurar l'alçada real
    if (parent) parent.style.maxHeight = 'none'

    // Comprovem si és més alt que el límit
    if (el.scrollHeight > 120) {
      longComments.value[id] = true
    }

    // Restablim l'alçada original
    if (parent) parent.style.maxHeight = ''
  })
}

function needsShowMore(commentId) {
  return !!longComments.value[commentId]
}
</script>

<style scoped>
.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.slide-fade-enter-from,
.slide-fade-leave-to {
  opacity: 0;
  transform: translateX(100%);
}
.comment-content {
  white-space: pre-wrap;
  word-break: break-word;
  overflow-wrap: anywhere;
}
.comment-clamped {
  overflow: hidden;
  transition: max-height 0.4s ease;
}
.comment-clamped::after {
  display: none;
}
.show-more-btn {
  background: transparent;
  border: none;
  color: #2563eb;
  cursor: pointer;
  padding: 0;
  font-size: 0.9rem;
}
textarea.resize-none {
  resize: none;
}
.char-counter {
  font-size: 0.85rem;
  transition: all 0.3s ease;
}
.char-counter.safe {
  color: #6b7280;
}
.char-counter.warning {
  color: #ef4444;
  font-weight: 600;
}
.truncate-3-lines {
  overflow: hidden;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  
  word-break: break-word; 
}
</style>

<template>
  <AppLayout>
    <Head :title="ranking.title" />

    <div class="max-w-7xl mx-auto py-10 px-6">
      
      <transition name="slide-fade">
        <div v-if="flash.success" class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-xl shadow-lg z-50">
          {{ flash.success }}
        </div>
      </transition>
      <transition name="slide-fade">
        <div v-if="flash.error" class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-xl shadow-lg z-50">
          {{ flash.error }}
        </div>
      </transition>

      <div class="grid grid-cols-1 lg:grid-cols-5 lg:gap-12">

        <div class="lg:col-span-2 space-y-8">
          
          <div class="bg-gray-50 dark:bg-gray-900 transition-colors duration-500 rounded-xl shadow-sm p-4">
            
            <img
              v-if="ranking.image && (ranking.is_approved || $page.props.auth?.user?.is_admin || $page.props.auth?.user?.id === ranking.user_id)"
              :src="ranking.image.startsWith('/storage/') ? ranking.image : `/storage/${ranking.image}`"
              alt="Imatge del rànquing"
              class="w-full h-64 object-cover rounded-lg mb-6"
            />
            <div v-else class="w-full h-64 flex items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg mb-6">
              <span class="text-gray-400 text-sm">Sense imatge</span>
            </div>

            <div class="relative">
              <FavoriteStar :ranking="ranking" class="absolute top-0 right-0" />
              
              <div class="pr-10">
                <h1 class="text-3xl font-bold mb-2 text-gray-900 dark:text-gray-100 truncate-3-lines">
                  {{ ranking.title }}
                </h1>
                
                <p class="text-gray-700 dark:text-gray-300 mb-6 truncate-3-lines">
                  {{ ranking.description }}
                </p>
              </div>

              <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                Creat per: <span class="font-semibold">{{ ranking.user?.name || 'Usuari desconegut' }}</span>
              </p>
            </div>

            <div v-if="page.props.auth?.user && (ranking.user_id === page.props.auth.user.id || page.props.auth.user.is_admin)" class="mb-6">
              <button @click="confirmDelete" class="w-full px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                Eliminar rànquing
              </button>
            </div>
          </div>
          <div class="bg-gray-50 dark:bg-gray-900 transition-colors duration-500 rounded-xl shadow-sm p-6">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Comentaris</h2>

            <div v-if="$page.props.auth?.user" class="mb-6">
              <form @submit.prevent="submitComment" class="space-y-2">
                <textarea
                  ref="textareaRef"
                  v-model="commentForm.content"
                  placeholder="Escriu el teu comentari..."
                  class="w-full border dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-100 rounded p-2 resize-none transition-all duration-300"
                  rows="3"
                  @input="autoResize($event)"
                ></textarea>

                <div class="flex justify-between items-center">
                  <div :class="['char-counter', charCount < charLimit ? 'safe' : 'warning']">
                    {{ charCount }} / {{ charLimit }}
                  </div>
                  <button
                    type="submit"
                    :disabled="commentForm.processing || !commentForm.content"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-red-600 dark:hover:bg-red-700 text-white rounded disabled:opacity-50 transition"
                  >
                    Publicar
                  </button>
                </div>
              </form>
            </div>
            <div v-else class="text-gray-600 dark:text-gray-300 text-sm">
              <a href="/login" class="text-blue-600 underline">Inicia sessió</a> per publicar comentaris.
            </div>

            <div class="mb-4 flex items-center gap-3">
              <label for="sort" class="text-gray-700 dark:text-gray-300 text-sm">Ordenar per:</label>
              <select
                id="sort"
                v-model="sort"
                class="border rounded-lg px-4 py-2 w-48 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100"
              >
                <option value="likes">Més likes</option>
                <option value="recent">Més recents</option>
                <option value="oldest">Més antics</option>
              </select>
            </div>
            <div v-if="loadingComments" class="space-y-4 mt-6 animate-pulse">
              <div v-for="i in 3" :key="i" class="p-4 bg-white dark:bg-gray-800 rounded border dark:border-gray-700">
                <div class="flex justify-between">
                  <div>
                    <div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-24 mb-2"></div>
                    <div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-32"></div>
                  </div>
                  <div class="flex items-center gap-2">
                    <div class="h-6 w-10 bg-gray-200 dark:bg-gray-700 rounded"></div>
                    <div class="h-6 w-10 bg-gray-200 dark:bg-gray-700 rounded"></div>
                  </div>
                </div>
                <div class="mt-3 space-y-2">
                  <div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-full"></div>
                  <div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-5/6"></div>
                </div>
              </div>
            </div>
            <div v-else-if="comments && comments.length > 0" class="space-y-4 mt-6" v-auto-animate>
              <div v-for="comment in comments" :key="comment.id" class="p-4 bg-white dark:bg-gray-800 rounded border dark:border-gray-700">
                <div class="flex justify-between">
                  <div>
                    <div class="font-semibold text-sm">{{ comment.user?.name || 'Usuari' }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ new Date(comment.created_at).toLocaleString() }}</div>
                  </div>

                  <div class="flex items-center gap-2">
                    <button
                      @click="voteComment(comment.id, 1)"
                      :class="['text-sm px-2 py-1 rounded transition', comment.user_vote === true ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200' : 'text-gray-600 dark:text-gray-400 hover:bg-green-50 dark:hover:bg-green-800']"
                    >
                      👍 {{ comment.likes_count ?? 0 }}
                    </button>
                    <button
                      @click="voteComment(comment.id, 0)"
                      :class="['text-sm px-2 py-1 rounded transition', comment.user_vote === false ? 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200' : 'text-gray-600 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-800']"
                    >
                      👎 {{ comment.dislikes_count ?? 0 }}
                    </button>

                    <button
                      v-if="page.props.auth?.user && (comment.user_id === page.props.auth.user.id || ranking.user_id === page.props.auth.user.id || page.props.auth.user.is_admin)"
                      @click="deleteComment(comment.id)"
                      class="text-xs px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition"
                    >
                      🗑️
                    </button>
                  </div>
                </div>

                <div class="mt-2">
                  <div
                    class="comment-clamped"
                    :style="!isExpanded(comment.id) ? { maxHeight: '7.5rem', overflow: 'hidden' } : { maxHeight: 'none' }"
                  >
                    <div
                      class="comment-content text-gray-700 dark:text-gray-200"
                      :data-id="comment.id"
                    >
                      {{ comment.content }}
                    </div>
                  </div>

                  <div v-if="needsShowMore(comment.id)" class="mt-2">
                    <button class="show-more-btn" @click="toggleExpanded(comment.id)">
                      <span v-if="!isExpanded(comment.id)">Veure més</span>
                      <span v-else>Veure menys</span>
                    </button>
                  </div>
                  </div>
                </div>
            </div>
            <div v-else class="text-gray-600 dark:text-gray-400 mt-4">
              Encara no hi ha comentaris. Sigues el primer!
            </div>

          </div>
          </div>
        <div class="lg:col-span-3">
          
          <div class="bg-gray-50 dark:bg-gray-900 transition-colors duration-500 rounded-xl shadow-sm p-6">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Vota les Opcions</h2>
            
            <div class="grid grid-cols-1 gap-4" v-auto-animate>
              
              <div 
                v-for="opt in rankedOptions" 
                :key="opt.id" 
                :class="[
                  'relative p-4 rounded-lg shadow-md transition-all duration-300 hover:shadow-lg', 
                  getPodiumClasses(opt.rank, opt.votes_count).card
                ]"
              > 
                <span 
                  :class="[
                    'absolute -top-3 -left-3 flex items-center justify-center w-8 h-8 rounded-full text-white font-bold text-lg shadow-lg z-10',
                    getPodiumClasses(opt.rank, opt.votes_count).badge
                  ]"
                >
                  {{ opt.rank }}
                </span>

                <div class="flex flex-col sm:flex-row sm:items-center sm:gap-6">

                  <div class="flex-shrink-0 w-full sm:w-32 h-32 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded overflow-hidden mb-3 sm:mb-0">
                    <img
                      v-if="opt.image && (opt.is_approved || $page.props.auth?.user?.is_admin || $page.props.auth?.user?.id === ranking.user_id)"
                      :src="opt.image.startsWith('/storage/') ? opt.image : `/storage/${opt.image}`"
                      alt="Imatge opció"
                      class="w-full h-full object-cover"
                    />
                    <span v-else class="text-gray-400 text-sm">Sense imatge</span>
                  </div>

                  <div class="flex-1 min-w-0">
                    
                    <h3 class="text-lg font-bold truncate mb-2 text-gray-900 dark:text-gray-100" :title="opt.name">
                      {{ opt.name }}
                    </h3>

                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4 overflow-hidden">
                      <div 
                        class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-red-500 dark:to-red-600 h-4 transition-all duration-700 ease-out" 
                        :style="{ width: getPercentage(opt.votes_count) + '%' }"
                      ></div>
                    </div>

                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mt-1">
                      <span>{{ opt.votes_count }} vots</span>
                      <span>{{ getPercentage(opt.votes_count) }}%</span>
                    </div>

                    <div class="mt-4 flex items-center gap-3">
                      <button
                        v-if="votedOptionId !== opt.id"
                        @click="vote(opt.id)"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-red-600 dark:hover:bg-red-700 text-white rounded active:scale-95 transform transition-all duration-150 ease-out"
                      >
                        Votar
                      </button>

                      <template v-else>
                        <span class="px-4 py-2 bg-green-600 text-white rounded">
                          ✅ Has votat "{{ opt.name }}"
                        </span>
                        <button
                          @click="unvoteRanking"
                          class="px-3 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded hover:bg-gray-400 dark:hover:bg-gray-500 active:scale-95 transform transition-all duration-150 ease-out"
                        >
                          Treure votació
                        </button>
                      </template>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>