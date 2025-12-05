/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('daisyui'),
  ],
  daisyui: {
    themes: [
      {
        light: {
          "primary": "#00BCD4",      // Teal
          "primary-focus": "#0097A7", // Darker Teal
          "primary-content": "#ffffff",
          "secondary": "#4DD0E1",    // Cyan
          "secondary-focus": "#00ACC1",
          "secondary-content": "#ffffff",
          "accent": "#80DEEA",       // Light Cyan/Mint
          "accent-focus": "#4DD0E1",
          "accent-content": "#ffffff",
          "neutral": "#F3E5F5",      // Light Pink/Lavender
          "neutral-focus": "#E1BEE7",
          "neutral-content": "#1F2937",
          "base-100": "#FFFFFF",
          "base-200": "#F9FAFB",
          "base-300": "#F3F4F6",
          "base-content": "#1F2937",
          "info": "#00BCD4",
          "success": "#4CAF50",
          "warning": "#FF9800",
          "error": "#F44336",
        },
        dark: {
          "primary": "#00BCD4",      // Teal
          "primary-focus": "#00838F",
          "primary-content": "#ffffff",
          "secondary": "#4DD0E1",    // Cyan
          "secondary-focus": "#00838F",
          "secondary-content": "#ffffff",
          "accent": "#80DEEA",       // Light Cyan
          "accent-focus": "#4DD0E1",
          "accent-content": "#1F2937",
          "neutral": "#37474F",      // Dark slate
          "neutral-focus": "#263238",
          "neutral-content": "#ECEFF1",
          "base-100": "#1F2937",     // Dark bg
          "base-200": "#111827",
          "base-300": "#0F172A",
          "base-content": "#F3F4F6",
          "info": "#4DD0E1",
          "success": "#66BB6A",
          "warning": "#FFA726",
          "error": "#EF5350",
        }
      }
    ],
  },
}
