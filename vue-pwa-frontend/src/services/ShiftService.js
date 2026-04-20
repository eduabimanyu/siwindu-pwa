import apiService from './api';

export default {
    /**
     * Get current active shift for logged-in cashier
     */
    async getCurrentShift() {
        try {
            // Use custom endpoint - need to add to api.js
            const response = await fetch(`${import.meta.env.VITE_API_URL || 'http://localhost:8000/api'}/shifts/current`, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            if (response.status === 404) {
                return null; // No active shift
            }

            if (!response.ok) {
                throw new Error('Failed to fetch current shift');
            }

            return await response.json();
        } catch (error) {
            if (error.message.includes('404')) {
                return null;
            }
            throw error;
        }
    },

    /**
     * Start a new shift
     * @param {number} saldoTunai - Initial cash amount
     */
    async startShift(saldoTunai) {
        const response = await fetch(`${import.meta.env.VITE_API_URL || 'http://localhost:8000/api'}/shifts/start`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                saldo_tunai: saldoTunai
            })
        });

        if (!response.ok) {
            throw new Error('Failed to start shift');
        }

        return await response.json();
    },

    /**
     * End current shift with reconciliation
     * @param {number} shiftId - Shift ID
     * @param {object} reconciliation - Reconciliation data
     */
    async endShift(shiftId, reconciliation = {}) {
        const response = await fetch(`${import.meta.env.VITE_API_URL || 'http://localhost:8000/api'}/shifts/end`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                shift_id: shiftId,
                actual_cash: reconciliation.actualCash,
                notes: reconciliation.notes
            })
        });

        if (!response.ok) {
            throw new Error('Failed to end shift');
        }

        return await response.json();
    },

    /**
     * Get shift history for current cashier
     * @param {object} filters - Optional filters (date range, etc)
     */
    async getShiftHistory(filters = {}) {
        const queryParams = new URLSearchParams(filters).toString();
        const url = `${import.meta.env.VITE_API_URL || 'http://localhost:8000/api'}/shifts/history${queryParams ? '?' + queryParams : ''}`;

        const response = await fetch(url, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch shift history');
        }

        return await response.json();
    },

    /**
     * Get detailed shift information
     * @param {number} shiftId - Shift ID
     */
    async getShiftDetail(shiftId) {
        const response = await fetch(`${import.meta.env.VITE_API_URL || 'http://localhost:8000/api'}/shifts/${shiftId}`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch shift detail');
        }

        const data = await response.json();
        console.log('📊 Shift Detail Response:', data);
        // Backend returns { shift: {...}, stats: {...} }
        return data;
    },

    /**
     * Generate PDF report for shift
     * @param {number} shiftId - Shift ID
     */
    async generatePDF(shiftId) {
        const response = await fetch(`${import.meta.env.VITE_API_URL || 'http://localhost:8000/api'}/shifts/${shiftId}/pdf`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to generate PDF');
        }

        return await response.blob();
    },

    /**
     * Get thermal printer format data
     * @param {number} shiftId - Shift ID
     */
    async getThermalPrintData(shiftId) {
        const response = await fetch(`${import.meta.env.VITE_API_URL || 'http://localhost:8000/api'}/shifts/${shiftId}/thermal`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch thermal print data');
        }

        return await response.json();
    },

    /**
     * Download PDF report
     * @param {number} shiftId - Shift ID
     * @param {string} filename - Optional filename
     */
    async downloadPDF(shiftId, filename = null) {
        const blob = await this.generatePDF(shiftId);
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = filename || `shift-${shiftId}-report.pdf`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
    }
};

