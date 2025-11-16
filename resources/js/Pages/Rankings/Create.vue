<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import * as nsfwjs from 'nsfwjs'
import * as tf from '@tensorflow/tfjs'
import { ref, onMounted, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import axios from 'axios'
import { Cropper } from 'vue-advanced-cropper'
import 'vue-advanced-cropper/dist/style.css'

const form = useForm({
  title: '',
  description: '',
  image: null,
  options: [
    { name: '', image: null },
    { name: '', image: null }
  ]
})

const rankingFile = ref(null)
const optionFiles = ref(form.options.map(() => null))
const imagePreview = ref(null)
const optionPreviews = ref([])
const nsfwError = ref('')
const nsfwLoading = ref(true)
const processing = ref(false)
const rankingSuspicious = ref(false)
const optionSuspicious = ref([])
optionSuspicious.value = form.options.map(() => false)

let model = null

const isCropperOpen = ref(false)
const cropperSrc = ref(null)
const cropperAspectRatio = ref(16 / 9)
const editingImageIndex = ref(null)
const cropperRef = ref(null)

onMounted(async () => {
  try {
    await tf.ready()
    model = await nsfwjs.load()
  } catch (err) {
    console.error('Error carregant model NSFW:', err)
    nsfwError.value = 'No s’ha pogut carregar el filtre d’imatges.'
  } finally {
    nsfwLoading.value = false
  }
})

const openCropper = (file, ratio, index) => {
  if (!file || !file.type.startsWith('image/')) return
  
  nsfwError.value = ''
  
  cropperSrc.value = URL.createObjectURL(file)
  cropperAspectRatio.value = ratio
  editingImageIndex.value = index
  isCropperOpen.value = true
}

const cancelCrop = () => {
  isCropperOpen.value = false
  cropperSrc.value = null
}

const saveCrop = async () => {
  if (!cropperRef.value) return
  
  const { canvas } = cropperRef.value.getResult()
  if (!canvas) return
  
  canvas.toBlob(async (blob) => {
    
    if (nsfwLoading.value) {
      nsfwError.value = 'El filtre d\'imatges encara s\'està carregant.'
      return
    }
    const { ok, suspicious } = await validateImage(blob)
    if (!ok) {
      nsfwError.value = 'Aquesta imatge (o una part) sembla inapropiada.'
      cancelCrop()
      return
    }

    const previewUrl = URL.createObjectURL(blob)
    const index = editingImageIndex.value
    
    if (index === null) {
      rankingFile.value = blob
      imagePreview.value = previewUrl
      rankingSuspicious.value = suspicious
    } else {
      optionFiles.value[index] = blob
      optionPreviews.value[index] = previewUrl
      optionSuspicious.value[index] = suspicious
    }
    
    cancelCrop()

  }, 'image/jpeg', 0.9)
}

const handleRankingImage = (e) => {
  const file = e.target.files && e.target.files[0]
  openCropper(file, 16 / 9, null)
  e.target.value = null
}

const onDropRanking = (e) => {
  const file = e.dataTransfer.files && e.dataTransfer.files[0]
  openCropper(file, 16 / 9, null)
}

const handleOptionImage = (e, index) => {
  const file = e.target.files && e.target.files[0]
  openCropper(file, 1 / 1, index)
  e.target.value = null
}

const onDropOption = (e, index) => {
  const file = e.dataTransfer.files && e.dataTransfer.files[0]
  openCropper(file, 1 / 1, index)
}

const validateImage = async (fileOrBlob) => {
  try {
    if (!model) {
      await tf.ready()
      model = await nsfwjs.load()
    }
    const img = document.createElement('img')
    img.src = URL.createObjectURL(fileOrBlob)
    await new Promise(resolve => img.onload = resolve)
    const predictions = await model.classify(img)
    let maxPorn = 0
    for (const p of predictions) {
      if (['Hentai', 'Porn', 'Sexy'].includes(p.className)) {
        maxPorn = Math.max(maxPorn, p.probability)
      }
    }
    URL.revokeObjectURL(img.src)
    if (maxPorn > 0.85) return { ok: false, suspicious: true }
    if (maxPorn > 0.6) return { ok: true, suspicious: true }
    return { ok: true, suspicious: false }
  } catch (err) {
    console.error('Error validateImage', err)
    return { ok: true, suspicious: false }
  }
}

const addOption = () => {
  form.options.push({ name: '', image: null })
  optionFiles.value.push(null)
  optionPreviews.value.push(null)
  optionSuspicious.value.push(false)
}
const removeOption = (index) => {
  if (form.options.length > 2) {
    form.options.splice(index, 1)
    optionFiles.value.splice(index, 1)
    optionPreviews.value.splice(index, 1)
    optionSuspicious.value.splice(index, 1)
  }
}

const titleMax = 60
const descMax = 400
const optMax = 25

const limitInput = (val, max) => (val.length > max ? val.slice(0, max) : val)

watch(() => form.title, val => form.title = limitInput(val, titleMax))
watch(() => form.description, val => form.description = limitInput(val, descMax))
watch(() => form.options, newOptions => {
  newOptions.forEach((opt, i) => {
    if (opt.name.length > optMax) form.options[i].name = opt.name.slice(0, optMax)
  })
}, { deep: true })

const submit = async () => {
  if (nsfwError.value) {
    alert('Hi ha imatges inapropiades. Revisa-les abans de continuar.')
    return
  }
  processing.value = true
  form.errors = {}
  try {
    const fd = new FormData()
    fd.append('title', form.title)
    fd.append('description', form.description ?? '')
    if (rankingFile.value) {
      fd.append('image', rankingFile.value)
      fd.append('image_is_suspicious', rankingSuspicious.value ? '1' : '0')
    }
    form.options.forEach((opt, i) => {
      fd.append(`options[${i}][name]`, opt.name ?? '')
      fd.append(`options[${i}][is_suspicious]`, optionSuspicious.value[i] ? '1' : '0')
    })
    optionFiles.value.forEach((file, i) => {
      if (file) fd.append(`options[${i}][image]`, file)
    })
    await axios.post(route('rankings.store'), fd, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
      },
    })
    window.location.href = route('rankings.index')
  } catch (err) {
    if (err.response?.status === 422) {
      const errors = err.response.data.errors || {}
      for (const key in errors) {
        form.errors[key] = Array.isArray(errors[key]) ? errors[key][0] : errors[key]
      }
    } else {
      console.error('Error enviant el formulari:', err)
      alert('Error en crear el rànquing.')
    }
  } finally {
    processing.value = false
  }
}
</script>

<style scoped>
@keyframes gradient-slow {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
.animate-gradient-slow {
  animation: gradient-slow 8s ease infinite;
  background-size: 200% 200%;
}
textarea { resize: none; }
.red-count { color: #ef4444; transition: color 0.3s ease; }
.file-drop-zone {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 1.5rem;
  border: 2px dashed #9ca3af;
  border-radius: 0.5rem;
  background-color: rgba(255, 255, 255, 0.5);
  cursor: pointer;
  transition: all 0.2s ease;
}
.dark .file-drop-zone {
  background-color: rgba(55, 65, 81, 0.5);
  border-color: #4b5563;
}
.file-drop-zone:hover {
  border-color: #3b82f6;
  background-color: rgba(255, 255, 255, 0.8);
}
.dark .file-drop-zone:hover {
  border-color: #ef4444;
  background-color: rgba(55, 65, 81, 0.8);
}
.cropper-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}
.cropper-container {
  width: 90%;
  max-width: 600px;
  height: 70vh;
  max-height: 500px;
  background: white;
  border-radius: 0.5rem;
  overflow: hidden;
}
.cropper-buttons {
  display: flex;
  gap: 1rem;
  margin-top: 1rem;
}
</style>

<template>
  <AppLayout>
    <Head title="Crear Rànquing" />

    <section class="relative py-16 px-6 overflow-hidden transition-colors duration-500">
      <div
        class="absolute inset-0 bg-gradient-to-br from-blue-200 via-white to-red-100
               dark:from-gray-800 dark:via-gray-900 dark:to-black
               animate-gradient-slow">
      </div>

      <div class="relative z-10 max-w-4xl mx-auto bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-2xl rounded-2xl p-10 border border-gray-200 dark:border-gray-700">
        <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100 text-center">
          🏆 Crear un nou rànquing
        </h1>

        <div
          v-if="nsfwError"
          class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300 p-3 rounded-lg mb-6">
          {{ nsfwError }}
        </div>

        <form @submit.prevent="submit" class="space-y-8">
          
          <div class="grid grid-cols-1 md:grid-cols-[1.5fr_1fr] gap-8">
            
            <div class="space-y-6">
              <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                  Nom del rànquing
                </label>
                <input
                  id="title"
                  v-model="form.title"
                  type="text"
                  :maxlength="titleMax"
                  class="mt-1 block w-full rounded-lg border p-2.5 focus:ring-2 focus:ring-blue-500 transition-colors border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                />
                <div class="text-xs mt-1 text-right" :class="form.title.length >= titleMax ? 'red-count' : 'text-gray-500'">
                  {{ form.title.length }}/{{ titleMax }}
                </div>
                <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">
                  {{ form.errors.title }}
                </div>
              </div>

              <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                  Descripció
                </label>
                <textarea
                  id="description"
                  v-model="form.description"
                  rows="4" 
                  :maxlength="descMax"
                  class="mt-1 block w-full rounded-lg border p-2.5 focus:ring-2 focus:ring-blue-500 transition-colors border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </textarea>
                <div class="text-xs mt-1 text-right" :class="form.description.length >= descMax ? 'red-count' : 'text-gray-500'">
                  {{ form.description.length }}/{{ descMax }}
                </div>
                <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">
                  {{ form.errors.description }}
                </div>
              </div>
            </div>

            <div class="flex flex-col items-center">
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                Imatge del rànquing (16:9)
              </label>

              <label 
                class="w-full max-w-xs"
                @dragover.prevent
                @drop.prevent="onDropRanking"
              >
                <div v-if="!imagePreview" class="file-drop-zone h-36">
                  <svg class="w-10 h-10 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                  <span class="mt-2 text-sm text-gray-600 dark:text-gray-300 text-center">Clica o arrossega (16:9)</span>
                </div>
                <div v-else class="mt-2 relative w-full max-w-xs aspect-video cursor-pointer">
                  <img :src="imagePreview" alt="Preview" class="w-full h-full object-cover rounded-lg shadow" />
                  <div class="absolute inset-0 rounded-lg bg-black/50 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                    <span class="text-white font-semibold">Canviar imatge</span>
                  </div>
                </div>
                <input
                  type="file"
                  accept="image/*"
                  @change="handleRankingImage"
                  class="hidden"
                />
              </label>
            </div>
          </div>
          
          <hr class="border-gray-300 dark:border-gray-600">

          <div>
            <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">
              Opcions del rànquing (Quadrat 1:1)
            </h2>

            <div class="space-y-4" v-auto-animate>
              <div
                v-for="(option, index) in form.options"
                :key="index"
                class="p-4 border rounded-lg bg-gray-50 dark:bg-gray-700/70 transition-colors border-gray-300 dark:border-gray-600"
              >
                <div class="grid grid-cols-[80px_1fr_auto] items-start gap-4">
                  
                  <label
                    @dragover.prevent
                    @drop.prevent="(e) => onDropOption(e, index)"
                  >
                    <div v-if="!optionPreviews[index]" class="file-drop-zone w-20 h-20 !p-0">
                      <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    </div>
                    <div v-else class="relative w-20 h-20 cursor-pointer">
                      <img :src="optionPreviews[index]" alt="Preview" class="w-full h-full object-cover rounded-lg shadow" />
                      <div class="absolute inset-0 rounded-lg bg-black/50 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                        <span class="text-white text-xs text-center">Canviar</span>
                      </div>
                    </div>
                    <input type="file" accept="image/*" @change="(e) => handleOptionImage(e, index)" class="hidden" />
                  </label>

                  <div class="min-w-0">
                    <input
                      v-model="form.options[index].name"
                      type="text"
                      :maxlength="optMax"
                      placeholder="Nom de l’opció"
                      class="w-full rounded-md p-2.5 border focus:ring-2 focus:ring-blue-500 transition-colors border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                    />
                    <div class="flex justify-between items-center">
                      <div v-if="form.errors[`options.${index}.name`]" class="text-red-500 text-sm mt-1">
                        {{ form.errors[`options.${index}.name`] }}
                      </div>
                      <div class="flex-grow text-xs mt-1 text-right" :class="option.name.length >= optMax ? 'red-count' : 'text-gray-500'">
                        {{ option.name.length }}/{{ optMax }}
                      </div>
                    </div>
                  </div>

                  <button
                    v-if="form.options.length > 2"
                    type="button"
                    @click="removeOption(index)"
                    class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-all duration-300 h-11"
                  >
                    🗑️
                  </button>
                </div>
              </div>
            </div>

            <button
              type="button"
              @click="addOption"
              class="mt-4 px-4 py-2 bg-blue-600 dark:bg-red-600 text-white rounded-lg hover:bg-blue-700 dark:hover:bg-red-700 shadow-md transition-all duration-300">
              + Afegir opció
            </button>
          </div>

          <div>
            <button
              type="submit"
              class="w-full px-6 py-3 rounded-lg text-lg font-semibold text-white shadow-lg relative overflow-hidden group transition-all duration-300"
              :disabled="processing || nsfwLoading">
              <span
                class="absolute inset-0 bg-gradient-to-r from-blue-600 via-red-500 to-blue-600 animate-gradient-slow">
              </span>
              <span class="relative z-10">
                <span v-if="nsfwLoading">Carregant filtre...</span>
                <span v-else-if="processing">Creant…</span>
                <span v-else>Crear Rànquing</span>
              </span>
            </button>
          </div>
        </form>
      </div>
    </section>
    
    <div v-if="isCropperOpen" class="cropper-modal">
      <div class="cropper-container">
        <Cropper
          ref="cropperRef"
          :src="cropperSrc"
          :stencil-props="{
            aspectRatio: cropperAspectRatio
          }"
        />
      </div>
      <div class="cropper-buttons">
        <button @click="cancelCrop" type="button" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
          Cancel·lar
        </button>
        <button @click="saveCrop" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
          Acceptar i retallar
        </button>
      </div>
    </div>
    </AppLayout>
</template>