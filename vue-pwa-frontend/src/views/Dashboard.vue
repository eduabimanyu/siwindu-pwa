<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <Navbar />

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h2 class="text-2xl font-bold text-gray-900 mb-6">Dashboard</h2>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stats Card -->
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-8 w-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Transaksi Hari Ini</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.transaksi_hari_ini || 0 }}</p>
            </div>
          </div>
        </div>

        <!-- More stats cards... -->
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Pendapatan Hari Ini</p>
              <p class="text-2xl font-bold text-gray-900">Rp {{ formatCurrency(stats.pendapatan_hari_ini || 0) }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        
        <!-- Kasir Layout -->
        <div v-if="authStore.hasRole(['Kasir'])" class="space-y-4">
          <!-- Transaksi Baru - Full Width -->
          <router-link
            to="/pos"
            class="block w-full bg-green-600 text-white rounded-lg py-4 text-center hover:bg-green-400 transition font-semibold text-lg"
          >
            <div class="flex items-center justify-center space-x-2">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              <span>Transaksi Baru</span>
            </div>
          </router-link>

          <!-- Shift & Riwayat Transaksi - 2 Columns -->
          <div class="grid grid-cols-2 gap-4">
            <router-link
              to="/shift"
              class="bg-indigo-600 text-white rounded-lg p-6 text-center hover:bg-indigo-700 transition"
            >
              <svg class="h-12 w-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span class="font-semibold">Shift</span>
            </router-link>

            <router-link
              to="/transactions"
              class="bg-yellow-500 text-white rounded-lg p-6 text-center hover:bg-yellow-600 transition"
            >
              <svg class="h-12 w-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              <span class="font-semibold">Riwayat Transaksi</span>
            </router-link>
          </div>
        </div>

        <!-- Admin/Manager/Keuangan Layout -->
        <div v-else class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <!-- Transactions - All Users -->
          <router-link
            to="/transactions"
            class="bg-yellow-500 text-white rounded-lg p-6 text-center hover:bg-blue-700 transition"
          >
            <svg class="h-12 w-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span class="font-semibold">Riwayat Transaksi</span>
          </router-link>

          <!-- Reports - Admin/Manager/Keuangan Only -->
          <router-link
            v-if="authStore.hasRole(['Admin', 'Manager', 'Keuangan'])"
            to="/reports"
            class="bg-purple-600 text-white rounded-lg p-6 text-center hover:bg-purple-700 transition"
          >
            <svg class="h-12 w-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="font-semibold">Laporan</span>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Navbar from '@/components/layout/Navbar.vue';
import api from '@/services/api';

const router = useRouter();
const authStore = useAuthStore();

const stats = ref({
  transaksi_hari_ini: 0,
  pendapatan_hari_ini: 0
});

const loadStats = async () => {
  try {
    const response = await api.getDashboardStats();
    stats.value = response.data;
  } catch (error) {
    console.error('Failed to load stats:', error);
  }
};

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID').format(amount);
};

onMounted(() => {
  // Debug: Log user role
  console.log('User data:', authStore.user);
  console.log('User role:', authStore.userRole);
  console.log('Has Admin role?', authStore.hasRole(['Admin']));
  console.log('Has Admin/Manager/Keuangan role?', authStore.hasRole(['Admin', 'Manager', 'Keuangan']));
  
  loadStats();
});
</script>
