<template>
  <div class="bg-white rounded-lg shadow p-4">
    <h3 class="text-lg font-semibold mb-4">Shopping Cart ({{ posStore.cartItemCount }})</h3>
    
    <!-- Empty Cart -->
    <div v-if="posStore.cart.length === 0" class="text-center py-8 text-gray-500">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
      </svg>
      <p class="mt-2">Cart is empty</p>
    </div>

    <!-- Cart Items -->
    <div v-else class="space-y-3">
      <div
        v-for="item in posStore.cart"
        :key="item.id"
        class="flex items-center justify-between border-b pb-3"
      >
        <div class="flex-1">
          <p class="font-medium">{{ item.name }}</p>
          <p class="text-sm text-gray-600">Rp {{ formatCurrency(item.price) }}</p>
        </div>
        
        <div class="flex items-center space-x-2">
          <!-- Decrease -->
          <button
            @click="posStore.updateQuantity(item.id, item.quantity - 1)"
            class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>
          
          <!-- Quantity -->
          <span class="w-8 text-center font-semibold">{{ item.quantity }}</span>
          
          <!-- Increase -->
          <button
            @click="posStore.updateQuantity(item.id, item.quantity + 1)"
            class="w-8 h-8 rounded-full bg-primary-600 hover:bg-primary-700 text-white flex items-center justify-center"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
          </button>
          
          <!-- Remove -->
          <button
            @click="posStore.removeFromCart(item.id)"
            class="ml-2 text-red-600 hover:text-red-800"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Total -->
      <div class="pt-4 border-t-2">
        <div class="flex justify-between text-lg font-bold">
          <span>Total:</span>
          <span>Rp {{ formatCurrency(posStore.cartTotal) }}</span>
        </div>
      </div>

      <!-- Customer Name (Optional) -->
      <div class="pt-3">
        <label for="customerName" class="block text-sm font-medium text-gray-700 mb-1">
          Nama Pelanggan <span class="text-gray-400 text-xs">(opsional)</span>
        </label>
        <input
          id="customerName"
          v-model="posStore.customerName"
          type="text"
          placeholder="Masukkan nama pelanggan"
          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
        />
      </div>

      <!-- Checkout Button -->
      <button
        @click="$emit('checkout')"
        class="w-full mt-4 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-lg transition"
      >
        Checkout
      </button>
    </div>
  </div>
</template>

<script setup>
import { usePOSStore } from '@/stores/pos';

const posStore = usePOSStore();

defineEmits(['checkout']);

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID').format(amount);
};
</script>
