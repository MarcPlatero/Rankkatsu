<template>
  <div 
    v-if="show" 
    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
  >
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6">
      <h2 class="text-lg font-semibold mb-4">{{ title }}</h2>
      <p class="text-gray-600 mb-6">{{ message }}</p>

      <div class="flex justify-end space-x-3">
        <button
          @click="$emit('cancel')"
          class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-gray-700"
        >
          Cancel·lar
        </button>
        <button
          @click="$emit('confirm')"
          class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-white"
        >
          Confirmar
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  show: Boolean,
  title: { type: String, default: "Confirmació" },
  message: { type: String, default: "Estàs segur de voler fer aquesta acció?" }
})
defineEmits(["confirm", "cancel"])
</script>
