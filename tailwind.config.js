/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'selector',
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    fontFamily: {
      'headline': ['Outfit', 'sans-serif'],
      'body': ['Inter', 'sans-serif'],
      'sans': ['Inter', 'sans-serif'],
    },
    extend: {
      colors: {
        'primary': {
          DEFAULT: '#1C3D76',
          light: '#2651a0',
          dark: '#132c55',
          hover: '#163060',
        },
        'rocie': {
          blue: '#1C3D76',
          'blue-light': '#2651a0',
          'blue-dark': '#132c55',
          accent: '#F5A623',
          'accent-hover': '#e6941a',
          'gray-bg': '#f8f9fc',
        },
        'rocie-dark': '#0d1f3c',
        'rocie-footer': {
          top: '#1C3D76',
          mid: '#132c55',
          bottom: '#0d1f3c',
        },
      },
      container: {
        center: true,
        padding: {
          DEFAULT: '1rem',
          sm: '2rem',
        },
        screens: {
          sm: '600px',
          md: '728px',
          lg: '984px',
          xl: '1240px',
          '2xl': '1240px',
        },
      }
    },
  },
  plugins: [],
}
