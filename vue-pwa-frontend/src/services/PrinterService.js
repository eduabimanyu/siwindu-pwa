class PrinterService {
    constructor() {
        this.device = null;
        this.server = null; 
        this.characteristic = null;
    }

    async connect() {
        if (!navigator.bluetooth) {
            throw new Error('Web Bluetooth API tidak didukung di browser ini.');
        }

        try {
            // Meminta perangkat Bluetooth dari pengguna
            this.device = await navigator.bluetooth.requestDevice({
                // Terima semua perangkat, karena UUID printer bisa berbeda-beda
                acceptAllDevices: true,
                optionalServices: ['000018f0-0000-1000-8000-00805f9b34fb', 'e7810a71-73ae-499d-8c15-faa9aef0c3f2'] // UUID umum untuk printer thermal
            });

            this.server = await this.device.gatt.connect();

            // Coba cari service yang tersedia
            const services = await this.server.getPrimaryServices();
            
            for (const service of services) {
                const characteristics = await service.getCharacteristics();
                for (const char of characteristics) {
                    if (char.properties.write || char.properties.writeWithoutResponse) {
                        this.characteristic = char;
                        break;
                    }
                }
                if (this.characteristic) break;
            }

            if (!this.characteristic) {
                throw new Error('Tidak dapat menemukan service untuk menulis ke printer.');
            }

            // Dengarkan event disconnect
            this.device.addEventListener('gattserverdisconnected', this.onDisconnected.bind(this));

            return {
                name: this.device.name || 'Unknown Printer',
                id: this.device.id
            };
        } catch (error) {
            console.error('Koneksi Bluetooth gagal:', error);
            this.disconnect();
            throw error;
        }
    }

    onDisconnected() {
        console.log('Printer terputus');
        this.device = null;
        this.server = null;
        this.characteristic = null;
    }

    disconnect() {
        if (this.device && this.device.gatt.connected) {
            this.device.gatt.disconnect();
        }
        this.onDisconnected();
    }

    async printTest() {
        if (!this.characteristic) {
            throw new Error('Printer belum terkoneksi.');
        }

        const encoder = new TextEncoder();
        
        // Command ESC/POS Dasar
        const init = new Uint8Array([0x1B, 0x40]); // Initialize
        const alignCenter = new Uint8Array([0x1B, 0x61, 0x01]); // Align Center
        const alignLeft = new Uint8Array([0x1B, 0x61, 0x00]); // Align Left
        const boldOn = new Uint8Array([0x1B, 0x45, 0x01]); // Bold On
        const boldOff = new Uint8Array([0x1B, 0x45, 0x00]); // Bold Off
        const cut = new Uint8Array([0x1D, 0x56, 0x41, 0x10]); // Cut paper
        
        const text1 = encoder.encode("TEST PRINT\n");
        const text2 = encoder.encode("SIWINDU POS\n");
        const text3 = encoder.encode("Koneksi Bluetooth Berhasil!\n\n\n\n\n");

        try {
            await this.characteristic.writeValue(init);
            await this.characteristic.writeValue(alignCenter);
            await this.characteristic.writeValue(boldOn);
            await this.characteristic.writeValue(text1);
            await this.characteristic.writeValue(text2);
            await this.characteristic.writeValue(boldOff);
            await this.characteristic.writeValue(alignLeft);
            await this.characteristic.writeValue(text3);
            
            // Coba potong kertas jika didukung
            try {
                await this.characteristic.writeValue(cut);
            } catch(e) {}
            
            return true;
        } catch (error) {
            console.error('Gagal mencetak:', error);
            throw error;
        }
    }
}

export default new PrinterService();
