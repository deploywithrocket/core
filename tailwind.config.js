const colors = require('tailwindcss/colors')

module.exports = {
  purge: {
    // enabled: true,
    content: [
      "app/**/*.php",
      "resources/**/*.html",
      "resources/**/*.js",
      "resources/**/*.jsx",
      "resources/**/*.ts",
      "resources/**/*.tsx",
      "resources/**/*.php",
      "resources/**/*.vue",
      "resources/**/*.twig",
    ],
    options: {
      // defaultExtractor: (content) => content.match(/[\w-/.:]+(?<!:)/g) || [],
      whitelistPatterns: [/-active$/, /-enter$/, /-leave-to$/, /show$/],
    },
  },
  theme: {
    extend: {
      colors: {
        gray: colors.trueGray,
        orange: colors.orange,
        pink: colors.pink,
      },
      screens: {
        xl: "1140px",
      },
    },
    container: {
      center: true,
      padding: "1rem",
    },
  },
  variants: {},
  plugins: [
    require('@tailwindcss/custom-forms')
  ],
};
