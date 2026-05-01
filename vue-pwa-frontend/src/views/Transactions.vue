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

          <!-- Wisata Filter (Manager/Admin only) -->
          <div v-if="isManagerOrAdmin" class="md:col-span-3">
            <label class="block text-sm font-medium text-gray-700 mb-1">
              <span class="flex items-center gap-1">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Filter Lokasi Wisata
              </span>
            </label>
            <div class="flex gap-2 flex-wrap">
              <button
                @click="filterWisata = null"
                :class="[
                  'px-3 py-1.5 rounded-lg text-sm font-medium border transition',
                  !filterWisata
                    ? 'bg-primary-600 text-white border-primary-600'
                    : 'bg-white text-gray-600 border-gray-300 hover:border-primary-400'
                ]"
              >
                Semua Lokasi
              </button>
              <button
                v-for="w in wisataList"
                :key="w.id"
                @click="filterWisata = w.id"
                :class="[
                  'px-3 py-1.5 rounded-lg text-sm font-medium border transition',
                  filterWisata === w.id
                    ? 'bg-primary-600 text-white border-primary-600'
                    : 'bg-white text-gray-600 border-gray-300 hover:border-primary-400'
                ]"
              >
                {{ w.nama_wisata }}
              </button>
            </div>
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
          <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0">
              <!-- Header: ID + Badges -->
              <div class="flex items-center flex-wrap gap-2 mb-3">
                <span class="text-sm font-bold text-gray-900">#{{ transaction.id_transaksi }}</span>
                <span
                  :class="[
                    'px-2 py-0.5 text-xs font-medium rounded-full',
                    transaction.status === 'Selesai' 
                      ? 'bg-green-500 text-white border' 
                      : 'bg-yellow-400 text-yellow-700 border border-yellow-300'
                  ]"
                >
                  {{ transaction.status }}
                </span>
                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-700 border border-blue-300">
                  {{ transaction.jenis_pembayaran }}
                </span>
              </div>

              <!-- Info Grid -->
              <div class="space-y-1 text-sm">
                <div class="flex items-center gap-1">
                  <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <span class="text-gray-500">{{ formatDate(transaction.created_at) }}</span>
                  <span class="text-gray-400">·</span>
                  <span class="text-gray-500">{{ formatTime(transaction.created_at) }}</span>
                </div>
                <div class="flex items-center gap-1">
                  <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="font-semibold text-gray-900">Rp {{ formatCurrency(transaction.total_harga) }}</span>
                </div>
                <div v-if="transaction.nama_pelanggan" class="flex items-center gap-1">
                  <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                  <span class="text-gray-600 truncate">{{ transaction.nama_pelanggan }}</span>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col gap-2 ml-3 flex-shrink-0">
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
      class="fixed inset-0 bg-black bg-opacity-50 flex items-end sm:items-center justify-center z-50 p-0 sm:p-4"
      @click.self="closeDetailModal"
    >
      <div class="bg-white rounded-t-2xl sm:rounded-2xl shadow-xl w-full sm:max-w-lg max-h-[92vh] flex flex-col">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 flex-shrink-0">
          <div>
            <h3 class="text-base font-bold text-gray-900">Detail Transaksi</h3>
            <p class="text-xs text-gray-400 mt-0.5">#{{ selectedTransaction?.id_transaksi }}</p>
          </div>
          <button
            @click="closeDetailModal"
            class="p-2 rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Scrollable Content -->
        <div class="overflow-y-auto flex-1">

          <!-- Loading -->
          <div v-if="loadingDetail" class="p-8 text-center">
            <div class="inline-block animate-spin rounded-full h-10 w-10 border-b-2 border-primary-600"></div>
            <p class="mt-3 text-sm text-gray-500">Memuat data...</p>
          </div>

          <div v-else-if="selectedTransaction">

            <!-- Status & Payment Badges -->
            <div class="px-5 pt-4 pb-3 flex items-center gap-2 flex-wrap">
              <span
                :class="[
                  'px-3 py-1 text-xs font-semibold rounded-full',
                  selectedTransaction.status === 'Selesai'
                    ? 'bg-green-100 text-green-700'
                    : 'bg-yellow-100 text-yellow-700'
                ]"
              >
                {{ selectedTransaction.status }}
              </span>
              <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                {{ selectedTransaction.jenis_pembayaran }}
              </span>
            </div>

            <!-- Transaction Info -->
            <div class="px-5 pb-4 space-y-3 text-sm">
              <div class="flex items-center justify-between py-2.5 border-b border-gray-100">
                <span class="text-gray-500 flex items-center gap-2">
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  Tanggal & Waktu
                </span>
                <span class="font-medium text-gray-800">
                  {{ formatDate(selectedTransaction.created_at) }} · {{ formatTime(selectedTransaction.created_at) }}
                </span>
              </div>
              <div v-if="selectedTransaction.nama_pelanggan" class="flex items-center justify-between py-2.5 border-b border-gray-100">
                <span class="text-gray-500 flex items-center gap-2">
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                  Pelanggan
                </span>
                <span class="font-medium text-gray-800">{{ selectedTransaction.nama_pelanggan }}</span>
              </div>
            </div>

            <!-- Items Section -->
            <div class="px-5 pb-3">
              <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Item Pembelian</h4>
              <div class="space-y-0 divide-y divide-gray-100">
                <div
                  v-for="item in transactionItems"
                  :key="item.id_transaksi_detail || item.id"
                  class="flex items-start justify-between py-3"
                >
                  <div class="flex-1 pr-4">
                    <p class="font-medium text-gray-900 text-sm leading-snug">
                      {{ item.item?.nama_item || item.nama_item || 'Item #' + item.id_item }}
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ item.jumlah }} × Rp {{ formatCurrency(item.harga) }}</p>
                  </div>
                  <p class="text-sm font-semibold text-gray-900 whitespace-nowrap">Rp {{ formatCurrency(item.subtotal) }}</p>
                </div>
              </div>
            </div>

            <!-- Total -->
            <div class="mx-5 mb-5 bg-gray-50 rounded-xl px-4 py-3 flex items-center justify-between">
              <span class="text-sm font-bold text-gray-700">TOTAL</span>
              <span class="text-lg font-bold text-primary-600">Rp {{ formatCurrency(selectedTransaction.total_harga) }}</span>
            </div>
          </div>
        </div>

        <!-- Action Buttons (sticky at bottom) -->
        <div class="px-5 py-4 border-t border-gray-100 flex gap-3 flex-shrink-0">
          <button
            @click="printReceipt(selectedTransaction.id_transaksi)"
            class="flex-1 flex items-center justify-center gap-2 bg-primary-600 text-white py-2.5 rounded-xl hover:bg-primary-700 font-medium text-sm transition"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print Struk
          </button>
          <button
            @click="closeDetailModal"
            class="flex-1 bg-gray-100 text-gray-700 py-2.5 rounded-xl hover:bg-gray-200 font-medium text-sm transition"
          >
            Tutup
          </button>
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
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const authStore = useAuthStore();

// Role check
const isManagerOrAdmin = computed(() => authStore.hasRole(['Manager', 'Admin', 'Keuangan']));

const transactions = ref([]);
const loading = ref(true);
const error = ref(null);
const searchQuery = ref('');
const filterDate = ref('');
const filterWisata = ref(null);
const wisataList = ref([]);

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
watch([searchQuery, filterDate, filterWisata], () => {
  currentPage.value = 1;
});

// Re-fetch when wisata filter changes (backend filter)
watch(filterWisata, () => {
  fetchTransactions();
});

const fetchTransactions = async () => {
  try {
    loading.value = true;
    error.value = null;
    
    const params = {};
    if (filterWisata.value) params.wisata = filterWisata.value;

    const response = await api.getTransactions(params);
    transactions.value = response.data;
  } catch (err) {
    console.error('Failed to fetch transactions:', err);
    error.value = err.response?.data?.message || 'Failed to load transactions';
  } finally {
    loading.value = false;
  }
};

const fetchWisata = async () => {
  try {
    const response = await api.getWisata();
    wisataList.value = response.data;
  } catch (err) {
    console.error('Failed to fetch wisata list:', err);
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
  if (isManagerOrAdmin.value) {
    fetchWisata();
  }
});
</script>
