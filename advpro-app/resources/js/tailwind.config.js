// tailwind.config.js
/** @type {import('tailwindcss').Config} */
module.exports = {
  // THIS IS CRUCIAL: Enable dark mode based on the 'dark' class on the HTML tag
  darkMode: 'class',

  // Configure the paths where Tailwind should look for your classes
  content: [
    './resources/**/*.blade.php', // All your Blade files
    './resources/**/*.js',        // All your JavaScript files
    // Add any other paths where you might use Tailwind classes (e.g., if you add Vue/React components later)
    // './resources/**/*.vue',
  ],
  theme: {
    extend: {
      // You can extend the default Tailwind theme here if needed.
      // For example, if you want to define custom colors:
      // colors: {
      //   yourPurple: '#6C2BD9',
      // },
    },
  },
  plugins: [],
}