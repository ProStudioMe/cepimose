/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./functions.php",
    "./inc/**/*.php",
    "./template-parts/**/*.php",
    "./templates/**/*.php",
    "./parts/**/*.html",
    "./templates/**/*.html",
    "../../plugins/*/*.php",
    "../../plugins/*/templates/**/*.php",
    "../../plugins/*/includes/**/*.php",
    "../../plugins/prostudiome/*.php",
    "../../plugins/prostudiome/templates/**/*.php",
    "../../plugins/prostudiome/includes/**/*.php",
    "../../plugins/prostudiome/src/**/*.{js,jsx}",
    "../../plugins/prostudiome/blocks/**/*.{php,js,jsx}",
    "./src/**/*.{js,jsx,ts,tsx}",
  ],
  safelist: ["aspect-[1920/640]", "text-red-500"],
  theme: {
    extend: {
      colors: {
        primary: "#0099ff",
        blue: {
          dark: "#0a76bd",
          middle: "#19ace4",
          light: "#9adaef",
          lighter: "#E9F7FF",
        },
        extra: {
          dark: "#1F2937",
        },
        white: "#ffffff",
        black: "#000000",
      },
      spacing: {
        18: "4.5rem",
      },
      container: {
        center: true,
        padding: "1.5rem",
      },
    },
  },
  plugins: [],
  corePlugins: {
    preflight: false,
  },
};
