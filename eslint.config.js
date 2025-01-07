import pluginJs from "@eslint/js";
import prettier from 'eslint-config-prettier';
import unusedImports from 'eslint-plugin-unused-imports';
import globals from "globals";

/** @type {import('eslint').Linter.Config[]} */
export default [
  {files: ["**/*.{js,mjs,cjs,jsx,svelte}"]},
  {languageOptions: { globals: globals.browser }},
  {
    ignores: [
      "node_modules",
      "public",
      "resources",
      "storage",
      "tests",
      "vendor",
      "bootstrap",
      "webpack.mix.js",
      "tailwind.config.js",
    ],
  },
  prettier,
  pluginJs.configs.recommended,
  {
    plugins: {
      'unused-imports': unusedImports,
    },
    rules: {
      'no-unused-vars': 'off',
      'unused-imports/no-unused-imports': 'error',
      'unused-imports/no-unused-vars': [
        'warn',
        {
          vars: 'all',
          varsIgnorePattern: '^_',
          args: 'after-used',
          argsIgnorePattern: '^_',
        },
      ],
    },
  },
];