@extends('layouts.admin')

@section('title', 'Import Items')

@section('content')

<div class="h-screen flex flex-col bg-gray-50 overflow-hidden">
    <!-- Page Header -->
    <div class="bg-white border-b border-gray-200 px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 flex-shrink-0">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Import Items from Excel</h1>
            <!--<p class="text-gray-600 mt-1">Upload an Excel file to import multiple items at once</p>-->
        </div>

        <a href="{{ route('admin.items.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition-colors whitespace-nowrap">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Items
        </a>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 overflow-y-auto px-6 py-6">
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">Import failed:</p>
                        @foreach ($errors->all() as $error)
                            <p class="text-sm text-red-700 mt-1">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if(session()->has('success'))
            <div id="successAlert" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
                        <p class="text-xs text-green-700 mt-2">Redirecting to items list in <span id="countdown" class="font-bold">3</span> seconds...</p>
                        <a href="{{ route('admin.items.index') }}" class="inline-block mt-3 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
                            Go to Items Now
                        </a>
                    </div>
                </div>
            </div>
        @elseif(session()->has('warning'))
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-semibold text-yellow-800">{{ session('warning') }}</p>
                        @if(session()->has('import_errors') && count(session('import_errors')) > 0)
                            <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded">
                                <p class="text-sm font-medium text-yellow-800">Rows that failed:</p>
                                <ul class="mt-2 text-sm text-yellow-700 list-disc list-inside max-h-40 overflow-y-auto">
                                    @foreach(session('import_errors') as $err)
                                        <li>{{ $err['item_name'] ?? 'Unknown' }}: {{ $err['error'] }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <a href="{{ route('admin.items.index') }}" class="inline-block mt-3 px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition">
                            Go to Items
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Upload Form Card -->
        <div class="mx-auto w-full max-w-4xl">
            <div class="bg-white rounded-lg border border-gray-200 p-8 md:p-12">
                <form method="POST" action="{{ route('admin.items.import') }}" enctype="multipart/form-data" class="space-y-6 h-full flex flex-col">
                    @csrf

                    <!-- File Upload Area -->
                    <div class="flex-1 flex flex-col">
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            Select Excel File <span class="text-red-600">*</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 md:p-12 text-center hover:border-blue-500 transition-colors cursor-pointer flex-1 flex flex-col items-center justify-center"
                             id="dropZone">
                            <p class="text-sm text-gray-500 mb-4">Choose a file or drag &amp; drop it here</p>
                            <button type="button" id="browseBtn" class="px-4 py-2 bg-white border rounded shadow-sm hover:bg-gray-50">Browse File</button>
                            <p class="text-xs text-gray-400 mt-3">Supported formats: XLSX, XLS, CSV — up to 10MB</p>
                            <input type="file" name="file" id="fileInput" class="hidden"
                                   accept=".xlsx,.xls,.csv" @error('file') aria-invalid="true" @enderror>
                        </div>
                        <p class="mt-3 text-sm text-gray-600 font-medium" id="fileName"></p>
                        <div id="fileList" class="mt-3"></div>
                        <div id="fileAlert" class="mt-2"></div>

                        @error('file')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preview Section -->
                    <div class="flex-1 flex flex-col min-h-0">
                        <label class="block text-sm font-semibold text-gray-900 mb-3">File Preview</label>
                        <div id="previewContainer" class="flex-1 overflow-auto rounded-md border border-gray-200 bg-gray-50 p-4">
                            <p class="text-sm text-gray-500 text-center py-8">Preview will appear here after selecting a file</p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-3 flex-shrink-0">
                        <button type="submit"
                                id="submitBtn"
                                class="flex-1 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 active:bg-blue-800 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 animate-spin hidden" id="loadingSpinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span id="submitText">Import Items</span>
                        </button>
                        <a href="{{ route('admin.items.index') }}"
                           class="px-6 py-3 bg-gray-200 text-gray-900 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SheetJS (xlsx) for client-side preview -->
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const fileName = document.getElementById('fileName');
    const previewContainer = document.getElementById('previewContainer');

    // Click to upload
    dropZone.addEventListener('click', () => fileInput.click());

    // Drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    // Browse button behaviour
    const browseBtn = document.getElementById('browseBtn');
    if (browseBtn) browseBtn.addEventListener('click', () => fileInput.click());

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        updateFileName();
    }

    fileInput.addEventListener('change', updateFileName);

    function updateFileName() {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            fileName.textContent = '✓ ' + file.name + ' (' +
                (file.size / 1024).toFixed(2) + ' KB)';
            fileName.classList.add('text-green-600', 'font-medium');
            generatePreview(file);
            checkFileDuplicate(file);
            renderFileList(file);
        } else {
            fileName.textContent = '';
            fileName.classList.remove('text-green-600', 'font-medium');
            previewContainer.innerHTML = '<p class="text-sm text-gray-500 text-center py-8">Preview will appear here after selecting a file</p>';
            document.getElementById('fileAlert').innerHTML = '';
            renderFileList();
        }
    }

    function renderFileList(file) {
        const list = document.getElementById('fileList');
        if (!list) return;
        if (!file) {
            list.innerHTML = '';
            return;
        }

        const sizeKb = (file.size / 1024).toFixed(2) + ' KB';
        list.innerHTML = `
            <div class="flex items-center justify-between bg-white border border-gray-200 rounded p-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10v10H7z"/></svg>
                    </div>
                    <div class="text-sm">
                        <div class="font-medium text-gray-800">${escapeHtml(file.name)}</div>
                        <div class="text-xs text-gray-500">${sizeKb}</div>
                    </div>
                </div>
                <div>
                    <button type="button" id="removeFileBtn" class="text-sm text-red-600 hover:underline">Remove</button>
                </div>
            </div>
        `;

        const removeBtn = document.getElementById('removeFileBtn');
        if (removeBtn) removeBtn.addEventListener('click', clearFile);
    }

    function clearFile() {
        fileInput.value = '';
        updateFileName();
        if (submitBtn) submitBtn.disabled = true;
        const alertEl = document.getElementById('fileAlert');
        if (alertEl) alertEl.innerHTML = '';
    }

    function generatePreview(file) {
        previewContainer.innerHTML = '<p class="text-sm text-gray-600 text-center py-8">Loading preview…</p>';
        const name = (file.name || '').toLowerCase();
        if (name.endsWith('.csv')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const text = e.target.result;
                const rows = parseCSVPreview(text);
                validateAndRender(rows);
            };
            reader.readAsText(file);
        } else if (name.endsWith('.xls') || name.endsWith('.xlsx')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, { type: 'array' });
                    const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                    const rows = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });
                    validateAndRender(rows);
                } catch (err) {
                    previewContainer.innerHTML = '<p class="text-sm text-red-600 text-center py-8">Unable to parse file for preview.</p>';
                }
            };
            reader.readAsArrayBuffer(file);
        } else {
            previewContainer.innerHTML = '<p class="text-sm text-gray-600 text-center py-8">Preview not available for this file type.</p>';
        }
    }

    const requiredHeaders = ['item_name','category_name','unit_name','condition','min_stock'];
    const submitBtn = document.querySelector('button[type="submit"]');

    function validateAndRender(rows) {
        renderPreviewTable(rows);
        // Validate header row
        const header = (rows && rows.length) ? rows[0].map(h => (h || '').toString().trim().toLowerCase()) : [];
        const missing = requiredHeaders.filter(h => !header.includes(h));

        const alertEl = document.getElementById('fileAlert');
        if (missing.length > 0) {
            alertEl.innerHTML = '<div class="p-3 rounded-md bg-red-50 border border-red-200 text-sm text-red-700">' +
                '<strong class="font-semibold">Invalid file:</strong> Missing required columns: ' + missing.join(', ') +
                '. Please use the import template.' +
                '</div>';
            if (submitBtn) submitBtn.disabled = true;
            fileName.classList.remove('text-green-600', 'font-medium');
        } else {
            alertEl.innerHTML = '<div class="p-3 rounded-md bg-green-50 border border-green-200 text-sm text-green-700">' +
                'File looks valid for import. You can proceed.' +
                '</div>';
            if (submitBtn) submitBtn.disabled = false;
            fileName.classList.add('text-green-600', 'font-medium');
        }
    }

    function checkFileDuplicate(file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', document.querySelector('input[name="_token"]').value);

        fetch('{{ route("admin.items.check-duplicate") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const alertEl = document.getElementById('fileAlert');
            if (data.isDuplicate) {
                alertEl.innerHTML = '<div class="p-3 rounded-md bg-yellow-50 border border-yellow-200 text-sm text-yellow-700">' +
                    '<strong class="font-semibold">⚠ File already imported:</strong> ' + data.message +
                    '</div>';
                if (submitBtn) submitBtn.disabled = true;
                fileName.classList.remove('text-green-600');
                fileName.classList.add('text-yellow-600');
            }
        })
        .catch(err => {
            console.error('Duplicate check error:', err);
        });
    }

    function parseCSVPreview(text) {
        const lines = text.split(/\r?\n/).filter(Boolean);
        return lines.slice(0, 50).map(line => {
            const parts = [];
            let current = '';
            let inQuotes = false;
            for (let i = 0; i < line.length; i++) {
                const ch = line[i];
                if (ch === '"') { inQuotes = !inQuotes; continue; }
                if (ch === ',' && !inQuotes) { parts.push(current); current = ''; continue; }
                current += ch;
            }
            parts.push(current);
            return parts;
        });
    }

    function renderPreviewTable(rows) {
        if (!rows || rows.length === 0) {
            previewContainer.innerHTML = '<p class="text-sm text-gray-600 text-center py-8">No data found in file.</p>';
            return;
        }
        const maxRows = 10;
        const displayRows = rows.slice(0, maxRows + 1);
        let html = '<table class="w-full text-xs border-collapse">';

        // header
        const header = displayRows[0];
        html += '<thead><tr class="bg-gray-200 text-left sticky top-0">';
        for (let c = 0; c < header.length; c++) html += '<th class="px-3 py-2 text-gray-800 border border-gray-300 font-semibold">' + escapeHtml(header[c] || '') + '</th>';
        html += '</tr></thead>';

        // body
        html += '<tbody>';
        for (let r = 1; r < displayRows.length; r++) {
            const row = displayRows[r];
            html += '<tr class="' + (r % 2 === 0 ? 'bg-white' : 'bg-gray-50') + '">';
            for (let c = 0; c < header.length; c++) html += '<td class="px-3 py-2 align-top border border-gray-300">' + escapeHtml(row[c] || '') + '</td>';
            html += '</tr>';
        }

        if (rows.length - 1 > maxRows) {
            html += '<tr class="bg-gray-100"><td class="px-3 py-2 text-gray-600 text-center font-semibold" colspan="' + (header.length || 1) + '">Showing first ' + maxRows + ' rows of ' + (rows.length - 1) + ' rows</td></tr>';
        }

        html += '</tbody></table>';
        previewContainer.innerHTML = html;
    }

    function escapeHtml(s) {
        return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    // Auto-redirect on successful import
    @if(session()->has('success'))
        let countdownValue = 3;
        const countdownEl = document.getElementById('countdown');
        const redirectInterval = setInterval(() => {
            countdownValue--;
            if (countdownEl) {
                countdownEl.textContent = countdownValue;
            }
            if (countdownValue <= 0) {
                clearInterval(redirectInterval);
                window.location.href = '{{ route("admin.items.index") }}';
            }
        }, 1000);
    @endif
});
</script>

@endsection
