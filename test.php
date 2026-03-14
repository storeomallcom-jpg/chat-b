<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مُولّد التوثيق - النسخة المعزولة</title>
    <style>
        body { font-family: sans-serif; background: #0f172a; color: #f8fafc; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; margin: 0; padding: 20px; box-sizing: border-box;}
        .card { background: #1e293b; padding: 2rem; border-radius: 1rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5); width: 100%; max-width: 800px; text-align: center; }
        h2 { margin-top: 0; color: #3b82f6; }
        
        .file-upload-box { background: #0f172a; border: 1px dashed #475569; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem; transition: border-color 0.3s; }
        .file-upload-box:hover { border-color: #3b82f6; }
        input[type="file"] { color: #94a3b8; width: 100%; cursor: pointer; }
        
        button { background: #3b82f6; color: white; border: none; padding: 1rem; border-radius: 0.5rem; cursor: pointer; font-weight: bold; width: 100%; font-size: 1.1rem; transition: background 0.3s; }
        button:hover { background: #2563eb; }
        button:disabled { background: #64748b; cursor: not-allowed; }
        
        /* جعلنا مربع النتيجة أكبر ليتسع للتوثيق ويدعم اتجاه اليسار لليمين لأن التوثيق بالإنجليزية */
        #result { margin-top: 1.5rem; text-align: left; background: #0f172a; padding: 1.5rem; border-radius: 0.5rem; border: 1px solid #334155; min-height: 150px; white-space: pre-wrap; font-size: 0.95rem; overflow-x: auto; font-family: monospace; direction: ltr; }
        .status { margin-bottom: 1.5rem; font-size: 0.9rem; color: #94a3b8; }
    </style>
</head>
<body>

    <div class="card">
        <h2>⚙️ مُولّد التوثيق (اختبار النظام المعزول)</h2>
        <p class="status">هذا الملف يقرأ الكود ويرسله مباشرة إلى `api.php` لتوليد التوثيق (بدون Supabase).</p>
        
        <div class="file-upload-box">
            <input type="file" id="codeFile" accept=".js,.php,.html,.css,.py,.java,.cpp,.txt">
        </div>
        
        <button id="generateBtn">🚀 توليد README الآن</button>
        
        <div id="result">سيظهر توثيق Markdown هنا...</div>
    </div>

    <script>
        document.getElementById('generateBtn').addEventListener('click', async () => {
            const btn = document.getElementById('generateBtn');
            const resultDiv = document.getElementById('result');
            const fileInput = document.getElementById('codeFile');
            
            if (!fileInput.files.length) {
                alert("يرجى اختيار ملف كود أولاً!");
                return;
            }

            const file = fileInput.files[0];
            btn.disabled = true;
            resultDiv.innerText = "⏳ جاري قراءة الملف والتواصل مع الذكاء الاصطناعي... (قد يستغرق بضع ثوانٍ)";
            resultDiv.style.color = "#fbbf24"; // لون أصفر للانتظار

            try {
                // قراءة محتوى الملف
                const code = await file.text();
                // قص الكود لـ 8000 حرف لتجنب مشاكل حجم الطلب
                const snippet = code.substring(0, 8000); 

                // بناء التعليمات للذكاء الاصطناعي (نفس التعليمات القديمة)
                const systemPrompt = "You are a world-class Senior Technical Writer. Generate a professional README.md for the provided code. Use emojis, include: Overview, Features, Quick Start, Architecture. Output ONLY raw Markdown.";
                const userPrompt = "Filename: " + file.name + "\n\nCode:\n" + snippet;

                // إرسال الطلب إلى الخادم المحلي الخاص بك
                const response = await fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        messages: [
                            { role: 'system', content: systemPrompt },
                            { role: 'user', content: userPrompt }
                        ]
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    const aiMessage = data.choices[0].message.content;
                    resultDiv.innerText = aiMessage; // عرض النص بصيغة Markdown الخام
                    resultDiv.style.color = "#4ade80"; // لون أخضر للنجاح
                } else {
                    resultDiv.innerText = `❌ خطأ السيرفر ${response.status}:\n${data.error?.message || 'الطلب فشل، تحقق من مفتاح API في api.php'}`;
                    resultDiv.style.color = "#f87171"; // لون أحمر للخطأ
                }
            } catch (err) {
                resultDiv.innerText = "❌ فشل الاتصال: " + err.message + "\n(تأكد أن هذا الملف موجود في نفس مجلد api.php)";
                resultDiv.style.color = "#f87171";
            } finally {
                btn.disabled = false;
            }
        });
    </script>
</body>
</html>