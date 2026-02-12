# Dokumentasi Fitur Import Master Item

## Ringkasan Fitur

Fitur import Excel telah diintegrasikan ke dalam aplikasi inventory untuk memudahkan pengguna menambahkan banyak item sekaligus. Sistem ini dirancang dengan fitur keamanan dan otomasi yang komprehensif.

## Fitur Utama

### 1. Import Data Excel
- Upload file Excel (.xlsx, .xls, .csv)
- Batch processing untuk performa optimal
- Validasi data real-time

### 2. Auto-Generate Item Code
Sistem secara otomatis menghasilkan kode item unik dengan format:
```
{CATEGORY_CODE}-ITM-{SEQUENTIAL_NUMBER}
```

**Contoh:**
- `ELE-ITM-001` (untuk item pertama kategori Electronics)
- `OFF-ITM-001` (untuk item pertama kategori Office Supplies)
- `OFF-ITM-002` (untuk item kedua kategori Office Supplies)

### 3. Manajemen Relasi Otomatis

#### Category Management
- Jika kategori belum ada di database, sistem membuat kategori baru secara otomatis
- Kode kategori di-generate dari 3 huruf pertama nama kategori (uppercase)
- Contoh: "Electronics" → kode "ELE"

#### Unit Management
- Jika unit belum ada di database, sistem membuat unit baru secara otomatis
- Kode unit di-generate dari 2 huruf pertama nama unit (uppercase)
- Contoh: "Piece" → kode "PI"

### 4. Validasi Data Komprehensif
- Validasi field required (item_name, category_name, unit_name, condition, min_stock)
- Validasi format condition (hanya "good" atau "damaged")
- Validasi numeric fields (min_stock harus angka ≥ 0)
- Validasi uniqueness item_name

### 5. Transaction Rollback
Menggunakan database transaction untuk setiap row:
- Jika terjadi error pada satu row, hanya row tersebut yang gagal
- Row lainnya tetap diproses
- Error dipantau dan dilaporkan kepada user

## Struktur File Excel

### Kolom Required (Wajib)

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| item_name | Text | Nama item (max 255 karakter) |
| category_name | Text | Nama kategori |
| unit_name | Text | Nama unit/satuan |
| condition | Text | "good" atau "damaged" |
| min_stock | Number | Minimum stok (angka) |

### Kolom Optional (Opsional)

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| description | Text | Deskripsi item |
| category_description | Text | Deskripsi kategori (jika dibuat baru) |
| unit_description | Text | Deskripsi unit (jika dibuat baru) |

### Contoh Data Excel

```
| item_name | category_name | unit_name | condition | min_stock | description |
|-----------|---------------|-----------|-----------|-----------|-------------|
| Laptop    | Electronics   | Piece     | good      | 5         | Dell Laptop |
| Kertas A4 | Office        | Ream      | good      | 10        | Kertas putih|
```

## Cara Menggunakan

### 1. Akses Halaman Import
- Buka halaman Master Item → klik tombol "Import Excel"
- URL: `/admin/items/import/show`

### 2. Download Template (Opsional)
- Klik tombol "📥 Download Template" untuk mendapatkan template Excel
- Template berisi struktur kolom yang benar dengan contoh data

### 3. Persiapkan File Excel
- Buat atau edit file Excel sesuai dengan struktur template
- Pastikan kolom header sesuai dengan nama kolom yang ditentukan
- Isi data sesuai dengan validasi yang berlaku

### 4. Upload File
- Pilih file Excel dengan 2 cara:
  - Klik pada area upload
  - Drag and drop file ke area upload
- File size harus sesuai dengan limit Laravel (default 2MB)

### 5. Proses Import
- Klik tombol "Import Items"
- Sistem akan memproses data dan menampilkan hasilnya

### 6. Review Hasil
- Jika berhasil: Halaman akan menampilkan pesan "X item(s) imported successfully"
- Jika ada error: Pesan error akan ditampilkan dengan detail baris dan alasannya
- Redirect ke halaman list items untuk melihat data yang diimpor

## Handling Error

### Jenis Error yang Mungkin

#### 1. Data Validation Error
```
Item name 'Laptop' already exists in database
```
**Solusi:** Gunakan nama item yang unik

#### 2. Invalid Condition
```
Invalid condition: 'excellent'. Must be 'good' or 'damaged'
```
**Solusi:** Isi condition hanya dengan "good" atau "damaged"

#### 3. Missing Required Field
```
Missing required field: min_stock
```
**Solusi:** Pastikan semua kolom required terisi

#### 4. Invalid Numeric Value
```
Min stock cannot be negative
```
**Solusi:** Isi min_stock dengan angka positif

### Error Reporting
- Error ditampilkan dengan daftar item yang gagal beserta alasannya
- Item yang berhasil tetap disimpan (tidak rollback global)
- User dapat memperbaiki item yang error dan re-import

## Contoh Implementasi

### File Excel Template
Buka file `items-import-template.xlsx` yang dapat didownload dari halaman import.

Struktur:
```
Header Row:
item_name | category_name | category_description | unit_name | unit_description | condition | min_stock | description

Data Row:
Laptop | Electronics | Electronic equipment | Piece | Individual unit | good | 5 | Dell Laptop 15"
```

## Technical Details

### Class: ItemsImport
- **File:** `app/Imports/ItemsImport.php`
- **Implements:**
  - `ToModel` - Mengubah setiap row menjadi model Item
  - `WithHeadingRow` - Menggunakan header row sebagai key
  - `WithBatchInserts` - Batch insert untuk performa
  - `WithChunkReading` - Chunk reading untuk memory efficiency

### Key Methods
- `model()` - Process setiap row data
- `validateRow()` - Validasi field required
- `generateCategoryCode()` - Generate kode kategori
- `generateUnitCode()` - Generate kode unit
- `generateItemCode()` - Generate kode item

### Controller Methods (ItemController)
- `showImport()` - Tampilkan form import (GET)
- `import()` - Process upload file (POST)
- `downloadTemplate()` - Download template Excel (GET)

### Routes
```php
// Show import form
Route::get('items/import/show', ...)->name('items.import.show');

// Process import
Route::post('items/import', ...)->name('items.import');

// Download template
Route::get('items/download-template', ...)->name('items.download-template');
```

## Best Practices

1. **Validasi Local Dulu**
   - Periksa format Excel sebelum upload
   - Pastikan tidak ada baris kosong di tengah data

2. **Gunakan Template**
   - Selalu download dan gunakan template yang disediakan
   - Ini memastikan struktur kolom yang benar

3. **Testing pada Dev**
   - Test dengan data kecil terlebih dahulu
   - Verifikasi hasil sebelum import data besar

4. **Backup Data**
   - Backup database sebelum import data besar
   - Ini membantu jika terjadi masalah

5. **Review Hasil**
   - Cek halaman items setelah import
   - Verifikasi item code yang generated
   - Pastikan relasi category dan unit benar

## Limitations & Notes

- Maximum file size: sesuai Laravel config (default 2MB)
- Batch size: 100 rows per batch
- Chunk size: 100 rows per chunk reading
- Supported formats: .xlsx, .xls, .csv
- Tidak support image upload via import (hanya nama item)
- Stock diset default 0, dapat diupdate manual setelahnya

## Troubleshooting

### Upload gagal dengan error "file must be mimes"
- Pastikan file adalah Excel (.xlsx, .xls) atau CSV yang valid
- Jangan upload file yang sudah dikompres (zip)

### Import lambat
- Kurangi jumlah data per file (pisah menjadi beberapa file)
- Upload di waktu off-peak

### Item code tidak sesuai ekspektasi
- Pastikan kategori unique
- Cek kode kategori yang di-generate

## Kontak & Support

Untuk pertanyaan atau masalah terkait fitur import:
- Hubungi tim development
- Buat issue di repository project
