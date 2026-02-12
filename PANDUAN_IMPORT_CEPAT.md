# Panduan Cepat Fitur Import Master Item

## 📋 Ringkasan
Fitur ini memungkinkan Anda mengimpor data item dalam jumlah besar dari file Excel, dengan otomasi penuh untuk kategori, unit, dan kode item.

## 🚀 Mulai Cepat

### Langkah 1: Buka Halaman Import
1. Masuk ke panel Admin
2. Buka **Master Data → Items**
3. Klik tombol **Import Excel** (warna amber)

### Langkah 2: Download Template (Opsional tapi Recommended)
1. Klik tombol **📥 Download Template**
2. Buka file di Excel
3. Lihat struktur kolom yang benar dengan contoh data

### Langkah 3: Siapkan Data Excel Anda
Buat file Excel dengan struktur ini:

| item_name | category_name | unit_name | condition | min_stock | description |
|-----------|---------------|-----------|-----------|-----------|-------------|
| Laptop Dell | Electronics | Piece | good | 5 | Laptop 15 inch Core i7 |
| Kertas A4 | Office | Ream | good | 10 | Kertas putih 80gsm |

**Catatan:**
- Kolom `description`, `category_description`, `unit_description` opsional
- `condition` hanya boleh: `good` atau `damaged`
- `min_stock` harus angka
- `item_name` harus unik (tidak boleh duplikat)

### Langkah 4: Upload File
1. Pilih file dengan salah satu cara:
   - Klik pada area upload
   - Drag & drop file ke area upload
2. File yang didukung: `.xlsx`, `.xls`, `.csv`

### Langkah 5: Proses Import
1. Klik tombol **Import Items** (biru)
2. Tunggu proses selesai
3. Lihat hasil di halaman items

### Langkah 6: Verifikasi Hasil
- ✅ Jika berhasil: Pesan "X item(s) imported successfully"
- ⚠️ Jika ada error: Daftar item yang gagal beserta alasan

## 🎯 Apa yang Otomatis?

### ✅ Item Code Auto-Generated
Sistem membuat kode otomatis dengan format: `{CATEGORY_CODE}-ITM-{NUMBER}`

**Contoh:**
```
Electronics → ELE-ITM-001, ELE-ITM-002
Office      → OFF-ITM-001, OFF-ITM-002
```

### ✅ Kategori Auto-Created
Jika kategori belum ada, sistem membuat otomatis:
- Nama: Sesuai input Anda
- Kode: 3 huruf pertama (uppercase)
- Deskripsi: Dari kolom category_description (jika ada)

**Contoh:**
```
Input "Electronics" → Kode "ELE" (otomatis)
Input "Office Supply" → Kode "OFF" (otomatis)
```

### ✅ Unit Auto-Created
Jika unit belum ada, sistem membuat otomatis:
- Nama: Sesuai input Anda
- Kode: 2 huruf pertama (uppercase)
- Deskripsi: Dari kolom unit_description (jika ada)

**Contoh:**
```
Input "Piece" → Kode "PI" (otomatis)
Input "Box"   → Kode "BO" (otomatis)
```

## ❌ Error Handling

### Jika Terjadi Error
Sistem akan:
1. Menampilkan item yang gagal dengan alasan
2. Item yang berhasil tetap disimpan ✅
3. Item yang gagal dapat diperbaiki dan di-import ulang

### Contoh Error Message

| Error | Penyebab | Solusi |
|-------|----------|--------|
| "Missing required field: min_stock" | Field min_stock kosong | Isi field min_stock dengan angka |
| "Invalid condition: 'excellent'" | Nilai condition salah | Gunakan hanya "good" atau "damaged" |
| "Item name 'Laptop' already exists" | Item name sudah ada | Gunakan nama item yang unik |
| "Min stock cannot be negative" | Nilai min_stock negatif | Gunakan angka positif atau 0 |

## 📊 Kolom Excel Lengkap

### Wajib (Required) ⭐
- `item_name` - Nama item (max 255 karakter)
- `category_name` - Nama kategori
- `unit_name` - Nama satuan/unit
- `condition` - "good" atau "damaged"
- `min_stock` - Angka minimum stok

### Opsional (Optional) 
- `description` - Deskripsi item
- `category_description` - Deskripsi kategori (untuk kategori baru)
- `unit_description` - Deskripsi unit (untuk unit baru)

## 💡 Tips & Trik

### Tip 1: Gunakan Template
```
Selalu download template dari aplikasi untuk memastikan 
struktur kolom yang benar
```

### Tip 2: Validasi Sebelum Upload
```
Periksa data di Excel sebelum upload:
- Tidak ada baris kosong di tengah data
- Tidak ada spasi berlebih di awal/akhir
- Format condition hanya "good" atau "damaged"
```

### Tip 3: Import Bertahap
```
Untuk data besar (>500 rows), lebih baik:
1. Pisah jadi beberapa file
2. Import satu per satu
3. Verifikasi hasil setiap kali
```

### Tip 4: Backup Pertama
```
Sebelum import data besar, backup database Anda dulu
```

## 🔍 Verifikasi Hasil

Setelah import, periksa di halaman Items:

1. **Cek Item Code**
   - Format harus: `{CATEGORY_CODE}-ITM-{NUMBER}`
   - Contoh: `ELE-ITM-001`

2. **Cek Relasi**
   - Kategori terhubung dengan benar
   - Unit terhubung dengan benar

3. **Cek Nilai**
   - Item name sesuai input
   - Condition: good atau damaged
   - Min stock: angka yang benar
   - Stock: 0 (default, dapat diubah kemudian)

## ⚙️ Fitur Lanjutan

### Transaction Rollback
Sistem menggunakan database transaction:
- **Jika error pada satu row** → Row tersebut tidak disimpan
- **Rows lain** → Tetap disimpan
- **Tidak ada global rollback** → Data yang valid tidak hilang

### Batch Processing
- Sistem memproses 100 rows sekaligus untuk performa optimal
- Cocok untuk import data besar (1000+ items)

## 🆘 Troubleshooting

### Masalah: Upload Gagal "file must be mimes"
**Solusi:**
- Pastikan file adalah Excel asli (.xlsx, .xls) atau CSV
- Jangan upload file yang sudah dikompres

### Masalah: Import Lambat
**Solusi:**
- Kurangi jumlah rows per file (pisah menjadi beberapa file)
- Upload saat tidak ada aktivitas tinggi

### Masalah: Item Code Tidak Sesuai
**Solusi:**
- Pastikan kategori unik (tidak ada duplikat nama kategori)
- Cek kode kategori yang di-generate di halaman Categories

### Masalah: Kategori/Unit Tidak Terdeteksi
**Solusi:**
- Refresh halaman browser
- Pastikan spelling nama kategori/unit sama dengan input Excel
- Cek apakah sudah ada kategori/unit dengan nama serupa

## 📞 Kontak & Support

Untuk masalah atau pertanyaan:
- Hubungi tim development
- Lihat dokumentasi lengkap: `docs/IMPORT_FEATURE.md`
- Check file import test: `tests/Feature/ItemImportTest.php`

## 📋 Checklist Pre-Import

Sebelum import, pastikan:

- [ ] File dalam format .xlsx, .xls, atau .csv
- [ ] Kolom header sesuai template
- [ ] Tidak ada baris kosong di tengah data
- [ ] `condition` hanya "good" atau "damaged"
- [ ] `min_stock` adalah angka
- [ ] `item_name` tidak ada yang duplikat
- [ ] Database sudah di-backup (untuk data besar)
- [ ] Sudah test dengan data kecil dulu (untuk data besar)

## 🎓 Contoh Kasus

### Contoh 1: Import Kategori Baru
**File Excel:**
```
item_name | category_name | unit_name | condition | min_stock
Laptop    | Electronics   | Piece     | good      | 5
Monitor   | Electronics   | Piece     | good      | 3
```

**Hasil:**
- ✅ Item "Laptop" dan "Monitor" dibuat
- ✅ Kategori "Electronics" (kode: ELE) dibuat otomatis
- ✅ Unit "Piece" (kode: PI) dibuat otomatis
- ✅ Item code: `ELE-ITM-001`, `ELE-ITM-002`

### Contoh 2: Import Mix (Existing + New Category)
**Database sudah punya:**
- Category: Electronics (ELE)
- Unit: Piece (PI)

**File Excel:**
```
item_name | category_name | unit_name | condition | min_stock
Keyboard  | Electronics   | Piece     | good      | 5
Printer   | Office        | Box       | damaged   | 2
```

**Hasil:**
- ✅ Keyboard: Gunakan kategori existing (ELE), item code `ELE-ITM-003`
- ✅ Printer: Buat kategori baru "Office" (OFF), buat unit baru "Box" (BO)
- ✅ Printer item code: `OFF-ITM-001`

## 📈 Kapasitas & Batasan

- **Max file size:** 2MB (default Laravel)
- **Max rows per file:** Tidak ada limit, tapi recommended max 1000 untuk performa
- **Batch size:** 100 rows per batch
- **Supported formats:** .xlsx, .xls, .csv
- **Success rate:** 100% untuk data yang valid

---

**Versi:** 1.0.0  
**Last Update:** February 2, 2026  
**Status:** Ready to Use ✅
