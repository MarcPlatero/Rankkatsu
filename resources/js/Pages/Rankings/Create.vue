<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const title = ref('')
const description = ref('')
const image = ref(null)

const options = ref([
  { name: '', image: null } // sempre comença amb 1 opció
])

const addOption = () => {
  options.value.push({ name: '', image: null })
}

const removeOption = (index) => {
  if (options.value.length > 1) {
    options.value.splice(index, 1)
  }
}

const submit = () => {
  const formData = new FormData()
  formData.append('title', title.value)
  formData.append('description', description.value)
  if (image.value) formData.append('image', image.value)

  options.value.forEach((opt, i) => {
    formData.append(`options[${i}][name]`, opt.name)
    if (opt.image) {
      formData.append(`options[${i}][image]`, opt.image)
    }
  })

  router.post('/rankings', formData)
}
</script>

<template>
  <div class="max-w-3xl mx-auto p-6 bg-white shadow rounded">
    <h1 class="text-2xl font-bold mb-4">Crear un nou Ranking</h1>

    <!-- Títol -->
    <input v-model="title" type="text" placeholder="Nom del rànquing"
           class="w-full border rounded p-2 mb-4" />

    <!-- Descripció -->
    <textarea v-model="description" placeholder="Descripció del rànquing"
              class="w-full border rounded p-2 mb-4"></textarea>

    <!-- Imatge -->
    <input type="file" @change="e => image.value = e.target.files[0]" class="mb-6" />

    <h2 class="text-xl font-semibold mb-2">Opcions</h2>

    <div v-for="(opt, i) in options" :key="i" class="flex items-center gap-4 mb-2">
      <input v-model="opt.name" type="text" placeholder="Nom de l'opció"
             class="flex-1 border rounded p-2" />
      <input type="file" @change="e => opt.image = e.target.files[0]" />
      <button type="button" @click="removeOption(i)" class="text-red-500">-</button>
    </div>

    <button type="button" @click="addOption" class="text-blue-600 mb-6">+ Afegir opció</button>

    <button @click="submit"
            class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
      Crear Ranking
    </button>
  </div>
</template>
