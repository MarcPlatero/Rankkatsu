<script setup>
import { Head, router, usePage } from '@inertiajs/vue3'
import { computed, ref, watch, onMounted, nextTick } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import FavoriteStar from '@/Components/FavoriteStar.vue'
import { useForm } from '@inertiajs/vue3'
import { vAutoAnimate } from '@formkit/auto-animate'
import AdBanner from '@/Components/AdBanner.vue'
import PixelAvatar from '@/Components/PixelAvatar.vue'

const props = defineProps({
  ranking: Object,
  userVote: Object,
  votedOptionId: [Number, null],
  comments: Array,
  sort: { type: String, default: 'likes' }
})

const seoImageUrl = computed(() => {
  if (typeof window === 'undefined') return ''
  if (!props.ranking.image) return window.location.origin + '/images/og-default.jpg'
  let path = props.ranking.image
  if (path.startsWith('http')) return path
  const cleanPath = path.startsWith('/') ? path.substring(1) : path
  const storagePrefix = cleanPath.startsWith('storage/') ? '' : 'storage/'
  return `${window.location.origin}/${storagePrefix}${cleanPath}`
})

const currentUrl = computed(() => {
  if (typeof window === 'undefined') return ''
  return window.location.href
})

const page = usePage()
const isPremium = computed(() => page.props.auth.user?.is_premium)
const showAdOverlay = ref(false)
const adCountdown = ref(5)

const isPixelAvatar = (path) => {
  return path && typeof path === 'string' && path.startsWith('pixel-')
}

onMounted(() => {
  nextTick(() => checkOverflow())
  if (!isPremium.value) {
    let visits = parseInt(localStorage.getItem('rank_visits') || '0')
    visits++
    if (visits >= 10) {
      showAdOverlay.value = true
      adCountdown.value = 5
      visits = 0
      const timer = setInterval(() => {
        adCountdown.value--
        if (adCountdown.value <= 0) {
          clearInterval(timer)
          showAdOverlay.value = false
        }
      }, 1000)
    }
    localStorage.setItem('rank_visits', visits.toString())
  }
})

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

// Càlculs
const totalVotes = computed(() =>
  props.ranking.options.reduce((sum, opt) => sum + opt.votes_count, 0)
)
const getPercentage = (votes) =>
  totalVotes.value === 0 ? 0 : Math.round((votes / totalVotes.value) * 100)
  
const sortedOptions = computed(() => {
  return [...props.ranking.options].sort((a, b) => b.votes_count - a.votes_count)
})

const rankedOptions = computed(() => {
  let lastScore = -1
  let currentRank = 0
  return sortedOptions.value.map((option, index) => {
    if (option.votes_count !== lastScore) {
      currentRank = index + 1
    }
    lastScore = option.votes_count
    return { ...option, rank: currentRank }
  })
})

const vote = (optionId) => {
  if (props.votedOptionId === optionId)
    router.post(`/rankings/${props.ranking.id}/unvote`, {}, { preserveScroll: true })
  else
    router.post(`/rankings/${props.ranking.id}/vote`, { option_id: optionId }, { preserveScroll: true })
}

// Estils del podi
const getPodiumClasses = (rank, votes) => {
  if (votes === 0) {
    return {
      card: 'bg-white dark:bg-gray-800 border-l-4 border-gray-200 dark:border-gray-700',
      badge: 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-300'
    }
  }
  switch (rank) {
    case 1: 
      return {
        card: 'bg-gradient-to-r from-yellow-50 to-white dark:from-yellow-900/20 dark:to-gray-800 border-l-4 border-yellow-400 ring-1 ring-yellow-400/30',
        badge: 'bg-gradient-to-br from-yellow-400 to-yellow-600 text-white shadow-yellow-500/50'
      }
    case 2: 
      return {
        card: 'bg-gradient-to-r from-gray-50 to-white dark:from-gray-800/50 dark:to-gray-800 border-l-4 border-gray-400 ring-1 ring-gray-400/30',
        badge: 'bg-gradient-to-br from-gray-400 to-gray-600 text-white shadow-gray-500/50'
      }
    case 3: 
      return {
        card: 'bg-gradient-to-r from-orange-50 to-white dark:from-orange-900/20 dark:to-gray-800 border-l-4 border-orange-400 ring-1 ring-orange-400/30',
        badge: 'bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-orange-500/50'
      }
    default: 
      return {
        card: 'bg-white dark:bg-gray-800 border-l-4 border-transparent hover:border-blue-400 dark:hover:border-red-500',
        badge: 'bg-blue-100 text-blue-600 dark:bg-gray-700 dark:text-gray-300'
      }
  }
}

const unvoteRanking = () =>
  router.post(`/rankings/${props.ranking.id}/unvote`, {}, { preserveScroll: true })

const confirmDelete = () => {
  if (confirm("⚠️ Estàs segur que vols eliminar aquest rànquing? Aquesta acció no es podrà desfer."))
    router.delete(`/rankings/${props.ranking.id}`)
}

const getYoutubeEmbedUrl = (url) => {
  if (!url) return null;
  const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
  const match = url.match(regExp);
  return (match && match[2].length === 11) ? `https://www.youtube.com/embed/${match[2]}` : null;
}

const isLikeAnimating = ref(false)
const toggleLike = () => {
  if (!page.props.auth?.user) {
    router.visit(route('login'))
    return
  }
  isLikeAnimating.value = true
  setTimeout(() => isLikeAnimating.value = false, 300)
  const routeName = props.ranking.user_has_liked ? 'rankings.unlike' : 'rankings.like';
  router.post(route(routeName, props.ranking.id), {}, { preserveScroll: true })
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

// Comentaris
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

watch(() => commentForm.content, (val) => {
  if (val) {
    if (val.length > charLimit) commentForm.content = val.slice(0, charLimit)
    charCount.value = commentForm.content.length
  } else {
    charCount.value = 0
  }
})

const autoResize = (event) => {
  const textarea = event.target
  textarea.style.height = 'auto'
  const newHeight = Math.min(textarea.scrollHeight, 300)
  textarea.style.height = newHeight + 'px'
}

const resetTextareaHeight = () => {
  if (textareaRef.value) textareaRef.value.style.height = 'auto'
}

const deleteComment = (commentId) => {
  if (!confirm('Estàs segur que vols eliminar aquest comentari?')) return
  router.delete(route('rankings.comments.destroy', { ranking: props.ranking.id, comment: commentId }), {
    preserveScroll: true
  })
}

const voteComment = (commentId, isLike) => {
  const comment = props.comments.find(c => c.id === commentId)
  if ((comment.user_vote === true && isLike === 1) || (comment.user_vote === false && isLike === 0))
    router.post(`/comments/${commentId}/unvote`, {}, { preserveScroll: true })
  else
    router.post(`/comments/${commentId}/vote`, { is_like: isLike }, { preserveScroll: true })
}

// Lògica de Filtre i Scroll
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

const expandedComments = ref({})
function toggleExpanded(commentId) {
  expandedComments.value[commentId] = !expandedComments.value[commentId]
}
function isExpanded(commentId) {
  return !!expandedComments.value[commentId]
}

const longComments = ref({})
watch(() => props.comments, () => nextTick(() => checkOverflow()))

function checkOverflow() {
  longComments.value = {}
  document.querySelectorAll('.comment-content').forEach((el) => {
    const id = el.getAttribute('data-id')
    const parent = el.closest('.comment-clamped')
    if (parent) parent.style.maxHeight = 'none'
    if (el.scrollHeight > 120) longComments.value[id] = true
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
.animate-like { animation: like-pop 0.3s ease-in-out; }
.slide-fade-enter-active, .slide-fade-leave-active { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
.slide-fade-enter-from, .slide-fade-leave-to { opacity: 0; transform: translateX(100%); }

.comment-content { white-space: pre-wrap; word-break: break-word; overflow-wrap: anywhere; }
.comment-clamped { overflow: hidden; transition: max-height 0.4s ease; }
.show-more-btn { background: transparent; border: none; color: #2563eb; cursor: pointer; padding: 0; font-size: 0.9rem; font-weight: 600; }
textarea.resize-none { resize: none; }
.char-counter { font-size: 0.85rem; transition: all 0.3s ease; }
.char-counter.safe { color: #6b7280; }
.char-counter.warning { color: #ef4444; font-weight: 600; }
.scrollbar-hide::-webkit-scrollbar { display: none; }
.custom-scroll::-webkit-scrollbar { width: 6px; }
.custom-scroll::-webkit-scrollbar-track { background: transparent; }
.custom-scroll::-webkit-scrollbar-thumb { background-color: rgba(156, 163, 175, 0.5); border-radius: 20px; }
</style>

<template>
  <AppLayout>
    <Head>
      <title>{{ ranking.title }}</title>
      <meta name="description" :content="ranking.description || ''" />
      <meta property="og:type" content="website" />
      <meta property="og:title" :content="ranking.title" />
      <meta property="og:description" :content="ranking.description || ''" />
      <meta property="og:image" :content="seoImageUrl" />
      <meta property="og:url" :content="currentUrl" />
      <meta name="twitter:card" content="summary_large_image" />
      <meta name="twitter:title" :content="ranking.title" />
      <meta name="twitter:description" :content="ranking.description || ''" />
      <meta name="twitter:image" :content="seoImageUrl" />
    </Head>

    <div class="min-h-screen bg-gray-50/50 dark:bg-[#0b1120]">
      <div class="max-w-[1400px] mx-auto py-8 px-4 sm:px-6 lg:px-8">
        
        <transition name="slide-fade">
          <div v-if="flash.success" class="fixed top-20 right-4 bg-green-500 text-white px-6 py-3 rounded-2xl shadow-xl z-50 flex items-center gap-3 backdrop-blur-md bg-opacity-90 border border-green-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-medium">{{ flash.success }}</span>
          </div>
        </transition>
        <transition name="slide-fade">
          <div v-if="flash.error" class="fixed top-20 right-4 bg-red-500 text-white px-6 py-3 rounded-2xl shadow-xl z-50 flex items-center gap-3 backdrop-blur-md bg-opacity-90 border border-red-400">
             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">{{ flash.error }}</span>
          </div>
        </transition>

        <div class="relative w-full mb-12 group">
          <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-600 rounded-[2.5rem] blur opacity-20 group-hover:opacity-40 transition duration-1000"></div>
          
          <div class="relative backdrop-blur-xl bg-white/70 dark:bg-gray-900/70 border border-white/20 shadow-xl rounded-[2rem] overflow-hidden">
             <div v-if="ranking.image" class="absolute inset-0 z-0">
               <img :src="ranking.image.startsWith('/storage/') ? ranking.image : `/storage/${ranking.image}`" class="w-full h-full object-cover opacity-10 blur-3xl scale-125" />
               <div class="absolute inset-0 bg-gradient-to-b from-white/80 via-white/90 to-white dark:from-gray-900/80 dark:via-gray-900/90 dark:to-gray-900"></div>
             </div>

             <div class="relative z-10 flex flex-col md:flex-row gap-0 md:gap-8">
                <div class="w-full md:w-[600px] aspect-video relative shrink-0">
                    <img
                      v-if="ranking.image && (ranking.is_approved || $page.props.auth?.user?.is_admin || $page.props.auth?.user?.id === ranking.user_id)"
                      :src="ranking.image.startsWith('/storage/') ? ranking.image : `/storage/${ranking.image}`"
                      alt="Ranking Cover"
                      class="w-full h-full object-cover md:rounded-l-[2rem]"
                    />
                    <div v-else class="w-full h-full flex flex-col items-center justify-center bg-gray-100 dark:bg-gray-800/50 md:rounded-l-[2rem]">
                      <span class="text-6xl mb-4 opacity-30">📊</span>
                    </div>
                </div>

                <div class="p-6 md:py-10 md:pr-10 flex flex-col justify-center flex-1">
                   <div class="flex items-start justify-between gap-4">
                      <div>
                          <h1 class="text-4xl md:text-6xl font-black text-gray-900 dark:text-white tracking-tighter leading-[0.95] mb-6 drop-shadow-sm">
                            {{ ranking.title }}
                          </h1>
                      </div>
                      <div class="hidden sm:flex flex-col items-center gap-1">
                          <div class="text-3xl font-black text-gray-900 dark:text-white">{{ ranking.options?.length || 0 }}</div>
                          <div class="text-[10px] uppercase font-bold text-gray-400">Opcions</div>
                      </div>
                   </div>

                   <p class="text-lg md:text-xl text-gray-600 dark:text-gray-300 leading-relaxed max-w-3xl font-medium mb-8">
                     {{ ranking.description }}
                   </p>

                   <div class="flex flex-wrap items-center gap-4 mt-auto">
                     
                      <button 
                        @click="toggleLike"
                        class="group/like relative overflow-hidden rounded-full px-8 py-3 bg-gray-100 dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-300 active:scale-95 flex items-center gap-3"
                        :class="{'!bg-red-500 !text-white ring-4 ring-red-200 dark:ring-red-900/40': ranking.user_has_liked}"
                      >
                          <svg :class="['w-6 h-6 transition-transform group-hover/like:scale-125', ranking.user_has_liked ? 'fill-current' : 'stroke-current stroke-2', { 'animate-like': isLikeAnimating }]" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"></path></svg>
                          <span class="font-bold text-lg">{{ ranking.likes_count }}</span>
                      </button>

                      <div class="w-px h-10 bg-gray-200 dark:bg-gray-700 mx-2 hidden sm:block"></div>

                      <div class="flex items-center gap-3">
                         <div class="w-12 h-12 rounded-full ring-2 ring-white dark:ring-gray-700 shadow-lg overflow-hidden">
                            <PixelAvatar v-if="isPixelAvatar(ranking.user?.profile_photo_path)" :id="ranking.user.profile_photo_path" className="w-full h-full" />
                            <img v-else-if="ranking.user?.profile_photo_url" :src="ranking.user.profile_photo_url" class="w-full h-full object-cover" />
                         </div>
                         <div class="flex flex-col gap-0.5">
                            <div class="flex items-center gap-2">
                               <span class="text-xs text-gray-400 font-bold uppercase leading-none">Autor</span>
                               <span v-if="ranking.user?.is_premium" class="px-1.5 py-[1px] rounded-sm text-[8px] font-black bg-gradient-to-r from-yellow-400 to-orange-500 text-white shadow-sm uppercase tracking-wider leading-none">PREMIUM</span>
                            </div>
                            <span class="font-bold text-gray-900 dark:text-white leading-tight">{{ ranking.user?.name || 'Anònim' }}</span>
                         </div>
                      </div>

                      <div class="ml-auto flex gap-3">
                         <button @click="shareRanking" class="w-12 h-12 rounded-full backdrop-blur-xl bg-white/70 dark:bg-gray-900/70 border border-white/20 shadow-xl flex items-center justify-center hover:bg-blue-50 dark:hover:bg-blue-900/30 text-gray-400 hover:text-blue-600 transition-all hover:scale-110 active:scale-90" title="Compartir">
                            <svg v-if="!copied" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                            <svg v-else class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                         </button>
                         <div class="w-12 h-12 rounded-full backdrop-blur-xl bg-white/70 dark:bg-gray-900/70 border border-white/20 shadow-xl flex items-center justify-center hover:scale-110 transition-transform">
                            <FavoriteStar :ranking="ranking" />
                         </div>
                         <button v-if="page.props.auth?.user && (ranking.user_id === page.props.auth.user.id || page.props.auth.user.is_admin)" @click="confirmDelete" class="w-12 h-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                         </button>
                      </div>
                   </div>
                </div>
             </div>
          </div>
        </div>

        <div class="mb-10 w-full flex justify-center">
            <AdBanner format="horizontal" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
          
          <div class="lg:col-span-8 space-y-8">
            <h2 class="text-3xl font-black text-gray-900 dark:text-white">
              Vota les Opcions
            </h2>

            <div class="space-y-6" v-auto-animate>
               <div 
                 v-for="opt in rankedOptions" 
                 :key="opt.id"
                 :class="[
                   'group relative flex flex-col sm:flex-row rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-300 overflow-visible bg-white dark:bg-gray-800',
                   getPodiumClasses(opt.rank, opt.votes_count).card
                 ]"
               >
                 <div 
                    :class="[
                      'absolute -top-4 -left-4 w-12 h-12 flex items-center justify-center rounded-xl font-black text-xl shadow-lg z-20 transform transition-transform group-hover:scale-110 group-hover:rotate-3',
                      getPodiumClasses(opt.rank, opt.votes_count).badge
                    ]"
                  >
                    #{{ opt.rank }}
                  </div>

                  <div class="w-full sm:w-56 sm:h-auto min-h-[200px] relative shrink-0 sm:rounded-l-xl overflow-hidden bg-gray-900">
                      <iframe 
                        v-if="opt.video_url"
                        :src="getYoutubeEmbedUrl(opt.video_url)"
                        class="w-full h-full absolute inset-0 z-10"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                      ></iframe>
                      <template v-else>
                         <img
                          v-if="opt.image && (opt.is_approved || $page.props.auth?.user?.is_admin || $page.props.auth?.user?.id === ranking.user_id)"
                          :src="opt.image.startsWith('/storage/') ? opt.image : `/storage/${opt.image}`"
                          alt="Option"
                          class="w-full h-full object-cover absolute inset-0 transition-transform duration-700 group-hover:scale-110 opacity-90 group-hover:opacity-100"
                        />
                        <div v-else class="w-full h-full flex flex-col items-center justify-center text-gray-500">
                          <span class="text-4xl mb-2">📷</span>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent sm:bg-gradient-to-r sm:from-transparent sm:to-black/10"></div>
                      </template>
                  </div>

                  <div class="flex-1 p-6 flex flex-col justify-center relative">
                      <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4 leading-tight">
                        {{ opt.name }}
                      </h3>

                      <div class="relative w-full h-3 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden mb-2">
                        <div 
                          class="absolute top-0 left-0 h-full bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-red-500 dark:to-orange-500 transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(59,130,246,0.5)] dark:shadow-[0_0_10px_rgba(239,68,68,0.5)]"
                          :style="{ width: getPercentage(opt.votes_count) + '%' }"
                        ></div>
                      </div>
                      
                      <div class="flex justify-between items-center text-sm font-medium text-gray-500 dark:text-gray-400 mb-6">
                        <span>{{ opt.votes_count }} vots</span>
                        <span>{{ getPercentage(opt.votes_count) }}%</span>
                      </div>

                      <div class="mt-auto">
                        <button
                          v-if="votedOptionId !== opt.id"
                          @click="vote(opt.id)"
                          class="w-full py-3 px-6 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.99] transition-all duration-200 flex items-center justify-center gap-2"
                        >
                          Votar aquesta
                        </button>
                        <template v-else>
                          <div class="flex gap-3">
                             <div class="flex-1 py-3 bg-green-500/10 border border-green-500 text-green-600 dark:text-green-400 font-bold rounded-xl text-center flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Votat
                             </div>
                             <button @click="unvoteRanking" class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-medium rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                Retirar
                             </button>
                          </div>
                        </template>
                      </div>
                  </div>
               </div>
            </div>

            <div class="mt-12">
               <AdBanner format="horizontal" />
            </div>
          </div>

          <div class="lg:col-span-4">
            <div class="sticky top-6 space-y-6">
               
               <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 overflow-hidden flex flex-col max-h-[calc(100vh-2rem)]">
                  <div class="p-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 backdrop-blur flex items-center justify-between">
                      <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span>💬</span> <span class="hidden sm:inline">Comentaris</span> <span class="text-sm font-normal text-gray-500">({{ comments?.length || 0 }})</span>
                      </h2>
                      
                      <div>
                          <select v-model="sort" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-blue-400 dark:hover:border-red-500 text-gray-700 dark:text-gray-300 py-1.5 pl-3 pr-8 rounded-lg text-xs font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer transition-all">
                            <option value="likes">Més populars</option>
                            <option value="recent">Més recents</option>
                            <option value="oldest">Més antics</option>
                         </select>
                      </div>
                  </div>

                  <div class="flex-1 overflow-y-auto p-5 space-y-5 custom-scroll" v-auto-animate>
                      
                      <div v-if="$page.props.auth?.user" class="mb-6 bg-blue-50 dark:bg-gray-800/50 p-3 rounded-xl">
                        <form @submit.prevent="submitComment">
                          <textarea 
                            ref="textareaRef" 
                            v-model="commentForm.content" 
                            placeholder="Digues la teva..." 
                            class="w-full bg-transparent border-0 focus:ring-0 text-gray-900 dark:text-gray-100 placeholder-gray-400 resize-none p-0 text-sm" 
                            rows="2" 
                            @input="autoResize($event)"
                          ></textarea>
                          <div class="flex justify-between items-center mt-2 pt-2 border-t border-blue-100 dark:border-gray-700">
                             <span :class="['text-xs', charCount < charLimit ? 'text-gray-400' : 'text-red-500 font-bold']">{{ charCount }}/{{ charLimit }}</span>
                             <button type="submit" :disabled="commentForm.processing || !commentForm.content" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 dark:bg-red-600 dark:hover:bg-red-700 text-white rounded-lg text-xs font-bold shadow-md transition disabled:opacity-50">Enviar</button>
                          </div>
                        </form>
                      </div>
                      <div v-else class="text-center p-4 bg-gray-50 dark:bg-gray-800 rounded-xl mb-4">
                        <a href="/login" class="text-blue-600 dark:text-red-400 font-bold hover:underline">Inicia sessió</a> <span class="text-sm text-gray-500">per comentar.</span>
                      </div>
                      <div v-if="loadingComments" class="space-y-4 animate-pulse">
                        <div v-for="i in 3" :key="i" class="flex gap-3">
                           <div class="w-8 h-8 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                           <div class="flex-1 space-y-2">
                              <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/3"></div>
                              <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-full"></div>
                           </div>
                        </div>
                      </div>

                      <template v-else-if="comments && comments.length > 0">
                        <div v-for="comment in comments" :key="comment.id" class="group">
                           <div class="flex gap-3">
                              <div class="shrink-0 mt-1">
                                 <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-800 ring-2 ring-gray-100 dark:ring-gray-700">
                                    <PixelAvatar v-if="isPixelAvatar(comment.user?.profile_photo_path)" :id="comment.user.profile_photo_path" className="w-full h-full" />
                                    <img v-else-if="comment.user?.profile_photo_url" :src="comment.user.profile_photo_url" class="w-full h-full object-cover" />
                                 </div>
                              </div>
                              <div class="flex-1 min-w-0">
                                 <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl rounded-tl-none p-3 px-4 relative group-hover:bg-gray-100 dark:group-hover:bg-gray-700/80 transition-colors">
                                    <div class="flex justify-between items-start mb-1">
                                       <div class="flex items-center gap-1.5 truncate">
                                          <span class="font-bold text-sm text-gray-900 dark:text-gray-200">{{ comment.user?.name }}</span>
                                          <span v-if="comment.user?.is_premium" class="text-[9px] bg-yellow-100 text-yellow-700 px-1 rounded font-bold border border-yellow-200">PRO</span>
                                       </div>
                                       <span class="text-[10px] text-gray-400">{{ new Date(comment.created_at).toLocaleDateString() }}</span>
                                    </div>
                                    
                                    <div class="comment-clamped text-sm text-gray-700 dark:text-gray-300 leading-relaxed" :style="!isExpanded(comment.id) ? { maxHeight: '6rem', overflow: 'hidden' } : { maxHeight: 'none' }">
                                       <div class="comment-content" :data-id="comment.id">{{ comment.content }}</div>
                                    </div>
                                    <button v-if="needsShowMore(comment.id)" @click="toggleExpanded(comment.id)" class="text-xs text-blue-500 font-semibold mt-1 hover:underline">
                                       {{ !isExpanded(comment.id) ? 'Llegir més' : 'Llegir menys' }}
                                    </button>
                                 </div>

                                 <div class="flex items-center gap-4 mt-1 ml-2">
                                    <button @click="voteComment(comment.id, 1)" :class="['flex items-center gap-1 text-xs font-medium transition', comment.user_vote === true ? 'text-green-600' : 'text-gray-400 hover:text-green-500']">
                                       <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v7.333z"></path></svg>
                                       {{ comment.likes_count ?? 0 }}
                                    </button>
                                    <button @click="voteComment(comment.id, 0)" :class="['flex items-center gap-1 text-xs font-medium transition', comment.user_vote === false ? 'text-red-600' : 'text-gray-400 hover:text-red-500']">
                                       <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M18 9.5a1.5 1.5 0 11-3 0v-6a1.5 1.5 0 013 0v6zM14 9.667v-5.43a2 2 0 00-1.105-1.79l-.05-.025A4 4 0 0011.055 2H5.64a2 2 0 00-1.962 1.608l-1.2 6A2 2 0 004.44 12H8v4a2 2 0 002 2 1 1 0 001-1v-7.333z"></path></svg>
                                       {{ comment.dislikes_count ?? 0 }}
                                    </button>
                                    <button v-if="page.props.auth?.user && (comment.user_id === page.props.auth.user.id || ranking.user_id === page.props.auth.user.id || page.props.auth.user.is_admin)" @click="deleteComment(comment.id)" class="text-xs text-gray-300 hover:text-red-500 transition">
                                       Eliminar
                                    </button>
                                 </div>
                              </div>
                           </div>
                        </div>
                      </template>
                      <div v-else class="text-center py-10">
                        <div class="text-4xl mb-2">🦗</div>
                        <p class="text-gray-500 text-sm">Encara no hi ha soroll per aquí...</p>
                      </div>
                  </div>
               </div>

               <div class="rounded-2xl overflow-hidden shadow-lg">
                 <AdBanner format="square" />
               </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="showAdOverlay" class="fixed inset-0 z-[100] bg-black flex flex-col items-center justify-center text-white">
      <div class="text-2xl font-bold mb-4">PUBLICITAT</div>
      
      <div class="text-6xl font-black mb-8 animate-pulse">{{ adCountdown }}</div>
      
      <p class="text-gray-400">Pots continuar votant en uns segons...</p>
      
      <p class="text-xs text-gray-500 mt-8">
        Fes-te <span class="text-yellow-500 font-bold">Premium</span> per saltar això.
      </p>
    </div>

  </AppLayout>
</template>