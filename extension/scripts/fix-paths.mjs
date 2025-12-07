#!/usr/bin/env node
import { readFileSync, writeFileSync } from 'fs';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const htmlPath = join(__dirname, '../dist/index.html');

try {
  let html = readFileSync(htmlPath, 'utf8');
  
  // Fix absolute paths to relative paths
  html = html.replace(/src="\/popup\.js"/g, 'src="./popup.js"');
  html = html.replace(/href="\/popup\.css"/g, 'href="./popup.css"');
  
  writeFileSync(htmlPath, html, 'utf8');
  console.log('Fixed HTML paths');
} catch (err) {
  console.error('Error fixing HTML paths:', err.message);
  process.exit(1);
}
