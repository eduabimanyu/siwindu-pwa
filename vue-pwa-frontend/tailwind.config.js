/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Poppins', 'sans-serif'],
      },
      colors: {
        primary: {
          50: '#f0f6ff',
          100: '#e0edff',
          200: '#c7ddff',
          300: '#a1c8ff',
          400: '#75aaff',
          500: '#4986ff',
          600: '#206bc4',
          700: '#1b59a6',
          800: '#16498a',
          900: '#133f75',
        }
      }
    },
  },
  plugins: [],
}
