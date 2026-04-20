<template>
  <div
    @click="$emit('select', item)"
    class="bg-white rounded-lg shadow hover:shadow-lg transition cursor-pointer p-4"
  >
    <div class="flex flex-col h-full">
      <!-- Item Name -->
      <h3 class="font-semibold text-gray-900 mb-1">{{ item.nama_item }}</h3>
      
      <!-- Category -->
      <p class="text-xs text-gray-500 mb-2">{{ item.kategori?.nama_kategori || 'Uncategorized' }}</p>
      
      <!-- Price -->
      <div class="mt-auto">
        <p class="text-lg font-bold text-primary-600">
          Rp {{ formatCurrency(item.harga) }}
        </p>
      </div>
      
      <!-- Stock indicator (optional) -->
      <div v-if="item.stok !== undefined" class="mt-2">
        <span
          :class="[
            'text-xs px-2 py-1 rounded',
            item.stok > 10 ? 'bg-green-100 text-green-800' : 
            item.stok > 0 ? 'bg-yellow-100 text-yellow-800' : 
            'bg-red-100 text-red-800'
          ]"
        >
          Stock: {{ item.stok }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  item: {
    type: Object,
    required: true
  }
});

defineEmits(['select']);

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID').format(amount);
};
</script>
