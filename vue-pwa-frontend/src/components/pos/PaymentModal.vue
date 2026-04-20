<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
    @click.self="$emit('close')"
  >
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
      <!-- Header -->
      <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center">
        <h3 class="text-xl font-bold">Payment</h3>
        <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Content -->
      <div class="p-6 space-y-6">
        <!-- Total Amount -->
        <div class="bg-gray-50 rounded-lg p-4">
          <p class="text-sm text-gray-600">Total Amount</p>
          <p class="text-2xl font-bold text-gray-900">Rp {{ formatCurrency(total) }}</p>
        </div>

        <!-- Payment Method Selection -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
          <div class="grid grid-cols-2 gap-2">
            <button
              @click="paymentMethod = 'cash'"
              :class="[
                'py-3 px-4 rounded-lg border-2 font-medium transition',
                paymentMethod === 'cash' 
                  ? 'border-primary-600 bg-primary-50 text-primary-700' 
                  : 'border-gray-300 hover:border-gray-400'
              ]"
            >
              Cash
            </button>
            <button
              @click="paymentMethod = 'transfer'"
              :class="[
                'py-3 px-4 rounded-lg border-2 font-medium transition',
                paymentMethod === 'transfer' 
                  ? 'border-primary-600 bg-primary-50 text-primary-700' 
                  : 'border-gray-300 hover:border-gray-400'
              ]"
            >
              Transfer
            </button>
            <button
              @click="paymentMethod = 'qris'"
              :class="[
                'py-3 px-4 rounded-lg border-2 font-medium transition',
                paymentMethod === 'qris' 
                  ? 'border-primary-600 bg-primary-50 text-primary-700' 
                  : 'border-gray-300 hover:border-gray-400'
              ]"
            >
              QRIS
            </button>
            <button
              @click="paymentMethod = 'ewallet'"
              :class="[
                'py-3 px-4 rounded-lg border-2 font-medium transition',
                paymentMethod === 'ewallet' 
                  ? 'border-primary-600 bg-primary-50 text-primary-700' 
                  : 'border-gray-300 hover:border-gray-400'
              ]"
            >
              E-Wallet
            </button>
          </div>
        </div>

        <!-- Cash Payment -->
        <div v-if="paymentMethod === 'cash'" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Amount Paid</label>
            <input
              v-model.number="amountPaid"
              type="number"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
              placeholder="Enter amount"
            />
          </div>
          <div v-if="amountPaid >= total" class="bg-green-50 rounded-lg p-4">
            <p class="text-sm text-gray-600">Change</p>
            <p class="text-xl font-bold text-green-700">Rp {{ formatCurrency(amountPaid - total) }}</p>
          </div>
        </div>

        <!-- Bank Transfer -->
        <div v-if="paymentMethod === 'transfer'">
          <label class="block text-sm font-medium text-gray-700 mb-1">Select Bank</label>
          <select
            v-model="selectedBank"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
          >
            <option value="">Choose bank...</option>
            <option v-for="bank in banks" :key="bank.id" :value="bank.id">
              {{ bank.nama_bank }}
            </option>
          </select>
        </div>

        <!-- E-Wallet -->
        <div v-if="paymentMethod === 'ewallet'">
          <label class="block text-sm font-medium text-gray-700 mb-1">Select E-Wallet</label>
          <select
            v-model="selectedEwallet"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
          >
            <option value="">Choose e-wallet...</option>
            <option v-for="ewallet in ewalets" :key="ewallet.id" :value="ewallet.id">
              {{ ewallet.nama_ewalet }}
            </option>
          </select>
        </div>

        <!-- QRIS -->
        <div v-if="paymentMethod === 'qris'" class="bg-blue-50 rounded-lg p-4">
          <div class="flex items-start space-x-3">
            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
            </svg>
            <div>
              <p class="font-medium text-blue-900">QRIS Payment</p>
              <p class="text-sm text-blue-700 mt-1">Customer will scan QR code to complete payment</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="sticky bottom-0 bg-gray-50 px-6 py-4 flex space-x-3">
        <button
          @click="$emit('close')"
          class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 font-medium"
        >
          Cancel
        </button>
        <button
          @click="handleConfirm"
          :disabled="!isValid || loading"
          class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {{ loading ? 'Processing...' : 'Confirm Payment' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  show: Boolean,
  total: Number,
  banks: Array,
  ewalets: Array,
  loading: Boolean
});

const emit = defineEmits(['close', 'confirm']);

const paymentMethod = ref('cash');
const amountPaid = ref(0);
const selectedBank = ref('');
const selectedEwallet = ref('');

// Debug: Log banks and ewalets when they change
watch(() => props.banks, (newBanks) => {
  console.log('💳 Banks data:', newBanks);
}, { immediate: true });

watch(() => props.ewalets, (newEwalets) => {
  console.log('💰 E-Wallets data:', newEwalets);
}, { immediate: true });

const isValid = computed(() => {
  if (paymentMethod.value === 'cash') {
    return amountPaid.value >= props.total;
  } else if (paymentMethod.value === 'transfer') {
    return selectedBank.value !== '';
  } else if (paymentMethod.value === 'ewallet') {
    return selectedEwallet.value !== '';
  } else if (paymentMethod.value === 'qris') {
    return true; // QRIS doesn't require additional selection
  }
  return false;
});

const handleConfirm = () => {
  const paymentData = {
    payment_method: paymentMethod.value,
    amount_paid: paymentMethod.value === 'cash' ? amountPaid.value : props.total,
    bank_id: paymentMethod.value === 'transfer' ? selectedBank.value : null,
    ewalet_id: paymentMethod.value === 'ewallet' ? selectedEwallet.value : null
  };
  
  emit('confirm', paymentData);
};

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID').format(amount);
};
</script>
