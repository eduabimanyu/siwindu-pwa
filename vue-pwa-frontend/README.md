# SIWINDU POS - Vue 3 PWA

Progressive Web App untuk Point of Sale sistem e-tiket wisata, dibangun dengan Vue 3 + Vite.

## 🚀 Quick Start

```bash
# Install dependencies
npm install

# Run development server
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview
```

## 📦 Tech Stack

- **Vue 3** - Progressive JavaScript Framework
- **Vite** - Next Generation Frontend Tooling
- **Vue Router** - Official Router for Vue.js
- **Pinia** - State Management
- **Axios** - HTTP Client
- **TailwindCSS** - Utility-first CSS Framework
- **Vite PWA** - PWA Plugin for Vite

## 🔧 Configuration

Create `.env` file:

```env
VITE_API_URL=http://localhost:8000/api
```

## 📁 Project Structure

```
src/
├── assets/          # Static assets
├── components/      # Reusable components
│   ├── layout/     # Layout components
│   ├── common/     # Common components
│   └── pos/        # POS-specific components
├── views/          # Page components
├── router/         # Vue Router configuration
├── stores/         # Pinia stores
├── services/       # API services
├── composables/    # Composition API utilities
└── utils/          # Helper functions
```

## 🌐 API Endpoints

Backend: Laravel API at `http://localhost:8000/api`

- Authentication: `/login`, `/logout`, `/user`
- Dashboard: `/dashboard/stats`
- Transactions: `/transactions`
- Shifts: `/shifts/start`, `/shifts/end`
- Master Data: `/items`, `/wisata`, `/banks`, `/ewalets`

## 📱 PWA Features

- ✅ Offline capability
- ✅ Installable on mobile & desktop
- ✅ Service Worker caching
- ✅ Background sync

## 🔐 Default Credentials

```
Email: admin@siwindu.com
Password: password
```

---

**Built with ❤️ using Vue 3**
