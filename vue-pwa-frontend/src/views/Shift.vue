<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation Bar -->
    <Navbar title="Shift Management" titleClass="text-gray-900" showBack @back="$router.push('/dashboard')" />

    <div class="max-w-7xl mx-auto px-4 py-8">
      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <p class="text-red-800">{{ error }}</p>
        <button @click="loadShiftData" class="mt-2 text-red-600 hover:text-red-800 font-medium">
          Coba Lagi
        </button>
      </div>

      <!-- Main Content -->
      <div v-else>
        <!-- No Active Shift - Start Shift Section -->
        <div v-if="!currentShift" class="bg-white rounded-lg shadow-md p-6 mb-6">
          <div class="text-center">
            <div class="mb-4">
              <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Shift Aktif</h3>
            <p class="text-gray-600 mb-6">Mulai shift baru untuk memulai transaksi</p>
            
            <div class="max-w-md mx-auto">
              <label class="block text-left text-sm font-medium text-gray-700 mb-2">
                Modal Kas Awal
              </label>
              <div class="relative">
                <span class="absolute left-3 top-3 text-gray-500">Rp</span>
                <input
                  v-model.number="startShiftData.saldoTunai"
                  type="number"
                  class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="0"
                  min="0"
                  step="1000"
                />
              </div>
              <p class="text-xs text-gray-500 mt-1 text-left">
                Masukkan jumlah uang tunai di kas/brankas saat memulai shift
              </p>
              
              <button
                @click="handleStartShift"
                :disabled="startingShift || startShiftData.saldoTunai === null || startShiftData.saldoTunai === '' || startShiftData.saldoTunai < 0"
                class="mt-4 w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors"
              >
                <span v-if="startingShift">Memulai Shift...</span>
                <span v-else>Mulai Shift</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Active Shift - Current Shift Monitoring -->
        <div v-else class="space-y-6">
          <!-- Shift Info Card -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg p-6 text-white">
            <div class="flex justify-between items-start mb-4">
              <div>
                <h3 class="text-lg font-semibold opacity-90">Shift Aktif</h3>
                <p class="text-2xl font-bold mt-1">Shift #{{ currentShift.id_shift }}</p>
              </div>
              <div class="text-right">
                <p class="text-sm opacity-90">Dimulai</p>
                <p class="font-semibold">{{ formatDate(currentShift.created_at) }}</p>
                <p class="text-sm">{{ formatTime(currentShift.created_at) }}</p>
              </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mt-6">
              <div class="bg-white bg-opacity-20 rounded-lg p-4">
                <p class="text-sm opacity-90">Modal Awal</p>
                <p class="text-xl font-bold mt-1">{{ formatCurrency(currentShift.saldo_tunai) }}</p>
              </div>
              <div class="bg-white bg-opacity-20 rounded-lg p-4">
                <p class="text-sm opacity-90">Total Pendapatan</p>
                <p class="text-xl font-bold mt-1">{{ formatCurrency(shiftStats.total_revenue) }}</p>
              </div>
            </div>
          </div>

          <!-- Statistics Cards -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Items -->
            <div class="bg-white rounded-lg shadow p-6">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm text-gray-600">Total Item Terjual</p>
                  <p class="text-3xl font-bold text-gray-900 mt-2">{{ shiftStats.total_items }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                  <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                  </svg>
                </div>
              </div>
            </div>

            <!-- Total Transactions -->
            <div class="bg-white rounded-lg shadow p-6">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm text-gray-600">Total Transaksi</p>
                  <p class="text-3xl font-bold text-gray-900 mt-2">{{ shiftStats.total_transactions }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                  <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                  </svg>
                </div>
              </div>
            </div>

            <!-- Expected Cash -->
            <div class="bg-white rounded-lg shadow p-6">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm text-gray-600">Kas Seharusnya</p>
                  <p class="text-3xl font-bold text-gray-900 mt-2">{{ formatCurrency(expectedCash) }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                  <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>

          <!-- Payment Breakdown -->
          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Rincian Pembayaran</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Tunai</p>
                <p class="text-xl font-bold text-gray-900">{{ formatCurrency(shiftStats.payment_breakdown.tunai) }}</p>
              </div>
              <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">Transfer Bank</p>
                <p class="text-xl font-bold text-gray-900">{{ formatCurrency(shiftStats.payment_breakdown.transfer) }}</p>
              </div>
              <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">QRIS</p>
                <p class="text-xl font-bold text-gray-900">{{ formatCurrency(shiftStats.payment_breakdown.qris) }}</p>
              </div>
              <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-1">E-Wallet</p>
                <p class="text-xl font-bold text-gray-900">{{ formatCurrency(shiftStats.payment_breakdown.ewallet) }}</p>
              </div>
            </div>
          </div>

          <!-- End Shift Button -->
          <div class="bg-white rounded-lg shadow p-6">
            <button
              @click="showEndShiftModal = true"
              class="w-full bg-red-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-red-700 transition-colors"
            >
              Akhiri Shift
            </button>
          </div>
        </div>

        <!-- Shift History Section -->
        <div class="mt-8 bg-white rounded-lg shadow">
          <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900">Riwayat Shift</h3>
          </div>
          
          <div class="p-6">
            <!-- Loading History -->
            <div v-if="loadingHistory" class="text-center py-8">
              <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
            </div>

            <!-- Empty History -->
            <div v-else-if="!shiftHistory.length" class="text-center py-8 text-gray-500">
              <p>Belum ada riwayat shift</p>
            </div>

            <!-- History List -->
            <div v-else class="space-y-4">
              <div
                v-for="shift in shiftHistory"
                :key="shift.id_shift"
                class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors"
              >
                <div class="flex justify-between items-start">
                  <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-2">
                      <span class="font-semibold text-gray-900">Shift #{{ shift.id_shift }}</span>
                      <span class="px-2 py-1 bg-green-500 text-white text-xs font-medium rounded-full">
                        Selesai
                      </span>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                      <div>
                        <p class="text-gray-600">Tanggal</p>
                        <p class="font-medium">{{ formatDate(shift.created_at) }}</p>
                      </div>
                      <div>
                        <p class="text-gray-600">Modal Awal</p>
                        <p class="font-medium">{{ formatCurrency(shift.saldo_tunai) }}</p>
                      </div>
                      <div>
                        <p class="text-gray-600">Pendapatan</p>
                        <p class="font-medium">{{ formatCurrency(shift.pendapatan) }}</p>
                      </div>
                      <div>
                        <p class="text-gray-600">Item Terjual</p>
                        <p class="font-medium">{{ shift.item_terjual }}</p>
                      </div>
                    </div>
                  </div>
                  
                  <div class="flex flex-row space-x-2 ml-4">
                    <button
                      @click="viewShiftDetail(shift.id_shift)"
                      class="p-2 bg-yellow-400 text-gray-800 rounded-lg hover:bg-yellow-500 transition"
                      title="Detail"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </button>
                    <button
                      @click="showPrintOptions(shift.id_shift)"
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
          </div>
        </div>
      </div>
    </div>

    <!-- End Shift Modal -->
    <div
      v-if="showEndShiftModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="showEndShiftModal = false"
    >
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Akhiri Shift</h3>
        
        <div class="mb-6">
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
            <p class="text-sm text-blue-800 mb-2">Kas Seharusnya:</p>
            <p class="text-2xl font-bold text-blue-900">{{ formatCurrency(expectedCash) }}</p>
            <p class="text-xs text-blue-600 mt-1">
              Modal Awal ({{ formatCurrency(currentShift?.saldo_tunai) }}) + 
              Tunai ({{ formatCurrency(shiftStats.payment_breakdown.tunai) }})
            </p>
          </div>

          <label class="block text-sm font-medium text-gray-700 mb-2">
            Jumlah Uang Fisik di Kas <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <span class="absolute left-3 top-3 text-gray-500">Rp</span>
            <input
              v-model.number="endShiftData.actualCash"
              type="number"
              class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="0"
              min="0"
              step="1000"
            />
          </div>
          <p class="text-xs text-gray-500 mt-1">
            Hitung dan masukkan jumlah uang tunai yang ada di kas
          </p>

          <!-- Cash Difference Alert -->
          <div v-if="endShiftData.actualCash && cashDifference !== 0" class="mt-4">
            <div
              :class="[
                'border rounded-lg p-3',
                cashDifference > 0 ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'
              ]"
            >
              <p :class="['text-sm font-medium', cashDifference > 0 ? 'text-green-800' : 'text-red-800']">
                {{ cashDifference > 0 ? 'Kelebihan' : 'Kekurangan' }}: 
                {{ formatCurrency(Math.abs(cashDifference)) }}
              </p>
            </div>
          </div>

          <label class="block text-sm font-medium text-gray-700 mb-2 mt-4">
            Catatan (Opsional)
          </label>
          <textarea
            v-model="endShiftData.notes"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            rows="3"
            placeholder="Tambahkan catatan jika ada..."
          ></textarea>
        </div>

        <div class="flex space-x-3">
          <button
            @click="showEndShiftModal = false"
            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium"
          >
            Batal
          </button>
          <button
            @click="handleEndShift"
            :disabled="endingShift || !endShiftData.actualCash"
            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:bg-gray-300 disabled:cursor-not-allowed font-medium"
          >
            <span v-if="endingShift">Mengakhiri...</span>
            <span v-else>Akhiri Shift</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Print Options Modal -->
    <div
      v-if="showPrintModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="showPrintModal = false"
    >
      <div class="bg-white rounded-lg shadow-xl max-w-sm w-full p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Pilih Opsi Print</h3>
        
        <div class="space-y-3">
          <button
            @click="printThermal"
            class="w-full flex items-center justify-between px-4 py-3 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-colors"
          >
            <div class="flex items-center space-x-3">
              <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
              </svg>
              <span class="font-medium">Thermal Printer</span>
            </div>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </button>

          <button
            @click="downloadPDF"
            class="w-full flex items-center justify-between px-4 py-3 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-colors"
          >
            <div class="flex items-center space-x-3">
              <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              <span class="font-medium">Download PDF</span>
            </div>
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </button>
        </div>

        <button
          @click="showPrintModal = false"
          class="w-full mt-4 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium"
        >
          Batal
        </button>
      </div>
    </div>

    <!-- Shift Detail Modal -->
    <div
      v-if="showDetailModal && selectedShiftDetail"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto"
      @click.self="showDetailModal = false"
    >
      <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full p-6 my-8">
        <div class="flex justify-between items-start mb-6">
          <h3 class="text-xl font-bold text-gray-900">Detail Shift #{{ selectedShiftDetail.shift?.id_shift }}</h3>
          <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <div class="space-y-4">
          <!-- Shift Info -->
          <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-sm text-gray-600">Tanggal Mulai</p>
              <p class="font-semibold">{{ formatDate(selectedShiftDetail.shift?.created_at) }}</p>
              <p class="text-sm text-gray-600">{{ formatTime(selectedShiftDetail.shift?.created_at) }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-sm text-gray-600">Tanggal Selesai</p>
              <p class="font-semibold">{{ formatDate(selectedShiftDetail.shift?.updated_at) }}</p>
              <p class="text-sm text-gray-600">{{ formatTime(selectedShiftDetail.shift?.updated_at) }}</p>
            </div>
          </div>

          <!-- Financial Summary -->
          <div class="border-t pt-4">
            <h4 class="font-semibold mb-3">Ringkasan Keuangan</h4>
            <div class="space-y-2">
              <div class="flex justify-between">
                <span class="text-gray-600">Modal Awal:</span>
                <span class="font-medium">{{ formatCurrency(selectedShiftDetail.shift?.saldo_tunai) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Total Pendapatan:</span>
                <span class="font-medium">{{ formatCurrency(selectedShiftDetail.stats?.pendapatan) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Item Terjual:</span>
                <span class="font-medium">{{ selectedShiftDetail.stats?.item_terjual }}</span>
              </div>
            </div>
          </div>

          <!-- Payment Breakdown -->
          <div class="border-t pt-4">
            <h4 class="font-semibold mb-3">Rincian Pembayaran</h4>
            <div class="grid grid-cols-2 gap-3">
              <div class="bg-blue-50 rounded-lg p-3">
                <p class="text-sm text-gray-600">Tunai</p>
                <p class="font-semibold">{{ formatCurrency(selectedShiftDetail.stats?.payment_breakdown?.tunai || 0) }}</p>
              </div>
              <div class="bg-green-50 rounded-lg p-3">
                <p class="text-sm text-gray-600">Transfer Bank</p>
                <p class="font-semibold">{{ formatCurrency(selectedShiftDetail.stats?.payment_breakdown?.transfer || 0) }}</p>
              </div>
              <div class="bg-purple-50 rounded-lg p-3">
                <p class="text-sm text-gray-600">QRIS</p>
                <p class="font-semibold">{{ formatCurrency(selectedShiftDetail.stats?.payment_breakdown?.qris || 0) }}</p>
              </div>
              <div class="bg-orange-50 rounded-lg p-3">
                <p class="text-sm text-gray-600">E-Wallet</p>
                <p class="font-semibold">{{ formatCurrency(selectedShiftDetail.stats?.payment_breakdown?.ewallet || 0) }}</p>
              </div>
            </div>
          </div>

          <!-- Product Breakdown -->
          <div v-if="selectedShiftDetail.stats?.product_breakdown?.length > 0" class="border-t pt-4">
            <h4 class="font-semibold mb-3">Detail Produk Terjual</h4>
            <div class="space-y-2">
              <div 
                v-for="(product, index) in selectedShiftDetail.stats.product_breakdown" 
                :key="index"
                class="flex justify-between items-center p-3 bg-gray-50 rounded-lg"
              >
                <div class="flex-1">
                  <p class="font-medium text-gray-900">{{ product.item_name }}</p>
                  <p class="text-sm text-gray-600">{{ formatCurrency(product.price) }} × {{ product.quantity }}</p>
                </div>
                <p class="font-semibold text-gray-900">{{ formatCurrency(product.subtotal) }}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-6 flex space-x-3">
          <button
            @click="showDetailModal = false"
            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium"
          >
            Tutup
          </button>
          <button
            @click="showPrintOptions(selectedShiftDetail.shift?.id_shift)"
            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium"
          >
            Print Laporan
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import Navbar from '@/components/layout/Navbar.vue';
import ShiftService from '../services/ShiftService';

const router = useRouter();

// State
const loading = ref(true);
const error = ref(null);
const currentShift = ref(null);
const shiftStats = ref({
  total_items: 0,
  total_revenue: 0,
  total_transactions: 0,
  payment_breakdown: {
    tunai: 0,
    transfer: 0,
    qris: 0,
    ewallet: 0
  }
});
const shiftHistory = ref([]);
const loadingHistory = ref(false);
const currentUser = ref(null);

// Start Shift
const startingShift = ref(false);
const startShiftData = ref({
  saldoTunai: 0
});

// End Shift
const showEndShiftModal = ref(false);
const endingShift = ref(false);
const endShiftData = ref({
  actualCash: 0,
  notes: ''
});

// Print
const showPrintModal = ref(false);
const selectedPrintShiftId = ref(null);

// Detail
const showDetailModal = ref(false);
const selectedShiftDetail = ref(null);

// Computed
const expectedCash = computed(() => {
  if (!currentShift.value) return 0;
  
  const modalAwal = parseFloat(currentShift.value.saldo_tunai) || 0;
  const tunai = parseFloat(shiftStats.value.payment_breakdown.tunai) || 0;
  
  console.log('💰 Expected Cash Calculation:', {
    modalAwal,
    tunai,
    total: modalAwal + tunai
  });
  
  return modalAwal + tunai;
});

const cashDifference = computed(() => {
  return endShiftData.value.actualCash - expectedCash.value;
});

// Methods
const loadShiftData = async () => {
  try {
    loading.value = true;
    error.value = null;

    // Load user data
    const userData = JSON.parse(localStorage.getItem('user_data') || '{}');
    currentUser.value = userData;

    // Load current shift
    const shiftData = await ShiftService.getCurrentShift();
    console.log('📊 Shift Data from API:', shiftData);
    currentShift.value = shiftData?.shift || null;
    if (shiftData?.stats) {
      console.log('📊 Shift Stats:', shiftData.stats);
      console.log('📊 Payment Breakdown:', shiftData.stats.payment_breakdown);
      shiftStats.value = shiftData.stats;
    }

    // Load shift history
    await loadShiftHistory();
  } catch (err) {
    console.error('Error loading shift data:', err);
    if (err.response?.status !== 404) {
      error.value = 'Gagal memuat data shift. Silakan coba lagi.';
    }
  } finally {
    loading.value = false;
  }
};

const loadShiftHistory = async () => {
  try {
    loadingHistory.value = true;
    const history = await ShiftService.getShiftHistory();
    shiftHistory.value = history.data || [];
  } catch (err) {
    console.error('Error loading shift history:', err);
  } finally {
    loadingHistory.value = false;
  }
};

const handleStartShift = async () => {
  if (startShiftData.value.saldoTunai === null || startShiftData.value.saldoTunai === '' || startShiftData.value.saldoTunai < 0) {
    alert('Mohon masukkan modal kas awal yang valid');
    return;
  }

  try {
    startingShift.value = true;
    await ShiftService.startShift(startShiftData.value.saldoTunai);
    
    // Refresh user data to get updated id_shift
    const { useAuthStore } = await import('@/stores/auth');
    const authStore = useAuthStore();
    await authStore.fetchUser();
    
    // Reload shift data
    await loadShiftData();
    
    // Reset form
    startShiftData.value.saldoTunai = 0;
    
    // Show success message
    alert('Shift berhasil dimulai!');
  } catch (err) {
    console.error('Error starting shift:', err);
    alert('Gagal memulai shift. Silakan coba lagi.');
  } finally {
    startingShift.value = false;
  }
};

const handleEndShift = async () => {
  if (!endShiftData.value.actualCash && endShiftData.value.actualCash !== 0) {
    alert('Mohon masukkan jumlah uang fisik di kas');
    return;
  }

  try {
    endingShift.value = true;
    await ShiftService.endShift(currentShift.value.id_shift, {
      actualCash: endShiftData.value.actualCash,
      notes: endShiftData.value.notes
    });

    // Refresh user data to clear id_shift
    const { useAuthStore } = await import('@/stores/auth');
    const authStore = useAuthStore();
    await authStore.fetchUser();

    // Close modal
    showEndShiftModal.value = false;
    
    // Reset form
    endShiftData.value = {
      actualCash: 0,
      notes: ''
    };

    // Reload shift data
    await loadShiftData();
    
    // Show success message
    alert('Shift berhasil diakhiri!');
  } catch (err) {
    console.error('Error ending shift:', err);
    alert('Gagal mengakhiri shift. Silakan coba lagi.');
  } finally {
    endingShift.value = false;
  }
};

const viewShiftDetail = async (shiftId) => {
  try {
    const detail = await ShiftService.getShiftDetail(shiftId);
    console.log('📊 Detail loaded:', detail);
    // Store the complete response (includes shift and stats)
    selectedShiftDetail.value = detail;
    showDetailModal.value = true;
  } catch (err) {
    console.error('Error loading shift detail:', err);
    alert('Gagal memuat detail shift');
  }
};

const showPrintOptions = (shiftId) => {
  selectedPrintShiftId.value = shiftId;
  showPrintModal.value = true;
  showDetailModal.value = false;
};

const printThermal = async () => {
  try {
    const data = await ShiftService.getThermalPrintData(selectedPrintShiftId.value);
    
    // Open print window with thermal format
    const printWindow = window.open('', '_blank', 'width=300,height=600');
    printWindow.document.write(`
      <html>
        <head>
          <title>Print Shift #${selectedPrintShiftId.value}</title>
          <style>
            body { font-family: monospace; font-size: 12px; margin: 0; padding: 10px; }
            .center { text-align: center; }
            .bold { font-weight: bold; }
            .line { border-top: 1px dashed #000; margin: 5px 0; }
            table { width: 100%; }
            td { padding: 2px 0; }
            .right { text-align: right; }
          </style>
        </head>
        <body>
          ${data.html || '<p>No data available</p>'}
        </body>
      </html>
    `);
    printWindow.document.close();
    printWindow.print();
    
    showPrintModal.value = false;
  } catch (err) {
    console.error('Error printing thermal:', err);
    alert('Gagal mencetak. Silakan coba lagi.');
  }
};

const downloadPDF = async () => {
  try {
    await ShiftService.downloadPDF(selectedPrintShiftId.value);
    showPrintModal.value = false;
  } catch (err) {
    console.error('Error downloading PDF:', err);
    alert('Gagal mengunduh PDF. Silakan coba lagi.');
  }
};

// Utility functions
const formatCurrency = (value) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(value || 0);
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  }).format(date);
};

const formatTime = (dateString) => {
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('id-ID', {
    hour: '2-digit',
    minute: '2-digit'
  }).format(date);
};

// Lifecycle
onMounted(() => {
  loadShiftData();
});
</script>

<style scoped>
/* Custom scrollbar for modals */
.overflow-y-auto::-webkit-scrollbar {
  width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>
