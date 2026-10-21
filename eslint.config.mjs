import { dirname } from "path";
import { fileURLToPath } from "url";
import { FlatCompat } from "@eslint/eslintrc";

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

const compat = new FlatCompat({
  baseDirectory: __dirname,
});

const eslintConfig = [
  ...compat.extends("next/core-web-vitals", "next/typescript"),
  {
    rules: {
      // The site keeps the original markup and stylesheets, so plain <img> and <link> tags are intentional.
      "@next/next/no-img-element": "off",
      "@next/next/no-css-tags": "off",
      "@next/next/no-page-custom-font": "off",
      "@next/next/no-html-link-for-pages": "off",
    },
  },
  {
    ignores: ["node_modules/**", "studio/**", "vendor/**", ".next/**", "out/**", "build/**", "next-env.d.ts", "public/**"],
  },
];

export default eslintConfig;
