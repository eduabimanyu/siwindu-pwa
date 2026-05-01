<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navbar with Back Button -->
    <Navbar title="Transaksi Baru" titleClass="text-gray-900" showBack @back="goToDashboard" />

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Items Section (2/3) -->
        <div class="lg:col-span-2">
          <!-- Search & View Toggle -->
          <div class="mb-4 flex gap-3">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search items..."
              class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            />
            
            <!-- View Toggle -->
            <div class="flex bg-white border border-gray-300 rounded-lg overflow-hidden">
              <button
                @click="viewMode = 'grid'"
                :class="[
                  'px-4 py-2 transition',
                  viewMode === 'grid' 
                    ? 'bg-primary-600 text-white' 
                    : 'text-gray-600 hover:bg-gray-50'
                ]"
                title="Grid View"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
              </button>
              <button
                @click="viewMode = 'list'"
                :class="[
                  'px-4 py-2 transition border-l border-gray-300',
                  viewMode === 'list' 
                    ? 'bg-primary-600 text-white' 
                    : 'text-gray-600 hover:bg-gray-50'
                ]"
                title="List View"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Category Filter -->
          <div class="mb-4">
            <select
              v-model="selectedKategori"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            >
              <option value="">All Categories</option>
              <option v-for="kat in posStore.kategori" :key="kat.id" :value="kat.id">
                {{ kat.nama_kategori }}
              </option>
            </select>
          </div>

          <!-- Loading State -->
          <div v-if="posStore.loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
            <p class="mt-4 text-gray-600">Loading items...</p>
          </div>

          <!-- Error State -->
          <div v-else-if="posStore.error" class="bg-red-50 border border-red-200 rounded-lg p-4">
            <p class="text-red-800">{{ posStore.error }}</p>
          </div>

          <!-- Items Grid View -->
          <div v-else-if="viewMode === 'grid'" class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <ItemCard
              v-for="item in filteredItems"
              :key="item.id"
              :item="item"
              @select="handleSelectItem"
            />
          </div>

          <!-- Items List View -->
          <div v-else class="space-y-2">
            <div
              v-for="item in filteredItems"
              :key="item.id"
              @click="handleSelectItem(item)"
              class="bg-white rounded-lg shadow hover:shadow-md transition cursor-pointer p-4 flex items-center justify-between"
            >
              <div class="flex-1">
                <h3 class="font-semibold text-gray-900">{{ item.nama_item }}</h3>
                <p class="text-xs text-gray-500 mt-1">{{ item.kategori?.nama_kategori || 'Uncategorized' }}</p>
              </div>
              <div class="text-right ml-4">
                <p class="text-lg font-bold text-primary-600">Rp {{ formatCurrency(item.harga) }}</p>
                <span
                  v-if="item.stok !== undefined"
                  :class="[
                    'text-xs px-2 py-1 rounded inline-block mt-1',
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

          <!-- Empty State -->
          <div v-if="!posStore.loading && filteredItems.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <p class="mt-4 text-gray-600">No items found</p>
          </div>
        </div>

        <!-- Cart Section (1/3) -->
        <div class="lg:col-span-1">
          <div class="sticky top-6">
            <Cart @checkout="showPaymentModal = true" />
          </div>
        </div>
      </div>
    </div>

    <!-- Payment Modal -->
    <PaymentModal
      :show="showPaymentModal"
      :total="posStore.cartTotal"
      :banks="posStore.banks"
      :ewalets="posStore.ewalets"
      :loading="posStore.loading"
      @close="showPaymentModal = false"
      @confirm="handlePayment"
    />

    <!-- Success Modal -->
    <div
      v-if="showSuccessModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click="showSuccessModal = false"
    >
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
          <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Transaction Successful!</h3>
        <p class="text-sm text-gray-500 mb-4">Transaction has been recorded successfully.</p>
        <button
          @click="showSuccessModal = false"
          class="w-full bg-primary-600 text-white py-2 rounded-lg hover:bg-primary-700"
        >
          Close
        </button>
      </div>
    </div>

    <!-- No Shift Modal -->
    <div
      v-if="showNoShiftModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
    >
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
          <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Shift Aktif</h3>
        <p class="text-sm text-gray-500 mb-4">Anda harus memulai shift terlebih dahulu sebelum dapat mengakses POS.</p>
        <div class="flex gap-3">
          <button
            @click="goToShift"
            class="flex-1 bg-primary-600 text-white py-2 rounded-lg hover:bg-primary-700"
          >
            Mulai Shift
          </button>
          <button
            @click="goToDashboard"
            class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300"
          >
            Kembali
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { usePOSStore } from '@/stores/pos';
import ShiftService from '@/services/ShiftService';
import Navbar from '@/components/layout/Navbar.vue';
import ItemCard from '@/components/pos/ItemCard.vue';
import Cart from '@/components/pos/Cart.vue';
import PaymentModal from '@/components/pos/PaymentModal.vue';

const router = useRouter();
const authStore = useAuthStore();
const posStore = usePOSStore();

const searchQuery = ref('');
const selectedKategori = ref('');
const viewMode = ref('grid'); // 'grid' or 'list'
const showPaymentModal = ref(false);
const showSuccessModal = ref(false);
const showNoShiftModal = ref(false);

const filteredItems = computed(() => {
  let items = posStore.items;
  
  // Filter by category
  if (selectedKategori.value) {
    items = items.filter(item => item.kategori === parseInt(selectedKategori.value));
  }
  
  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    items = items.filter(item => 
      item.nama_item.toLowerCase().includes(query) ||
      item.nama_kategori?.toLowerCase().includes(query)
    );
  }
  
  return items;
});

const handleSelectItem = (item) => {
  posStore.addToCart(item);
};

const handlePayment = async (paymentData) => {
  try {
    const result = await posStore.createTransaction(paymentData);
    showPaymentModal.value = false;
    
    console.log('💳 [POS] Transaction result:', result);
    
    // Redirect to receipt print page
    // Backend returns: { message: '...', data: { id_transaksi: ... } }
    const transactionId = result?.data?.id_transaksi || result?.id_transaksi;
    
    if (transactionId) {
      console.log('✅ [POS] Redirecting to receipt:', transactionId);
      router.push(`/receipt/${transactionId}`);
    } else {
      console.warn('⚠️ [POS] No transaction ID found, showing success modal');
      // Fallback to success modal if no transaction ID
      showSuccessModal.value = true;
    }
  } catch (error) {
    console.error('Payment failed:', error);
    alert('Payment failed: ' + (error.response?.data?.message || error.message));
  }
};

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID').format(amount);
};

const goToDashboard = () => {
  router.push('/dashboard');
};

const goToShift = () => {
  router.push('/shift');
};

// Watch for route changes to reload when coming back from shift page
const checkShiftOnFocus = async () => {
  // Refresh user data from server
  await authStore.fetchUser();
  
  // Re-check shift status
  const hasActiveShift = await checkActiveShift();
  
  if (hasActiveShift) {
    // Load POS data if shift is now active
    await posStore.fetchItems();
    await posStore.fetchKategori();
    await posStore.fetchBanks();
    await posStore.fetchEwalets();
  }
};

const handleLogout = async () => {
  await authStore.logout();
  router.push('/login');
};

const checkActiveShift = async () => {
  try {
    // First, refresh user data from server to ensure we have latest id_shift
    console.log('🔍 [POS] Refreshing user data before shift check...');
    await authStore.fetchUser();
    
    // Check if user has active shift
    const user = authStore.user;
    console.log('🔍 [POS] User data after refresh:', user);
    console.log('🔍 [POS] User id_shift:', user?.id_shift);
    
    if (!user || !user.id_shift) {
      console.log('❌ [POS] No id_shift found in user data');
      showNoShiftModal.value = true;
      return false;
    }
    
    // Verify shift is still active via API
    console.log('🔍 [POS] Verifying shift via API...');
    const shiftData = await ShiftService.getCurrentShift();
    console.log('🔍 [POS] Shift data from API:', shiftData);
    
    if (!shiftData || !shiftData.shift) {
      console.log('❌ [POS] No active shift found via API');
      showNoShiftModal.value = true;
      return false;
    }
    
    console.log('✅ [POS] Active shift confirmed:', shiftData.shift.id_shift);
    return true;
  } catch (error) {
    console.error('❌ [POS] Error checking shift:', error);
    showNoShiftModal.value = true;
    return false;
  }
};

onMounted(async () => {
  // Check for active shift first
  const hasActiveShift = await checkActiveShift();
  
  if (hasActiveShift) {
    // Only load POS data if shift is active
    await posStore.fetchItems();
    await posStore.fetchKategori();
    await posStore.fetchBanks();
    await posStore.fetchEwalets();
  }
  
  // Add event listener for when user returns to this page
  window.addEventListener('focus', checkShiftOnFocus);
});

// Cleanup on unmount
onUnmounted(() => {
  window.removeEventListener('focus', checkShiftOnFocus);
});
</script>
