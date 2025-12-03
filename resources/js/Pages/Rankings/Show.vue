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

const getYoutubeEmbedUrl = (url) => {
  if (!url) return null;
  const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
  const match = url.match(regExp);
  return (match && match[2].length === 11)
    ? `https://www.youtube.com/embed/${match[2]}`
    : null;
}

const isLikeAnimating = ref(false)

// Funció per a Like/Unlike
const toggleLike = () => {
  if (!page.props.auth?.user) {
    router.visit(route('login'))
    return
  }
  
  // Activa l'animació
  isLikeAnimating.value = true
  setTimeout(() => isLikeAnimating.value = false, 300)

  const routeName = props.ranking.user_has_liked ? 'rankings.unlike' : 'rankings.like';
  
  router.post(route(routeName, props.ranking.id), {}, {
    preserveScroll: true,
  })
}

const copied = ref(false)

const shareRanking = () => {
  const url = window.location.href
  
  navigator.clipboard.writeText(url).then(() => {
    copied.value = true
    setTimeout(() => (copied.value = false), 2000)

    flash.value.success = "Enllaç copiat al porta-retalls!"
    
    setTimeout(() => (flash.value.success = null), 3500)
  })
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
@keyframes like-pop {
  0% { transform: scale(1); }
  50% { transform: scale(1.4); }
  100% { transform: scale(1); }
}
.animate-like {
  animation: like-pop 0.3s ease-in-out;
}
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
          
          <div class="bg-gray-50 dark:bg-gray-900 transition-colors duration-500 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-800">
            
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
              <div class="absolute top-0 right-0 flex items-center gap-2">
                <button 
                  @click="toggleLike"
                  :class="[
                    'flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold transition-all duration-200 shadow-sm border',
                    ranking.user_has_liked 
                      ? 'bg-red-100 text-red-600 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800' 
                      : 'bg-white text-gray-600 border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700'
                  ]"
                  title="M'agrada"
                >
                  <svg 
                    :class="[
                      'w-5 h-5 transition-all', 
                      ranking.user_has_liked ? 'fill-current text-red-500' : 'stroke-current',
                      { 'animate-like': isLikeAnimating }
                    ]" 
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"></path>
                  </svg>
                  <span>{{ ranking.likes_count }}</span>
                </button>
                <FavoriteStar :ranking="ranking" />
              </div>
              
              <div class="pr-24">
                <h1 class="text-3xl font-bold mb-2 text-gray-900 dark:text-gray-100 truncate-3-lines">
                  {{ ranking.title }}
                </h1>
                <p class="text-gray-700 dark:text-gray-300 mb-6 truncate-3-lines">
                  {{ ranking.description }}
                </p>
              </div>

              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                  <img 
                    v-if="ranking.user?.profile_photo_url"
                    :src="ranking.user.profile_photo_url" 
                    alt="Avatar" 
                    class="w-8 h-8 rounded-full object-cover border border-gray-200 dark:border-gray-700"
                  >
                  <div v-else class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center border border-gray-200 dark:border-gray-700">
                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                  </div>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Creat per: <span class="font-semibold">{{ ranking.user?.name || 'Usuari desconegut' }}</span>
                  </p>
                </div>

                <button 
                  @click="shareRanking"
                  class="flex items-center justify-center w-8 h-8 rounded-full bg-white text-gray-600 dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 shadow-sm focus:outline-none"
                  :title="copied ? 'Copiat!' : 'Compartir'"
                >
                  <span v-if="copied" class="text-green-500 transition-all duration-300 transform scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                  </span>
                  <span v-else class="transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                  </span>
                </button>
              </div>
            </div>

            <div v-if="page.props.auth?.user && (ranking.user_id === page.props.auth.user.id || page.props.auth.user.is_admin)" class="mt-4">
              <button @click="confirmDelete" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                Eliminar rànquing
              </button>
            </div>
          </div>

          <div class="bg-gray-50 dark:bg-gray-900 transition-colors duration-500 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-800">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Comentaris</h2>
            
            <div v-if="$page.props.auth?.user" class="mb-6">
              <form @submit.prevent="submitComment" class="space-y-2">
                <textarea ref="textareaRef" v-model="commentForm.content" placeholder="Escriu el teu comentari..." class="w-full border dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-100 rounded p-2 resize-none transition-all duration-300" rows="3" @input="autoResize($event)"></textarea>
                <div class="flex justify-between items-center">
                  <div :class="['char-counter', charCount < charLimit ? 'safe' : 'warning']">{{ charCount }} / {{ charLimit }}</div>
                  <button type="submit" :disabled="commentForm.processing || !commentForm.content" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-red-600 dark:hover:bg-red-700 text-white rounded disabled:opacity-50 transition">Publicar</button>
                </div>
              </form>
            </div>
            <div v-else class="text-gray-600 dark:text-gray-300 text-sm"><a href="/login" class="text-blue-600 underline">Inicia sessió</a> per publicar comentaris.</div>

            <div class="mb-4 flex items-center gap-3">
              <label for="sort" class="text-gray-700 dark:text-gray-300 text-sm">Ordenar per:</label>
              <select id="sort" v-model="sort" class="border rounded-lg px-4 py-2 w-48 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100 cursor-pointer">
                <option value="likes">Més likes</option>
                <option value="recent">Més recents</option>
                <option value="oldest">Més antics</option>
              </select>
            </div>
            
            <div v-if="loadingComments" class="space-y-4 mt-6 animate-pulse">
               <div v-for="i in 3" :key="i" class="p-4 bg-white dark:bg-gray-800 rounded border dark:border-gray-700"><div class="flex justify-between"><div><div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-24 mb-2"></div><div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-32"></div></div><div class="flex items-center gap-2"><div class="h-6 w-10 bg-gray-200 dark:bg-gray-700 rounded"></div><div class="h-6 w-10 bg-gray-200 dark:bg-gray-700 rounded"></div></div></div><div class="mt-3 space-y-2"><div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-full"></div><div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-5/6"></div></div></div>
            </div>
            <div v-else-if="comments && comments.length > 0" class="space-y-4 mt-6" v-auto-animate>
              <div v-for="comment in comments" :key="comment.id" class="p-4 bg-white dark:bg-gray-800 rounded border dark:border-gray-700">
                <div class="flex justify-between">
                  <div class="flex items-center gap-3 min-w-0">
                    <img v-if="comment.user?.profile_photo_url" :src="comment.user.profile_photo_url" alt="Avatar" class="w-9 h-9 rounded-full object-cover border border-gray-200 dark:border-gray-700 flex-shrink-0">
                    <div v-else class="w-9 h-9 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center border border-gray-200 dark:border-gray-700 flex-shrink-0"><svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg></div>
                    <div class="min-w-0">
                      <div class="font-semibold text-sm truncate">{{ comment.user?.name || 'Usuari' }}</div>
                      <div class="text-xs text-gray-500 dark:text-gray-400">{{ new Date(comment.created_at).toLocaleString() }}</div>
                    </div>
                  </div>
                  <div class="flex items-center gap-2 flex-shrink-0">
                    <button @click="voteComment(comment.id, 1)" :class="['text-sm px-2 py-1 rounded transition', comment.user_vote === true ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200' : 'text-gray-600 dark:text-gray-400 hover:bg-green-50 dark:hover:bg-green-800']">👍 {{ comment.likes_count ?? 0 }}</button>
                    <button @click="voteComment(comment.id, 0)" :class="['text-sm px-2 py-1 rounded transition', comment.user_vote === false ? 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200' : 'text-gray-600 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-800']">👎 {{ comment.dislikes_count ?? 0 }}</button>
                    <button v-if="page.props.auth?.user && (comment.user_id === page.props.auth.user.id || ranking.user_id === page.props.auth.user.id || page.props.auth.user.is_admin)" @click="deleteComment(comment.id)" class="text-xs px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">🗑️</button>
                  </div>
                </div>
                <div class="mt-2">
                    <div class="comment-clamped" :style="!isExpanded(comment.id) ? { maxHeight: '7.5rem', overflow: 'hidden' } : { maxHeight: 'none' }"><div class="comment-content text-gray-700 dark:text-gray-200" :data-id="comment.id">{{ comment.content }}</div></div>
                    <div v-if="needsShowMore(comment.id)" class="mt-2"><button class="show-more-btn" @click="toggleExpanded(comment.id)"><span v-if="!isExpanded(comment.id)">Veure més</span><span v-else>Veure menys</span></button></div>
                </div>
              </div>
            </div>
            <div v-else class="text-gray-600 dark:text-gray-400 mt-4">Encara no hi ha comentaris.</div>
          </div>
        </div>

        <div class="lg:col-span-3">
          
          <div class="bg-gray-50 dark:bg-gray-900 transition-colors duration-500 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-800">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-8">Vota les Opcions</h2>
            
            <div class="grid grid-cols-1 gap-6" v-auto-animate>
              
              <div 
                v-for="opt in rankedOptions" 
                :key="opt.id" 
                :class="[
                  'relative flex flex-col sm:flex-row rounded-xl shadow-md transition-all duration-300 hover:shadow-lg overflow-visible border', 
                  getPodiumClasses(opt.rank, opt.votes_count).card.replace('p-4', '') 
                ]"
              > 
                <span 
                  :class="[
                    'absolute -top-3 -left-3 flex items-center justify-center w-10 h-10 rounded-full text-white font-bold text-xl shadow-lg z-30',
                    getPodiumClasses(opt.rank, opt.votes_count).badge
                  ]"
                >
                  {{ opt.rank }}
                </span>

                <div class="w-full sm:w-48 sm:h-48 bg-black relative group shrink-0 sm:rounded-l-xl overflow-hidden">
                  
                  <iframe 
                    v-if="opt.video_url"
                    :src="getYoutubeEmbedUrl(opt.video_url)"
                    class="w-full h-full absolute inset-0"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                  ></iframe>

                  <img
                    v-else-if="opt.image && (opt.is_approved || $page.props.auth?.user?.is_admin || $page.props.auth?.user?.id === ranking.user_id)"
                    :src="opt.image.startsWith('/storage/') ? opt.image : `/storage/${opt.image}`"
                    alt="Imatge opció"
                    class="w-full h-full object-cover absolute inset-0 transition-transform duration-500 group-hover:scale-105"
                  />
                  
                  <div v-else class="w-full h-full flex flex-col items-center justify-center text-gray-500 bg-gray-200 dark:bg-gray-800">
                    <span class="text-5xl mb-2">📷</span>
                    <span class="text-sm font-medium">Sense contingut</span>
                  </div>
                </div>

                <div class="flex-1 p-6 flex flex-col justify-center bg-white dark:bg-gray-800 sm:rounded-r-xl">
                  
                  <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-gray-100 leading-tight" :title="opt.name">
                    {{ opt.name }}
                  </h3>

                  <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4 overflow-hidden mb-2 shadow-inner">
                    <div 
                      class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-red-500 dark:to-red-600 h-4 transition-all duration-700 ease-out relative" 
                      :style="{ width: getPercentage(opt.votes_count) + '%' }"
                    ></div>
                  </div>

                  <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-4 font-medium">
                    <span>{{ opt.votes_count }} vots</span>
                    <span>{{ getPercentage(opt.votes_count) }}%</span>
                  </div>

                  <div class="mt-auto">
                    <button
                      v-if="votedOptionId !== opt.id"
                      @click="vote(opt.id)"
                      class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 dark:bg-red-600 dark:hover:bg-red-700 text-white font-bold rounded-lg shadow-md active:scale-[0.98] transform transition-all duration-200 flex items-center justify-center gap-2"
                    >
                      Votar
                    </button>

                    <template v-else>
                      <div class="flex items-center gap-3 w-full">
                        <div class="flex-1 py-2 px-3 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 font-bold rounded-lg text-center text-sm flex items-center justify-center">
                          ✅ Has votat
                        </div>
                        
                        <button
                          @click="unvoteRanking"
                          class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition text-sm shadow-sm whitespace-nowrap"
                        >
                          Retirar vot
                        </button>
                      </div>
                    </template>
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