<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import * as nsfwjs from 'nsfwjs'
import * as tf from '@tensorflow/tfjs'
import { ref, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import axios from 'axios'

// Formulari base
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

let model = null

// Carrega del model NSFW
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

// Validació NSFW
const validateImage = async (file) => {
  try {
    if (!model) return true
    const img = document.createElement('img')
    img.src = URL.createObjectURL(file)
    await new Promise((resolve) => (img.onload = resolve))
    const predictions = await model.classify(img)
    return !predictions.some(
      (p) =>
        ['Hentai', 'Porn', 'Sexy'].includes(p.className) &&
        p.probability > 0.7
    )
  } catch {
    return true // Permet si hi ha error
  }
}

// Afegir o eliminar opcions
const addOption = () => {
  form.options.push({ name: '', image: null })
  optionFiles.value.push(null)
  optionPreviews.value.push(null)
}
const removeOption = (index) => {
  if (form.options.length > 2) {
    form.options.splice(index, 1)
    optionFiles.value.splice(index, 1)
    optionPreviews.value.splice(index, 1)
  }
}

// Gestors d’imatges
const handleRankingImage = async (e) => {
  const file = e.target.files?.[0]
  if (!file) return
  nsfwError.value = ''
  const ok = await validateImage(file)
  if (!ok) {
    nsfwError.value = 'Aquesta imatge sembla inapropiada.'
    rankingFile.value = null
    imagePreview.value = null
  } else {
    rankingFile.value = file
    imagePreview.value = URL.createObjectURL(file)
  }
}

const handleOptionImage = async (e, index) => {
  const file = e.target.files?.[0]
  if (!file) return
  nsfwError.value = ''
  const ok = await validateImage(file)
  if (!ok) {
    nsfwError.value = `La imatge de l'opció ${index + 1} sembla inapropiada.`
    optionFiles.value[index] = null
    optionPreviews.value[index] = null
  } else {
    optionFiles.value[index] = file
    optionPreviews.value[index] = URL.createObjectURL(file)
  }
}

// Enviament
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
    if (rankingFile.value) fd.append('image', rankingFile.value)

    form.options.forEach((opt, i) => {
      fd.append(`options[${i}][name]`, opt.name ?? '')
      if (optionFiles.value[i])
        fd.append(`options[${i}][image]`, optionFiles.value[i])
    })

    await axios.post(route('rankings.store'), fd, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document
          .querySelector('meta[name="csrf-token"]')
          ?.getAttribute('content'),
      },
    })

    window.location.href = route('rankings.index')
  } catch (err) {
    if (err.response?.status === 422) {
      const errors = err.response.data.errors || {}
      for (const key in errors) {
        form.errors[key] = Array.isArray(errors[key])
          ? errors[key][0]
          : errors[key]
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

<template>
  <AppLayout>
    <Head title="Crear Rànquing" />

    <div v-if="nsfwLoading" class="text-gray-600 mb-4">
      Carregant filtre de seguretat d’imatges…
    </div>

    <div class="max-w-3xl mx-auto py-12 px-6">
      <h1 class="text-3xl font-bold mb-6">Crear un nou rànquing</h1>

      <form @submit.prevent="submit" class="space-y-6">
        <!-- Títol -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Nom del rànquing</label>
          <input
            v-model="form.title"
            type="text"
            class="mt-1 block w-full rounded-md border-gray-300 p-2"
          />
          <div v-if="form.errors.title" class="text-red-600 text-sm mt-1">
            {{ form.errors.title }}
          </div>
        </div>

        <!-- Error NSFW -->
        <div v-if="nsfwError" class="bg-red-100 text-red-700 p-3 rounded-md">
          {{ nsfwError }}
        </div>

        <!-- Descripció -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Descripció</label>
          <textarea
            v-model="form.description"
            rows="3"
            class="mt-1 block w-full rounded-md border-gray-300 p-2"
          ></textarea>
          <div v-if="form.errors.description" class="text-red-600 text-sm mt-1">
            {{ form.errors.description }}
          </div>
        </div>

        <!-- Imatge principal -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Imatge del rànquing</label>
          <input
            type="file"
            accept="image/*"
            @change="handleRankingImage"
            class="mt-1 block w-full text-sm"
          />
          <div v-if="imagePreview" class="mt-2">
            <img :src="imagePreview" alt="Preview" class="w-48 rounded-lg shadow" />
          </div>
          <div v-if="form.errors.image" class="text-red-600 text-sm mt-1">
            {{ form.errors.image }}
          </div>
        </div>

        <!-- Opcions -->
        <div>
          <h2 class="text-xl font-semibold mb-4">Opcions del rànquing</h2>
          <div
            v-for="(option, index) in form.options"
            :key="index"
            class="mb-4 p-4 border rounded-lg bg-gray-50"
          >
            <div class="flex items-center gap-4 flex-wrap">
              <input
                v-model="form.options[index].name"
                type="text"
                placeholder="Nom de l’opció"
                class="flex-1 rounded-md border-gray-300 p-2"
              />
              <input
                type="file"
                accept="image/*"
                @change="(e) => handleOptionImage(e, index)"
                class="text-sm"
              />
              <div v-if="optionPreviews[index]" class="mt-2">
                <img :src="optionPreviews[index]" alt="Preview" class="w-32 rounded-lg shadow" />
              </div>
              <button
                v-if="form.options.length > 2"
                type="button"
                @click="removeOption(index)"
                class="px-3 py-2 bg-red-500 text-white rounded-md"
              >
                -
              </button>
            </div>

            <div
              v-if="form.errors[`options.${index}.name`]"
              class="text-red-600 text-sm mt-1"
            >
              {{ form.errors[`options.${index}.name`] }}
            </div>
            <div
              v-if="form.errors[`options.${index}.image`]"
              class="text-red-600 text-sm mt-1"
            >
              {{ form.errors[`options.${index}.image`] }}
            </div>
          </div>

          <button
            type="button"
            @click="addOption"
            class="px-4 py-2 bg-green-500 text-white rounded-md"
          >
            + Afegir opció
          </button>
        </div>

        <!-- Submit -->
        <div>
          <button
            type="submit"
            class="w-full px-6 py-3 bg-blue-600 text-white text-lg rounded-lg"
            :disabled="processing"
          >
            <span v-if="!processing">Crear Rànquing</span>
            <span v-else>Creant…</span>
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
