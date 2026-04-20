<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <Navbar title="Struk Pembelian">
      <template #right>
        <button
          @click="printReceipt"
          class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700"
        >
          <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
          </svg>
          Print
        </button>
        <button
          @click="backToPOS"
          class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300"
        >
          Kembali ke POS
        </button>
      </template>
    </Navbar>

    <!-- Receipt Content -->
    <div class="max-w-md mx-auto px-4 py-8">
      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
        <p class="mt-4 text-gray-600">Loading receipt...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4">
        <p class="text-red-800">{{ error }}</p>
        <button
          @click="backToPOS"
          class="mt-4 w-full bg-primary-600 text-white py-2 rounded-lg hover:bg-primary-700"
        >
          Kembali ke POS
        </button>
      </div>

      <!-- Receipt Preview -->
      <div v-else class="bg-white rounded-lg shadow-lg p-6">
        <div id="receipt-content" v-html="receiptHtml"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Navbar from '@/components/layout/Navbar.vue';
import api from '@/services/api';

const route = useRoute();
const router = useRouter();

const loading = ref(true);
const error = ref(null);
const receiptHtml = ref('');

const fetchReceiptData = async () => {
  try {
    loading.value = true;
    error.value = null;
    
    const transactionId = route.params.id;
    if (!transactionId) {
      throw new Error('Transaction ID not found');
    }

    const response = await api.getTransactionThermalData(transactionId);
    receiptHtml.value = response.data.html;
  } catch (err) {
    console.error('Failed to fetch receipt:', err);
    error.value = err.response?.data?.message || 'Failed to load receipt';
  } finally {
    loading.value = false;
  }
};

const printReceipt = () => {
  const printWindow = window.open('', '_blank', 'width=300,height=600');
  printWindow.document.write(`
    <!DOCTYPE html>
    <html>
      <head>
        <title>Print Receipt #${route.params.id}</title>
        <style>
          * { margin: 0; padding: 0; box-sizing: border-box; }
          body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 58mm;
            padding: 5mm;
          }
          .center { text-align: center; }
          .bold { font-weight: bold; }
          .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
          }
          table {
            width: 100%;
            margin: 5px 0;
          }
          td {
            padding: 2px 0;
          }
          td.right {
            text-align: right;
          }
        </style>
      </head>
      <body>
        ${receiptHtml.value}
      </body>
    </html>
  `);
  printWindow.document.close();
  printWindow.print();
};

const backToPOS = () => {
  router.push('/pos');
};

onMounted(() => {
  fetchReceiptData();
});
</script>

<style scoped>
#receipt-content {
  font-family: 'Courier New', monospace;
  font-size: 14px;
}

#receipt-content :deep(.center) {
  text-align: center;
}

#receipt-content :deep(.bold) {
  font-weight: bold;
}

#receipt-content :deep(.line) {
  border-top: 1px dashed #000;
  margin: 10px 0;
}

#receipt-content :deep(table) {
  width: 100%;
  margin: 10px 0;
}

#receipt-content :deep(td) {
  padding: 4px 0;
}

#receipt-content :deep(td.right) {
  text-align: right;
}
</style>
