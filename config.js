/**
 * AutoDoc — Central Configuration
 * All keys, addresses, pricing, and global settings live here.
 * ⚠️  The GROQ API key is NEVER stored here — it lives exclusively in gate.php
 */

const APP_CONFIG = Object.freeze({

  /* ── SUPABASE ────────────────────────────────────────────────── */
  supabase: {
    url: 'https://cfltxffuoodpzxigfpgo.supabase.co',
    anonKey: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImNmbHR4ZmZ1b29kcHp4aWdmcGdvIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzE2MzI5NzMsImV4cCI6MjA4NzIwODk3M30.jtqQq5YhxtoC-yG_xycbP76o1TcFc0mhe3AvPhgYGZA',
  },

  /* ── AI PROXY ────────────────────────────────────────────────── */
  proxyUrl: 'gate.php',

  /* ── PRICING ─────────────────────────────────────────────────── */
  pricing: {
    costPerDoc:    