<div class="mt-6 relative" x-data="{ copied: false }">
    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Example response</h4>

    <button
        x-on:click="navigator.clipboard.writeText($refs.code.textContent); copied = true; JDVToast.success('Copied Successfully'); setTimeout(() => copied = false, 2000);"
        class="absolute top-14 right-4 bg-gray-700 hover:bg-gray-600 text-gray-300 text-xs font-semibold py-1 px-2 rounded-md transition-all"
        title="Copy to clipboard"
        >
        <span x-show="!copied" class="flex items-center">
            <i class="fa fa-copy mr-1"></i>
            Copy
        </span>
        <span x-show="copied" class="flex items-center text-emerald-400">
            <i class="fa fa-check mr-1"></i>
            Copied!
        </span>
    </button>
    
    <pre class="!rounded-lg !mt-0"><code class="language-json" x-ref="code">{{ $slot }}</code></pre>
</div>