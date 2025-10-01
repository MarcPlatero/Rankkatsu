<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const form = useForm({
  title: '',
  description: '',
  image: null,
  // mínim 2 opcions per defecte
  options: [
    { name: '', image: null },
    { name: '', image: null }
  ]
})

// Afegir una nova opció
const addOption = () => {
  form.options.push({ name: '', image: null })
}

// Eliminar opció (només si hi ha més de 2 opcions)
const removeOption = (index) => {
  if (form.options.length > 2) {
    form.options.splice(index, 1)
  }
}

// Handlers d'arxius
const handleRankingImage = (e) => {
  form.image = e.target.files[0] ?? null
}

const handleOptionImage = (e, index) => {
  form.options[index].image = e.target.files[0] ?? null
}

// Enviar formulari
const submit = () => {
  form.post(route('rankings.store'), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      // opcional: redirigir o mostrar notificació
      // router.visit(route('rankings.index'))
      form.reset('title', 'description', 'image', 'options')
    }
  })
}
</script>

<template>
  <AppLayout>
    <Head title="Crear Rànquing" />

    <div class="max-w-3xl mx-auto py-12 px-6">
      <h1 class="text-3xl font-bold mb-6 text-gray-800">Crear un nou rànquing</h1>

      <form @submit.prevent="submit" class="space-y-6">
        <!-- Títol -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Nom del rànquing</label>
          <input
            type="text"
            v-model="form.title"
            class="mt-1 block w-full rounded-md border-gray-300 p-2"
          />
          <div v-if="form.errors.title" class="text-red-600 text-sm mt-1">
            {{ form.errors.title }}
          </div>
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

        <!-- Imatge del rànquing -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Imatge del rànquing</label>
          <input
            type="file"
            accept="image/jpeg,image/png,image/webp"
            @change="handleRankingImage"
            class="mt-1 block w-full text-sm"
          />
          <div v-if="form.errors.image" class="text-red-600 text-sm mt-1">
            {{ form.errors.image }}
          </div>
        </div>

        <!-- Opcions -->
        <div>
          <h2 class="text-xl font-semibold text-gray-800 mb-4">Opcions del rànquing</h2>

          <div
            v-for="(option, index) in form.options"
            :key="index"
            class="mb-4 p-4 border rounded-lg bg-gray-50"
          >
            <div class="flex items-center gap-4">
              <input
                type="text"
                v-model="form.options[index].name"
                placeholder="Nom de l’opció"
                class="flex-1 rounded-md border-gray-300 p-2"
              />
              <input
                type="file"
                accept="image/jpeg,image/png,image/webp"
                @change="(e) => handleOptionImage(e, index)"
                class="text-sm"
              />
              <button
                type="button"
                @click="removeOption(index)"
                class="px-3 py-2 bg-red-500 text-white rounded-md"
                v-if="form.options.length > 2"
              >
                -
              </button>
            </div>

            <!-- Error específic per aquesta opció -->
            <div v-if="form.errors[`options.${index}.name`]" class="text-red-600 text-sm mt-1">
              {{ form.errors[`options.${index}.name`] }}
            </div>
            <div v-if="form.errors[`options.${index}.image`]" class="text-red-600 text-sm mt-1">
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

        <!-- Enviar -->
        <div>
          <button
            type="submit"
            class="w-full px-6 py-3 bg-blue-600 text-white text-lg rounded-lg"
            :disabled="form.processing"
          >
            Crear Rànquing
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
