<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const form = useForm({
  title: '',
  description: '',
  image: null,
  options: [
    { name: '', image: null }
  ]
})

// Afegir una nova opció
const addOption = () => {
  form.options.push({ name: '', image: null })
}

// Eliminar opció
const removeOption = (index) => {
  if (form.options.length > 1) {
    form.options.splice(index, 1)
  }
}

// Pujar imatge del rànquing
const handleRankingImage = (e) => {
  form.image = e.target.files[0]
}

// Pujar imatge d’una opció
const handleOptionImage = (e, index) => {
  form.options[index].image = e.target.files[0]
}

// Enviar formulari
const submit = () => {
  const data = new FormData()
  data.append('title', form.title)
  data.append('description', form.description)
  if (form.image) {
    data.append('image', form.image)
  }

  form.options.forEach((option, i) => {
    data.append(`options[${i}][name]`, option.name)
    if (option.image) {
      data.append(`options[${i}][image]`, option.image)
    }
  })

  form.post(route('rankings.store'), {
    forceFormData: true,
    onSuccess: () => form.reset(),
  })
}
</script>

<template>
  <Head title="Crear Rànquing" />

  <div class="max-w-3xl mx-auto py-12 px-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Crear un nou rànquing</h1>

    <form @submit.prevent="submit" class="space-y-6">
      <!-- Nom del rànquing -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Nom del rànquing</label>
        <input
          type="text"
          v-model="form.title"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
          required
        />
      </div>

      <!-- Descripció -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Descripció</label>
        <textarea
          v-model="form.description"
          rows="3"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
        ></textarea>
      </div>

      <!-- Imatge del rànquing -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Imatge del rànquing</label>
        <input
          type="file"
          accept="image/*"
          @change="handleRankingImage"
          class="mt-1 block w-full text-sm text-gray-600"
        />
      </div>

      <!-- Opcions -->
      <div>
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Opcions del rànquing</h2>

        <div v-for="(option, index) in form.options" :key="index" class="mb-4 p-4 border rounded-lg bg-gray-50">
          <div class="flex items-center gap-4">
            <!-- Nom -->
            <input
              type="text"
              v-model="option.name"
              placeholder="Nom de l’opció/personatge/cosa"
              class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
              required
            />

            <!-- Imatge -->
            <input
              type="file"
              accept="image/*"
              @change="(e) => handleOptionImage(e, index)"
              class="text-sm text-gray-600"
            />

            <!-- Botó eliminar -->
            <button
              type="button"
              @click="removeOption(index)"
              class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition"
              v-if="form.options.length > 1"
            >
              -
            </button>
          </div>
        </div>

        <!-- Botó afegir opció -->
        <button
          type="button"
          @click="addOption"
          class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition"
        >
          + Afegir opció
        </button>
      </div>

      <!-- Botó crear -->
      <div>
        <button
          type="submit"
          class="w-full px-6 py-3 bg-blue-600 text-white text-lg font-semibold rounded-lg shadow hover:bg-blue-700 transition disabled:opacity-50"
          :disabled="form.processing"
        >
          Crear Rànquing
        </button>
      </div>
    </form>
  </div>
</template>
