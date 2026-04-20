import { defineStore } from 'pinia';
import PrinterService from '@/services/PrinterService';

export const usePrinterStore = defineStore('printer', {
    state: () => ({
        isConnected: false,
        deviceName: null,
        isConnecting: false,
        error: null
    }),

    actions: {
        async connect() {
            this.isConnecting = true;
            this.error = null;
            try {
                const deviceInfo = await PrinterService.connect();
                this.isConnected = true;
                this.deviceName = deviceInfo.name;
                
                // Daftarkan listener saat terputus
                PrinterService.device.addEventListener('gattserverdisconnected', () => {
                    this.setDisconnected();
                });
                
                return true;
            } catch (error) {
                this.error = error.message;
                return false;
            } finally {
                this.isConnecting = false;
            }
        },

        disconnect() {
            PrinterService.disconnect();
            this.setDisconnected();
        },

        setDisconnected() {
            this.isConnected = false;
            this.deviceName = null;
        },

        async testPrint() {
            try {
                await PrinterService.printTest();
                return true;
            } catch (error) {
                this.error = error.message;
                return false;
            }
        }
    }
});
