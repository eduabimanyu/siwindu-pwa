<template>
  <nav class="bg-white shadow-sm sticky top-0 z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center space-x-4">
          <slot name="left">
            <button
              v-if="showBack"
              @click="$emit('back')"
              class="p-2 rounded-lg hover:bg-gray-100 transition text-gray-600"
              title="Kembali"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
            </button>
            <h1 class="text-base font-semibold" :class="titleClass">{{ title }}</h1>
          </slot>
        </div>
        <div class="flex items-center space-x-4">
          <slot name="right"></slot>
          <button
            @click="showPrinterModal = true"
            class="p-2 text-gray-500 hover:text-blue-600 rounded-lg hover:bg-gray-100 transition relative"
            title="Pengaturan Printer"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            <span v-if="printerStore.isConnected" class="absolute top-1 right-1 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></span>
            <span v-else class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full"></span>
          </button>
          <!-- User Dropdown -->
          <div class="relative ml-3">
            <div>
              <button
                @click="showUserMenu = !showUserMenu"
                @blur="hideUserMenu"
                type="button"
                class="flex items-center max-w-xs text-sm bg-white rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                id="user-menu-button"
                aria-expanded="false"
                aria-haspopup="true"
              >
                <span class="sr-only">Open user menu</span>
                <img
                  class="w-8 h-8 rounded-full object-cover border border-gray-200"
                  :src="'https://ui-avatars.com/api/?name=' + encodeURIComponent(userName || 'User') + '&background=random'"
                  alt="User Avatar"
                />
              </button>
            </div>

            <!-- Dropdown menu -->
            <transition
              enter-active-class="transition ease-out duration-100"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <div
                v-if="showUserMenu"
                class="absolute right-0 z-10 w-48 py-1 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                role="menu"
                aria-orientation="vertical"
                aria-labelledby="user-menu-button"
                tabindex="-1"
              >
                <div class="px-4 py-2 text-xs text-gray-500 border-b border-gray-100 mb-1 truncate">
                  Signed in as <strong>{{ userName }}</strong>
                </div>
                
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Status</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Profile</a>
                
                <div class="border-t border-gray-100 my-1"></div>
                
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Settings</a>
                <a 
                  href="#" 
                  @click.prevent="handleLogout" 
                  class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700" 
                  role="menuitem"
                >
                  Logout
                </a>
              </div>
            </transition>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <BluetoothPrinterModal 
    :show="showPrinterModal" 
    @close="showPrinterModal = false" 
  />
</template>

<script setup>
import { computed, ref, defineProps, defineEmits } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { usePrinterStore } from '@/stores/printer';
import BluetoothPrinterModal from '@/components/common/BluetoothPrinterModal.vue';

const router = useRouter();
const authStore = useAuthStore();
const printerStore = usePrinterStore();

const props = defineProps({
  title: {
    type: String,
    default: 'SIWINDU POS'
  },
  titleClass: {
    type: String,
    default: 'text-primary-600'
  },
  showBack: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['back']);

const showPrinterModal = ref(false);
const showUserMenu = ref(false);
const userName = computed(() => authStore.userName);

const hideUserMenu = () => {
  setTimeout(() => {
    showUserMenu.value = false;
  }, 200);
};

const handleLogout = async () => {
  await authStore.logout();
  router.push('/login');
};
</script>
