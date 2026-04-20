<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-gray-900 flex items-center">
          <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>
          Printer Bluetooth
        </h3>
        <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Browser Support Warning -->
      <div v-if="!isSupported" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
        Browser ini tidak mendukung Web Bluetooth API. Harap gunakan Google Chrome di Android atau PC.
      </div>

      <div v-else class="space-y-6">
        <!-- Status -->
        <div class="bg-gray-50 rounded-lg p-4 border" :class="printerStore.isConnected ? 'border-green-200' : 'border-gray-200'">
          <p class="text-sm text-gray-500 mb-1">Status Koneksi</p>
          <div class="flex items-center">
            <span class="w-3 h-3 rounded-full mr-2" :class="printerStore.isConnected ? 'bg-green-500' : 'bg-red-500'"></span>
            <span class="font-medium text-gray-900">
              {{ printerStore.isConnected ? 'Terkoneksi' : 'Terputus' }}
            </span>
          </div>
          <p v-if="printerStore.isConnected" class="mt-2 text-sm text-gray-700 font-medium bg-white p-2 rounded border">
            🖨️ {{ printerStore.deviceName }}
          </p>
        </div>

        <!-- Error Alert -->
        <div v-if="printerStore.error" class="bg-red-50 text-red-600 px-4 py-3 rounded-lg text-sm border border-red-200">
          {{ printerStore.error }}
        </div>

        <!-- Actions -->
        <div class="space-y-3">
          <button
            v-if="!printerStore.isConnected"
            @click="handleConnect"
            :disabled="printerStore.isConnecting"
            class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
          >
            <svg v-if="printerStore.isConnecting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span v-else>Cari & Hubungkan Printer</span>
            <span v-if="printerStore.isConnecting">Menghubungkan...</span>
          </button>

          <template v-else>
            <button
              @click="handleTestPrint"
              :disabled="isPrinting"
              class="w-full flex justify-center items-center py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
            >
              <span v-if="isPrinting">Mencetak...</span>
              <span v-else>Print Test Page</span>
            </button>

            <button
              @click="handleDisconnect"
              class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
            >
              Putuskan Koneksi
            </button>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePrinterStore } from '@/stores/printer';

const props = defineProps({
  show: Boolean
});

const emit = defineEmits(['close']);
const printerStore = usePrinterStore();
const isPrinting = ref(false);

const isSupported = computed(() => {
  return !!navigator.bluetooth;
});

const handleConnect = async () => {
  await printerStore.connect();
};

const handleDisconnect = () => {
  printerStore.disconnect();
};

const handleTestPrint = async () => {
  isPrinting.value = true;
  await printerStore.testPrint();
  isPrinting.value = false;
};
</script>
