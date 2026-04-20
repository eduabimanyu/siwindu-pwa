<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <Navbar title="Riwayat Transaksi" titleClass="text-gray-900" showBack @back="goToDashboard" />

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <!-- Filters -->
      <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <!-- Search -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Cari Transaksi</label>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari nomor transaksi atau nama pelanggan..."
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            />
          </div>

          <!-- Date Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
            <input
              v-model="filterDate"
              type="date"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            />
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
        <p class="mt-4 text-gray-600">Loading transactions...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4">
        <p class="text-red-800">{{ error }}</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="filteredTransactions.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <p class="mt-4 text-gray-600">Tidak ada transaksi ditemukan</p>
      </div>

      <!-- Transaction List -->
      <div v-else class="space-y-4">
        <div
          v-for="transaction in paginatedTransactions"
          :key="transaction.id_transaksi"
          class="bg-white rounded-lg shadow-sm hover:shadow-md transition p-4"
        >
          <div class="flex items-center justify-between">
            <div class="flex-1">
              <div class="flex items-center space-x-2 mb-2">
                <span class="text-sm font-semibold text-gray-900">#{{ transaction.id_transaksi }}</span>
                <span
                  :class="[
                    'px-2 py-1 text-xs rounded-full',
                    transaction.status === 'Selesai' 
                      ? 'bg-green-500 text-white' 
                      : 'bg-yellow-500 text-white'
                  ]"
                >
                  {{ transaction.status }}
                </span>
              </div>
              
              <div class="grid grid-cols-2 gap-2 text-sm">
                <div>
                  <span class="text-gray-500">Tanggal:</span>
                  <span class="ml-2 text-gray-900">{{ formatDate(transaction.created_at) }}</span>
                </div>
                <div>
                  <span class="text-gray-500">Waktu:</span>
                  <span class="ml-2 text-gray-900">{{ formatTime(transaction.created_at) }}</span>
                </div>
                <div>
                  <span class="text-gray-500">Total:</span>
                  <span class="ml-2 font-semibold text-primary-600">Rp {{ formatCurrency(transaction.total_harga) }}</span>
                </div>
                <div>
                  <span class="text-gray-500">Pembayaran:</span>
                  <span class="ml-2 text-gray-900">{{ transaction.jenis_pembayaran }}</span>
                </div>
                <div v-if="transaction.nama_pelanggan" class="col-span-2">
                  <span class="text-gray-500">Pelanggan:</span>
                  <span class="ml-2 text-gray-900">{{ transaction.nama_pelanggan }}</span>
                </div>
              </div>
            </div>

            <div class="flex flex-row space-x-2 ml-4">
              <button
                @click="showDetail(transaction.id_transaksi)"
                class="p-2 bg-yellow-400 text-gray-800 rounded-lg hover:bg-yellow-500 transition"
                title="Detail"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
              <button
                @click="printReceipt(transaction.id_transaksi)"
                class="p-2 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition"
                title="Print"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination Controls -->
      <div v-if="totalPages > 1" class="flex justify-between items-center mt-6 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <button 
          @click="currentPage > 1 ? currentPage-- : null"
          :disabled="currentPage === 1"
          class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg disabled:opacity-50 hover:bg-gray-50 text-gray-700"
        >
          Previous
        </button>
        <div class="text-sm text-gray-600">
          Page <span class="font-semibold text-gray-900">{{ currentPage }}</span> of <span class="font-semibold text-gray-900">{{ totalPages }}</span>
        </div>
        <button 
          @click="currentPage < totalPages ? currentPage++ : null"
          :disabled="currentPage === totalPages"
          class="px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg disabled:opacity-50 hover:bg-gray-50 text-gray-700"
        >
          Next
        </button>
      </div>
    </div>

    <!-- Detail Modal -->
    <div
      v-if="showDetailModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="closeDetailModal"
    >
      <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Loading -->
        <div v-if="loadingDetail" class="p-8 text-center">
          <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
          <p class="mt-4 text-gray-600">Loading detail...</p>
        </div>

        <!-- Detail Content -->
        <div v-else-if="selectedTransaction" class="p-6">
          <!-- Header -->
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Detail Transaksi</h3>
            <button
              @click="closeDetailModal"
              class="text-gray-400 hover:text-gray-600"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Transaction Info -->
          <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-2 gap-4 text-sm">
              <div>
                <span class="text-gray-500">No. Transaksi:</span>
                <span class="ml-2 font-semibold">#{{ selectedTransaction.id_transaksi }}</span>
              </div>
              <div>
                <span class="text-gray-500">Status:</span>
                <span
                  :class="[
                    'ml-2 px-2 py-1 text-xs rounded-full',
                    selectedTransaction.status === 'Selesai' 
                      ? 'bg-green-100 text-green-800' 
                      : 'bg-yellow-100 text-yellow-800'
                  ]"
                >
                  {{ selectedTransaction.status }}
                </span>
              </div>
              <div>
                <span class="text-gray-500">Tanggal:</span>
                <span class="ml-2">{{ formatDate(selectedTransaction.created_at) }}</span>
              </div>
              <div>
                <span class="text-gray-500">Waktu:</span>
                <span class="ml-2">{{ formatTime(selectedTransaction.created_at) }}</span>
              </div>
              <div>
                <span class="text-gray-500">Pembayaran:</span>
                <span class="ml-2">{{ selectedTransaction.jenis_pembayaran }}</span>
              </div>
              <div v-if="selectedTransaction.nama_pelanggan">
                <span class="text-gray-500">Pelanggan:</span>
                <span class="ml-2">{{ selectedTransaction.nama_pelanggan }}</span>
              </div>
            </div>
          </div>

          <!-- Items -->
          <div class="mb-6">
            <h4 class="font-semibold text-gray-900 mb-3">Items</h4>
            <div class="space-y-2">
              <div
                v-for="item in transactionItems"
                :key="item.id"
                class="flex items-center justify-between py-2 border-b border-gray-200"
              >
                <div class="flex-1">
                  <p class="font-medium text-gray-900">{{ item.nama_item }}</p>
                  <p class="text-sm text-gray-500">Rp {{ formatCurrency(item.harga) }} x {{ item.jumlah }}</p>
                </div>
                <p class="font-semibold text-gray-900">Rp {{ formatCurrency(item.subtotal) }}</p>
              </div>
            </div>
          </div>

          <!-- Total -->
          <div class="border-t border-gray-200 pt-4">
            <div class="flex items-center justify-between text-lg font-bold">
              <span>TOTAL</span>
              <span class="text-primary-600">Rp {{ formatCurrency(selectedTransaction.total_harga) }}</span>
            </div>
          </div>

          <!-- Actions -->
          <div class="mt-6 flex space-x-3">
            <button
              @click="printReceipt(selectedTransaction.id_transaksi)"
              class="flex-1 bg-primary-600 text-white py-2 rounded-lg hover:bg-primary-700"
            >
              Print Struk
            </button>
            <button
              @click="closeDetailModal"
              class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300"
            >
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import Navbar from '@/components/layout/Navbar.vue';
import api from '@/services/api';

const router = useRouter();

const transactions = ref([]);
const loading = ref(true);
const error = ref(null);
const searchQuery = ref('');
const filterDate = ref('');

// Pagination
const currentPage = ref(1);
const itemsPerPage = ref(10);

// Detail Modal
const showDetailModal = ref(false);
const loadingDetail = ref(false);
const selectedTransaction = ref(null);
const transactionItems = ref([]);

const filteredTransactions = computed(() => {
  let filtered = transactions.value;

  // Search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(t => 
      t.id_transaksi.toString().includes(query) ||
      (t.nama_pelanggan && t.nama_pelanggan.toLowerCase().includes(query))
    );
  }

  // Date filter
  if (filterDate.value) {
    filtered = filtered.filter(t => {
      const transDate = new Date(t.created_at).toISOString().split('T')[0];
      return transDate === filterDate.value;
    });
  }

  return filtered;
});

// Calculate total pages based on filtered results
const totalPages = computed(() => {
  return Math.max(1, Math.ceil(filteredTransactions.value.length / itemsPerPage.value));
});

// Get transactions for current page
const paginatedTransactions = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredTransactions.value.slice(start, end);
});

// Reset to page 1 when filters change
watch([searchQuery, filterDate], () => {
  currentPage.value = 1;
});

const fetchTransactions = async () => {
  try {
    loading.value = true;
    error.value = null;
    
    const response = await api.getTransactions();
    transactions.value = response.data;
  } catch (err) {
    console.error('Failed to fetch transactions:', err);
    error.value = err.response?.data?.message || 'Failed to load transactions';
  } finally {
    loading.value = false;
  }
};

const showDetail = async (id) => {
  try {
    showDetailModal.value = true;
    loadingDetail.value = true;
    
    const response = await api.getTransactionDetail(id);
    selectedTransaction.value = response.data.transaction;
    transactionItems.value = response.data.items;
  } catch (err) {
    console.error('Failed to fetch transaction detail:', err);
    error.value = 'Failed to load transaction detail';
  } finally {
    loadingDetail.value = false;
  }
};

const closeDetailModal = () => {
  showDetailModal.value = false;
  selectedTransaction.value = null;
  transactionItems.value = [];
};

const printReceipt = (id) => {
  router.push(`/receipt/${id}`);
};

const goToDashboard = () => {
  router.push('/dashboard');
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('id-ID', { 
    day: '2-digit', 
    month: '2-digit', 
    year: 'numeric' 
  });
};

const formatTime = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleTimeString('id-ID', { 
    hour: '2-digit', 
    minute: '2-digit' 
  });
};

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID').format(amount);
};

onMounted(() => {
  fetchTransactions();
});
</script>
