import { defineStore } from 'pinia';
import api from '@/services/api';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: JSON.parse(localStorage.getItem('user_data')) || null,
        token: localStorage.getItem('auth_token') || null,
        loading: false,
        error: null
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
        userName: (state) => state.user?.name || '',
        userRole: (state) => {
            // Handle roles (plural) or role (singular) as array of objects or string
            const roleData = state.user?.roles || state.user?.role;
            if (Array.isArray(roleData)) {
                return roleData[0]?.name || roleData[0]?.display_name || '';
            }
            return roleData || '';
        },
        userWisata: (state) => state.user?.wisata || null,
        hasRole: (state) => (roles) => {
            // Check both 'roles' and 'role' fields
            const roleData = state.user?.roles || state.user?.role;
            if (!roleData) return false;

            // Handle role as array of objects
            if (Array.isArray(roleData)) {
                const userRoleName = (roleData[0]?.name || roleData[0]?.display_name || '').toLowerCase();
                console.log('🔍 Checking role:', userRoleName, 'against:', roles);
                const hasRole = roles.some(role => {
                    const match = userRoleName === role.toLowerCase();
                    console.log(`  - "${userRoleName}" === "${role.toLowerCase()}"?`, match);
                    return match;
                });
                console.log('✅ Has role result:', hasRole);
                return hasRole;
            }

            // Handle role as string (fallback)
            const userRole = roleData.toLowerCase();
            return roles.some(role => userRole.includes(role.toLowerCase()));
        }
    },

    actions: {
        async login(email, password) {
            this.loading = true;
            this.error = null;

            try {
                const response = await api.login(email, password);
                this.token = response.data.token;
                this.user = response.data.user;

                // Save to localStorage
                localStorage.setItem('auth_token', this.token);
                localStorage.setItem('user_data', JSON.stringify(this.user));

                return true;
            } catch (error) {
                let errorMessage = error.response?.data?.message || 'Login gagal';
                if (errorMessage.includes('SQLSTATE[HY000] [2002]') || errorMessage.includes('No connection could be made')) {
                    errorMessage = 'Gagal koneksi ke database';
                }
                this.error = errorMessage;
                return false;
            } finally {
                this.loading = false;
            }
        },

        async logout() {
            try {
                await api.logout();
            } catch (error) {
                console.error('Logout error:', error);
            } finally {
                // Clear state
                this.token = null;
                this.user = null;
                this.error = null;

                // Clear localStorage
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user_data');
            }
        },

        async fetchUser() {
            if (!this.token) return;

            try {
                const response = await api.getUser();
                this.user = response.data;
                localStorage.setItem('user_data', JSON.stringify(this.user));
            } catch (error) {
                console.error('Fetch user error:', error);
                this.logout();
            }
        }
    }
});
