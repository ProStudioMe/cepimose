/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.php", "./**/*.html", "./parts/**/*.html", "./src/**/*.css"],
  theme: {
    extend: {
      colors: {
        primary: "#0099ff",
        blue: {
          dark: "#0a76bd",
          middle: "#19ace4",
          light: "#9adaef",
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
};
