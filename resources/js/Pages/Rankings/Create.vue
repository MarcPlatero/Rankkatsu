<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import * as nsfwjs from 'nsfwjs'
import * as tf from '@tensorflow/tfjs'
import { ref, onMounted, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import axios from 'axios'

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

const validateImage = async (file) => {
  try {
    if (!model) {
      await tf.ready()
      model = await nsfwjs.load()
    }
    const img = document.createElement('img')
    img.src = URL.createObjectURL(file)
    await new Promise(resolve => img.onload = resolve)
    const predictions = await model.classify(img)
    let maxPorn = 0
    for (const p of predictions) {
      if (['Hentai', 'Porn', 'Sexy'].includes(p.className)) {
        maxPorn = Math.max(maxPorn, p.probability)
      }
    }
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

const handleRankingImage = async (e) => {
  const file = e.target.files && e.target.files[0]
  if (!file) return
  nsfwError.value = ''
  const { ok, suspicious } = await validateImage(file)
  if (!ok) {
    nsfwError.value = 'Aquesta imatge sembla inapropiada. Tria una altra.'
    form.image = null
    imagePreview.value = null
    rankingFile.value = null
    rankingSuspicious.value = false
  } else {
    form.image = file
    imagePreview.value = URL.createObjectURL(file)
    rankingFile.value = file
    rankingSuspicious.value = !!suspicious
  }
}

const handleOptionImage = async (e, index) => {
  const file = e.target.files && e.target.files[0]
  if (!file) return
  nsfwError.value = ''
  const { ok, suspicious } = await validateImage(file)
  if (!ok) {
    nsfwError.value = `La imatge de l'opció ${index + 1} sembla inapropiada.`
    form.options[index].image = null
    optionPreviews.value[index] = null
    optionFiles.value[index] = null
    optionSuspicious.value[index] = false
  } else {
    form.options[index].image = file
    optionPreviews.value[index] = URL.createObjectURL(file)
    optionFiles.value[index] = file
    optionSuspicious.value[index] = !!suspicious
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

        <form @submit.prevent="submit" class="space-y-6">
          <!-- Títol -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
              Nom del rànquing
            </label>
            <input
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

          <!-- Error NSFW -->
          <div
            v-if="nsfwError"
            class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300 p-3 rounded-lg">
            {{ nsfwError }}
          </div>

          <!-- Descripció -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
              Descripció
            </label>
            <textarea
              v-model="form.description"
              rows="3"
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

          <!-- Imatge principal -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
              Imatge del rànquing
            </label>
            <input
              type="file"
              accept="image/*"
              @change="handleRankingImage"
              class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-200"
            />
            <div v-if="imagePreview" class="mt-3">
              <img :src="imagePreview" alt="Preview" class="w-48 rounded-lg shadow" />
            </div>
          </div>

          <!-- Opcions -->
          <div>
            <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">
              Opcions del rànquing
            </h2>

            <div
              v-for="(option, index) in form.options"
              :key="index"
              class="mb-4 p-4 border rounded-lg bg-gray-50 dark:bg-gray-700/70 transition-colors border-gray-300 dark:border-gray-600">
              <div class="flex items-center gap-4 flex-wrap">
                <input
                  v-model="form.options[index].name"
                  type="text"
                  :maxlength="optMax"
                  placeholder="Nom de l’opció"
                  class="flex-1 rounded-md p-2.5 border focus:ring-2 focus:ring-blue-500 transition-colors border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                />
                <input
                  type="file"
                  accept="image/*"
                  @change="(e) => handleOptionImage(e, index)"
                  class="text-sm text-gray-700 dark:text-gray-200"
                />
                <div v-if="optionPreviews[index]" class="mt-2">
                  <img :src="optionPreviews[index]" alt="Preview" class="w-32 rounded-lg shadow" />
                </div>
                <button
                  v-if="form.options.length > 2"
                  type="button"
                  @click="removeOption(index)"
                  class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-all duration-300">
                  -
                </button>
              </div>
              <div class="text-xs mt-1 text-right" :class="option.name.length >= optMax ? 'red-count' : 'text-gray-500'">
                {{ option.name.length }}/{{ optMax }}
              </div>
              <div v-if="form.errors[`options.${index}.name`]" class="text-red-500 text-sm mt-1">
                {{ form.errors[`options.${index}.name`] }}
              </div>
            </div>

            <button
              type="button"
              @click="addOption"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md transition-all duration-300">
              + Afegir opció
            </button>
          </div>

          <!-- Submit -->
          <div>
            <button
              type="submit"
              class="w-full px-6 py-3 rounded-lg text-lg font-semibold text-white shadow-lg relative overflow-hidden group transition-all duration-300"
              :disabled="processing">
              <span
                class="absolute inset-0 bg-gradient-to-r from-blue-600 via-red-500 to-blue-600 animate-gradient-slow">
              </span>
              <span class="relative z-10">
                <span v-if="!processing">Crear Rànquing</span>
                <span v-else>Creant…</span>
              </span>
            </button>
          </div>
        </form>
      </div>
    </section>
  </AppLayout>
</template>
