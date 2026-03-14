/**
 * AutoDoc — Central Configuration
 * All keys, addresses, pricing, and global settings live here.
 * ⚠️  The GROQ API key is NEVER stored here — it lives exclusively in gate.php
 */

const APP_CONFIG = Object.freeze({

  /* ── SUPABASE ────────────────────────────────────────────────── */
  supabase: {
    url: 'https://ebqfpbascmshvwdgawns.supabase.co',
    anonKey: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImVicWZwYmFzY21zaHZ3ZGdhd25zIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzM1MDk2ODcsImV4cCI6MjA4OTA4NTY4N30.z3teVIDb30sRfogMuSNMnDNd3tnz_IhGfJGdRZ4QRKY',
  },

  /* ── AI PROXY ────────────────────────────────────────────────── */
  proxyUrl: 'gate.php',

  /* ── PRICING ─────────────────────────────────────────────────── */
  pricing: {
    costPerDoc:    
