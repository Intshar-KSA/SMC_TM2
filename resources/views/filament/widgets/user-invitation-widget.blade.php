<div class="p-4 bg-white dark:bg-gray-800 shadow rounded-lg">
    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Your invitation link') }}</h3>
    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Click the button below to copy the invitation link') }}</p>

    <div class="mt-2 flex items-center">
        <input type="text"
               id="invitation-link"
               value="{{ url('/request-add-employee/' . auth()->user()->id) }}"
               class="form-input border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded-md w-full text-sm"
               readonly>
        <button onclick="copyToClipboard()"
                class="ml-2 bg-blue-500 hover:bg-blue-600 text-white dark:bg-blue-600 dark:hover:bg-blue-700 px-3 py-2 rounded-md text-sm">
            Copy
        </button>
    </div>
    <p id="copy-feedback" class="text-green-500 text-sm mt-2 hidden">Link copied to clipboard!</p>
</div>

<script>
    function copyToClipboard() {
        const inputElement = document.getElementById('invitation-link');
        const feedback = document.getElementById('copy-feedback');

        // تحديد النص داخل الحقل
        inputElement.select();
        inputElement.setSelectionRange(0, 99999); // للأجهزة المحمولة

        // تنفيذ أمر النسخ
        const successful = document.execCommand('copy');

        // عرض ملاحظة عند النسخ الناجح
        if (successful) {
            feedback.classList.remove('hidden'); // عرض الرسالة
            setTimeout(() => feedback.classList.add('hidden'), 2000); // إخفاء الرسالة بعد 2 ثانية
        } else {
            alert('Failed to copy the link. Please try again.');
        }
    }
</script>

