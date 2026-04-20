<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <Navbar title="Laporan Penjualan" showBack @back="goToDashboard" />

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <!-- Date Filter -->
      <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <!-- Wisata Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Wisata</label>
            <select
              v-model="filters.wisata"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            >
              <option value="">Semua Wisata</option>
              <option v-for="w in wisataList" :key="w.id" :value="w.id">
                {{ w.nama_wisata }}
              </option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
            <input
              v-model="filters.startDate"
              type="date"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
            <input
              v-model="filters.endDate"
              type="date"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            />
          </div>
          <div class="flex items-end">
            <button
              @click="applyFilters"
              class="w-full px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium"
            >
              Terapkan Filter
            </button>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
        <p class="mt-4 text-gray-600">Loading reports...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4">
        <p class="text-red-800">{{ error }}</p>
      </div>

      <!-- Reports Content -->
      <div v-else>
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
          <!-- Total Revenue -->
          <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 mb-1">Total Pendapatan</p>
                <p class="text-2xl font-bold text-gray-900">{{ formatCurrency(stats.totalRevenue) }}</p>
              </div>
              <div class="bg-green-100 rounded-full p-3">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
          </div>

          <!-- Total Transactions -->
          <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 mb-1">Total Transaksi</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.totalTransactions }}</p>
              </div>
              <div class="bg-blue-100 rounded-full p-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
              </div>
            </div>
          </div>

          <!-- Total Items Sold -->
          <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 mb-1">Total Item Terjual</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.totalItems }}</p>
              </div>
              <div class="bg-purple-100 rounded-full p-3">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
              </div>
            </div>
          </div>

          <!-- Average per Transaction -->
          <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 mb-1">Rata-rata/Transaksi</p>
                <p class="text-2xl font-bold text-gray-900">{{ formatCurrency(stats.averagePerTransaction) }}</p>
              </div>
              <div class="bg-orange-100 rounded-full p-3">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Payment Breakdown -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Rincian Pembayaran</h3>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="border border-gray-200 rounded-lg p-4">
              <p class="text-sm text-gray-600 mb-1">Tunai</p>
              <p class="text-xl font-bold text-gray-900">{{ formatCurrency(stats.paymentBreakdown.tunai) }}</p>
              <div class="mt-2 bg-green-200 rounded-full h-2">
                <div 
                  class="bg-green-600 h-2 rounded-full" 
                  :style="{ width: getPaymentPercentage('tunai') + '%' }"
                ></div>
              </div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
              <p class="text-sm text-gray-600 mb-1">Transfer Bank</p>
              <p class="text-xl font-bold text-gray-900">{{ formatCurrency(stats.paymentBreakdown.transfer) }}</p>
              <div class="mt-2 bg-blue-200 rounded-full h-2">
                <div 
                  class="bg-blue-600 h-2 rounded-full" 
                  :style="{ width: getPaymentPercentage('transfer') + '%' }"
                ></div>
              </div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
              <p class="text-sm text-gray-600 mb-1">QRIS</p>
              <p class="text-xl font-bold text-gray-900">{{ formatCurrency(stats.paymentBreakdown.qris) }}</p>
              <div class="mt-2 bg-purple-200 rounded-full h-2">
                <div 
                  class="bg-purple-600 h-2 rounded-full" 
                  :style="{ width: getPaymentPercentage('qris') + '%' }"
                ></div>
              </div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
              <p class="text-sm text-gray-600 mb-1">E-Wallet</p>
              <p class="text-xl font-bold text-gray-900">{{ formatCurrency(stats.paymentBreakdown.ewallet) }}</p>
              <div class="mt-2 bg-orange-200 rounded-full h-2">
                <div 
                  class="bg-orange-600 h-2 rounded-full" 
                  :style="{ width: getPaymentPercentage('ewallet') + '%' }"
                ></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-lg shadow-sm p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Produk Terlaris</h3>
          
          <div v-if="stats.topProducts.length === 0" class="text-center py-8 text-gray-500">
            <p>Belum ada data produk</p>
          </div>

          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Produk
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Jumlah Terjual
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Total Pendapatan
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(product, index) in stats.topProducts" :key="index">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10 bg-primary-100 rounded-full flex items-center justify-center">
                        <span class="text-primary-600 font-semibold">{{ index + 1 }}</span>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ product.quantity }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-semibold text-gray-900">{{ formatCurrency(product.revenue) }}</div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Navbar from '@/components/layout/Navbar.vue';
import api from '@/services/api';

const router = useRouter();
const authStore = useAuthStore();

const loading = ref(true);
const error = ref(null);
const wisataList = ref([]);
const filters = ref({
  wisata: '',
  startDate: '',
  endDate: ''
});

const stats = ref({
  totalRevenue: 0,
  totalTransactions: 0,
  totalItems: 0,
  averagePerTransaction: 0,
  paymentBreakdown: {
    tunai: 0,
    transfer: 0,
    qris: 0,
    ewallet: 0
  },
  topProducts: []
});

const transactions = ref([]);

const fetchReports = async () => {
  try {
    loading.value = true;
    error.value = null;

    // Fetch all transactions
    const response = await api.getTransactions();
    let allTransactions = response.data;
    
    console.log('📊 Raw transactions:', allTransactions);
    console.log('📊 Total transactions fetched:', allTransactions.length);

    // Apply wisata filter
    if (filters.value.wisata) {
      allTransactions = allTransactions.filter(t => t.wisata == filters.value.wisata);
      console.log('📊 After wisata filter:', allTransactions.length);
    }

    // Apply date filters if set
    if (filters.value.startDate || filters.value.endDate) {
      allTransactions = allTransactions.filter(t => {
        const transDate = new Date(t.created_at).toISOString().split('T')[0];
        
        if (filters.value.startDate && transDate < filters.value.startDate) {
          return false;
        }
        if (filters.value.endDate && transDate > filters.value.endDate) {
          return false;
        }
        return true;
      });
      console.log('📊 After date filter:', allTransactions.length);
    }

    transactions.value = allTransactions;

    // Calculate statistics
    calculateStats();
  } catch (err) {
    console.error('Failed to fetch reports:', err);
    error.value = err.response?.data?.message || 'Failed to load reports';
  } finally {
    loading.value = false;
  }
};

const calculateStats = () => {
  const trans = transactions.value;

  // Total revenue
  stats.value.totalRevenue = trans.reduce((sum, t) => sum + parseFloat(t.total_harga || 0), 0);

  // Total transactions
  stats.value.totalTransactions = trans.length;

  // Total items
  stats.value.totalItems = trans.reduce((sum, t) => sum + parseInt(t.total_item || 0), 0);

  // Average per transaction
  stats.value.averagePerTransaction = stats.value.totalTransactions > 0 
    ? stats.value.totalRevenue / stats.value.totalTransactions 
    : 0;

  // Payment breakdown
  const paymentBreakdown = {
    tunai: 0,
    transfer: 0,
    qris: 0,
    ewallet: 0
  };

  trans.forEach(t => {
    const amount = parseFloat(t.total_harga || 0);
    const method = t.jenis_pembayaran?.toLowerCase() || '';

    if (method.includes('tunai')) {
      paymentBreakdown.tunai += amount;
    } else if (method.includes('transfer') || method.includes('bank')) {
      paymentBreakdown.transfer += amount;
    } else if (method.includes('qris')) {
      paymentBreakdown.qris += amount;
    } else if (method.includes('wallet') || method.includes('ewallet')) {
      paymentBreakdown.ewallet += amount;
    }
  });

  stats.value.paymentBreakdown = paymentBreakdown;

  // Fetch top products from API
  fetchTopProducts();
};

const fetchTopProducts = async () => {
  try {
    const params = {};
    if (filters.value.wisata) params.wisata = filters.value.wisata;
    if (filters.value.startDate) params.start_date = filters.value.startDate;
    if (filters.value.endDate) params.end_date = filters.value.endDate;

    const response = await api.getTopProducts(params);
    stats.value.topProducts = response.data;
    console.log('📊 Top products:', stats.value.topProducts);
  } catch (err) {
    console.error('Failed to fetch top products:', err);
    stats.value.topProducts = [];
  }
};

const applyFilters = () => {
  fetchReports();
};

const getPaymentPercentage = (method) => {
  const total = stats.value.totalRevenue;
  if (total === 0) return 0;
  
  const amount = stats.value.paymentBreakdown[method] || 0;
  return Math.round((amount / total) * 100);
};

const goToDashboard = () => {
  router.push('/dashboard');
};

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(amount || 0);
};

onMounted(async () => {
  // Fetch wisata list
  try {
    const wisataResponse = await api.getWisata();
    wisataList.value = wisataResponse.data;
  } catch (err) {
    console.error('Failed to fetch wisata:', err);
  }

  // Set default date range (last 30 days)
  const today = new Date();
  const thirtyDaysAgo = new Date(today);
  thirtyDaysAgo.setDate(today.getDate() - 30);

  filters.value.startDate = thirtyDaysAgo.toISOString().split('T')[0];
  filters.value.endDate = today.toISOString().split('T')[0];

  fetchReports();
});
</script>
