# AutoDoc — Deployment Guide

AI-powered README generator. Upload code → get production-ready docs in seconds.
Powered by **Qwen2.5-32B via Groq** · Auth & billing via **Supabase** · Crypto payments via **MetaMask**.

---

## 📁 File Structure

```
autodoc/
├── index.html          ← Full UI (landing + auth + dashboard)
├── main.js             ← Client-side logic (auth, AI calls, Web3 payments)
├── api.php             ← 🔒 Secure server-side proxy — AI key lives here ONLY
├── test.php            ← Standalone test page (bypass Supabase)
└── supabase-setup.sql  ← Run once in Supabase SQL Editor
```

---

## 🚀 Setup (5 minutes)

### Step 1 — Supabase Database

1. Go to [supabase.com](https://supabase.com) → your project → **SQL Editor**
2. Paste the contents of `supabase-setup.sql` and click **Run**
3. This creates: `profiles`, `transactions` tables + `deduct_credits` / `add_credits` RPCs + auto-signup trigger

Your Supabase credentials are already wired into `main.js`:
- **URL:** `https://cfltxffuoodpzxigfpgo.supabase.co`
- **Anon key:** already embedded

### Step 2 — Groq API Key

1. Get a free key at [console.groq.com](https://console.groq.com)
2. Open `api.php` and replace `YOUR_GROQ_API_KEY_HERE`:

```php
define('GROQ_API_KEY', 'gsk_xxxxxxxxxxxxxxxxxxxxxxxx');
```

> ⚠️ Never paste the key in `main.js` or `index.html` — it must stay server-side.

### Step 3 — Deploy to a PHP Host

Upload all files to any PHP 7.4+ web host (cPanel, Plesk, VPS, etc.).  
The four files must be in the **same directory**.

Minimum server requirements:
- PHP 7.4+
- cURL extension enabled (`allow_url_fopen` or cURL — the proxy uses cURL)
- HTTPS (required for MetaMask)

---

## 💳 Payments

Users top up with **$5 USDT** on **Polygon** or **BSC** via MetaMask.  
$10.00 in credits is added (2× multiplier), covering 20 README generations at $0.50 each.

Receiving wallet is set in `main.js`:
```js
receiver: '0xfF82D591F726eF56313EF958Bb7d7D85866C4E8B'
```
Change this to your own wallet address.

---

## 🔗 Referral Program

- Every user gets a unique referral link on signup
- When a friend signs up via the link: **both get +$5 credit** automatically
- Logic is handled by the `handle_new_user` trigger in Supabase

---

## 🧪 Testing Without Supabase

Open `test.php` in your browser. It reads a file and calls `api.php` directly,  
bypassing auth and credits — useful for verifying the AI proxy works.

---

## 🔒 Security Notes

- `api.php` validates all input before forwarding to Groq
- CORS is set to `*` by default — change `ALLOWED_ORIGIN` to your domain in production
- The Supabase anon key is safe to expose client-side (Row Level Security enforces access)
- Never expose your Groq API key in client-side code
