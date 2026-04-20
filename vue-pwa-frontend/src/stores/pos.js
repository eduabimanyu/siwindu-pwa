import { defineStore } from 'pinia';
import api from '@/services/api';

export const usePOSStore = defineStore('pos', {
    state: () => ({
        items: [],
        cart: [],
        kategori: [],
        banks: [],
        ewalets: [],
        selectedPaymentMethod: 'cash',
        customerName: '', // Customer name (optional)
        loading: false,
        error: null
    }),

    getters: {
        cartTotal: (state) => {
            return state.cart.reduce((total, item) => {
                return total + (item.price * item.quantity);
            }, 0);
        },

        cartItemCount: (state) => {
            return state.cart.reduce((total, item) => total + item.quantity, 0);
        },

        getCartItem: (state) => (itemId) => {
            return state.cart.find(item => item.id === itemId);
        }
    },

    actions: {
        async fetchItems() {
            this.loading = true;
            try {
                // Get wisata from auth store
                const { useAuthStore } = await import('./auth');
                const authStore = useAuthStore();
                const wisataId = authStore.user?.wisata;

                console.log('🔍 [POS Store] User:', authStore.user);
                console.log('🔍 [POS Store] Wisata ID:', wisataId);
                console.log('🔍 [POS Store] Calling API with wisataId:', wisataId);

                const response = await api.getItems(wisataId);

                console.log('✅ [POS Store] API Response:', response.data);
                console.log('✅ [POS Store] Items count:', response.data.length);

                this.items = response.data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to load items';
                console.error('❌ [POS Store] Failed to fetch items:', error);
            } finally {
                this.loading = false;
            }
        },

        async fetchBanks() {
            try {
                const response = await api.getBanks();
                this.banks = response.data;
            } catch (error) {
                console.error('Failed to fetch banks:', error);
            }
        },

        async fetchEwalets() {
            try {
                const response = await api.getEwalets();
                this.ewalets = response.data;
            } catch (error) {
                console.error('Failed to fetch ewalets:', error);
            }
        },

        async fetchKategori() {
            try {
                const response = await api.getKategori();
                this.kategori = response.data;
            } catch (error) {
                console.error('Failed to fetch kategori:', error);
            }
        },

        addToCart(item) {
            const existingItem = this.cart.find(i => i.id === item.id);

            if (existingItem) {
                existingItem.quantity++;
            } else {
                this.cart.push({
                    id: item.id,
                    name: item.nama_item,
                    price: item.harga,
                    quantity: 1,
                    kategori: item.kategori
                });
            }
        },

        removeFromCart(itemId) {
            const index = this.cart.findIndex(i => i.id === itemId);
            if (index > -1) {
                this.cart.splice(index, 1);
            }
        },

        updateQuantity(itemId, quantity) {
            const item = this.cart.find(i => i.id === itemId);
            if (item) {
                if (quantity <= 0) {
                    this.removeFromCart(itemId);
                } else {
                    item.quantity = quantity;
                }
            }
        },

        clearCart() {
            this.cart = [];
            this.selectedPaymentMethod = 'cash';
            this.customerName = ''; // Reset customer name
        },

        setPaymentMethod(method) {
            this.selectedPaymentMethod = method;
        },

        async createTransaction(paymentData) {
            this.loading = true;
            this.error = null;

            try {
                // Get user data for required fields
                const { useAuthStore } = await import('./auth');
                const authStore = useAuthStore();
                const user = authStore.user;

                if (!user) {
                    throw new Error('User not authenticated');
                }

                if (!user.wisata) {
                    throw new Error('User wisata not found');
                }

                if (!user.id_shift) {
                    throw new Error('No active shift. Please start a shift first.');
                }


                // Map payment method to backend format
                let jenisPembayaran = '';
                if (paymentData.payment_method === 'cash') {
                    jenisPembayaran = 'Tunai';
                } else if (paymentData.payment_method === 'transfer') {
                    jenisPembayaran = 'Transfer Bank';
                } else if (paymentData.payment_method === 'qris') {
                    jenisPembayaran = 'QRIS';
                } else if (paymentData.payment_method === 'ewallet') {
                    jenisPembayaran = 'Transfer Ewallet';
                }

                console.log('💳 Payment method mapping:', {
                    from: paymentData.payment_method,
                    to: jenisPembayaran
                });

                const transactionData = {
                    // Required fields
                    wisata: user.wisata,
                    kasir: user.id,
                    id_shift: user.id_shift,

                    // Transaction details
                    total_harga: this.cartTotal,
                    jenis_pembayaran: jenisPembayaran,
                    nama_pelanggan: this.customerName || null, // Optional customer name
                    status: 'Selesai',

                    // Items
                    items: this.cart.map(item => ({
                        id_item: item.id,
                        harga: item.price,
                        jumlah: item.quantity,
                        subtotal: item.price * item.quantity
                    })),

                    // Payment specific fields
                    bayar: paymentData.amount_paid || this.cartTotal,
                    bank_id: paymentData.bank_id || null,
                    ewalet_id: paymentData.ewalet_id || null
                };

                console.log('📤 [POS Store] Sending transaction data:', transactionData);

                const response = await api.createTransaction(transactionData);

                console.log('✅ [POS Store] Transaction created:', response.data);

                // Clear cart after successful transaction
                this.clearCart();

                return response.data;
            } catch (error) {
                console.error('❌ [POS Store] Transaction failed:', error);
                this.error = error.response?.data?.message || error.message || 'Transaction failed';
                throw error;
            } finally {
                this.loading = false;
            }
        }
    }
});
