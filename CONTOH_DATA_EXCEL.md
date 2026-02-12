# Contoh Data Excel untuk Import Master Item

## 📋 Struktur Basic

Struktur paling sederhana (hanya required fields):

```
| item_name    | category_name | unit_name | condition | min_stock |
|--------------|---------------|-----------|-----------|-----------|
| Laptop Dell  | Electronics   | Piece     | good      | 5         |
| Mouse Logitech| Electronics  | Piece     | good      | 10        |
| Keyboard     | Electronics   | Piece     | good      | 8         |
```

## 📦 Contoh 1: Electronics Equipment

**File: electronics-import.xlsx**

```
item_name                 | category_name | unit_name | condition | min_stock | description
--------------------------|---------------|-----------|-----------|-----------|----------------------------
Laptop Dell XPS 13        | Electronics   | Piece     | good      | 5         | Dell XPS 13 i7 16GB RAM
Laptop Asus Vivobook      | Electronics   | Piece     | good      | 3         | Asus Vivobook 15 Ryzen 5
Monitor LG 24"            | Electronics   | Piece     | good      | 4         | Monitor LG 24 inch IPS
Monitor Dell 27"          | Electronics   | Piece     | good      | 2         | Monitor Dell 27 inch 4K
Keyboard Mechanical       | Electronics   | Piece     | good      | 8         | RGB Mechanical Keyboard
Mouse Wireless Logitech   | Electronics   | Piece     | good      | 10        | Logitech MX Master
Webcam HD                 | Electronics   | Piece     | good      | 5         | Webcam 1080p USB
Speaker USB               | Electronics   | Piece     | good      | 6         | Speaker stereo USB powered
USB Hub 7 Port            | Electronics   | Piece     | good      | 3         | USB 3.0 Hub 7 port
Charging Cable Type C     | Electronics   | Box       | good      | 20        | Kabel charger USB Type C
```

**Expected Results:**
- Category: Electronics (auto-created with code "ELE")
- Unit: Piece (auto-created with code "PI"), Box (auto-created with code "BO")
- Item codes: ELE-ITM-001 to ELE-ITM-010

---

## 🏢 Contoh 2: Office Supplies

**File: office-supplies-import.xlsx**

```
item_name           | category_name   | unit_name | condition | min_stock | description
---------------------|-----------------|-----------|-----------|-----------|--------------------------------
Kertas A4 80gsm     | Office Supplies | Ream      | good      | 10        | Kertas putih 80gsm 500 lembar
Kertas A4 100gsm    | Office Supplies | Ream      | good      | 5         | Kertas putih premium 100gsm
Kertas Folio        | Office Supplies | Ream      | good      | 8         | Kertas folio 210x330 mm
Tinta Printer HP    | Office Supplies | Box       | good      | 15        | Tinta printer HP black
Tinta Printer Canon | Office Supplies | Box       | good      | 12        | Tinta printer Canon color
Ballpoint Biru      | Office Supplies | Box       | good      | 20        | Ballpoint biru 0.7mm
Ballpoint Merah     | Office Supplies | Box       | good      | 15        | Ballpoint merah 0.7mm
Pensil 2B           | Office Supplies | Box       | good      | 25        | Pensil kayu 2B
Map Plastik         | Office Supplies | Box       | good      | 30        | Map plastik A4
Stapler             | Office Supplies | Piece     | good      | 3         | Stapler besi kapasitas 25
Staples Box         | Office Supplies | Box       | good      | 10        | Staples 1000 pcs
```

**Expected Results:**
- Category: Office Supplies (auto-created with code "OFF")
- Units: Ream, Box, Piece (auto-created with codes "RE", "BO", "PI")
- Item codes: OFF-ITM-001 to OFF-ITM-011

---

## 🏗️ Contoh 3: Furniture & Fixtures

**File: furniture-import.xlsx**

```
item_name             | category_name | unit_name | condition | min_stock | description
-----------------------|---------------|-----------|-----------|-----------|---------------------------
Meja Kerja            | Furniture     | Piece     | good      | 2         | Meja kerja kayu 120x60cm
Kursi Kantor          | Furniture     | Piece     | good      | 4         | Kursi kantor ergonomis
Lemari Arsip          | Furniture     | Piece     | good      | 1         | Lemari arsip 4 laci
Rak Buku              | Furniture     | Piece     | good      | 3         | Rak buku 5 tingkat
Meja Rapat            | Furniture     | Piece     | damaged   | 1         | Meja rapat 240x80cm rusak
Papan Tulis           | Furniture     | Piece     | good      | 2         | Papan tulis magnetik
Lemari Gantung        | Furniture     | Piece     | good      | 5         | Lemari gantung dinding
Locker Kayu           | Furniture     | Piece     | good      | 10        | Locker kayu 1 pintu
Meja Tambahan         | Furniture     | Piece     | good      | 3         | Meja tambahan lipat
Cermin Dinding        | Furniture     | Piece     | good      | 4         | Cermin dinding 60x80cm
```

**Expected Results:**
- Category: Furniture (auto-created with code "FUR")
- Unit: Piece (auto-created with code "PI")
- Item codes: FUR-ITM-001 to FUR-ITM-010
- Note: One item with "damaged" condition

---

## 🔧 Contoh 4: Maintenance & Supplies

**File: maintenance-import.xlsx**

```
item_name              | category_name        | unit_name | condition | min_stock | description
-----------------------|----------------------|-----------|-----------|-----------|------------------------------
Cairan Pembersih       | Maintenance          | Bottle    | good      | 5         | Cairan pembersih multifungsi
Sabun Cuci Tangan      | Maintenance          | Bottle    | good      | 8         | Sabun cuci tangan 500ml
Disinfektan            | Maintenance          | Bottle    | good      | 10        | Disinfektan antibakteri
Lap Pembersih          | Maintenance          | Box       | good      | 20        | Lap pembersih microfiber
Sapu                   | Maintenance          | Piece     | good      | 3         | Sapu lidi
Kemoceng               | Maintenance          | Piece     | good      | 4         | Kemoceng bulu ayam
Pel Lantai             | Maintenance          | Piece     | good      | 2         | Pel lantai dengan tongkat
Ember                  | Maintenance          | Piece     | good      | 6         | Ember plastik 10 liter
Gergaji                | Maintenance          | Piece     | good      | 1         | Gergaji kayu
Paku 2 Inch            | Maintenance          | Box       | good      | 15        | Paku besi 2 inch
Skrup                  | Maintenance          | Box       | good      | 10        | Skrup campuran ukuran
```

**Expected Results:**
- Category: Maintenance (auto-created with code "MAI")
- Units: Bottle, Box, Piece (auto-created)
- Item codes: MAI-ITM-001 to MAI-ITM-011

---

## 🌡️ Contoh 5: Dengan Description Lengkap

**File: detailed-import.xlsx**

```
item_name      | category_name | unit_name | condition | min_stock | description                    | category_description           | unit_description
----------------|---------------|-----------|-----------|-----------|--------------------------------|--------------------------------|------------------
Thermostat     | HVAC          | Piece     | good      | 2         | Smart thermostat digital      | Peralatan pendingin & pemanas  | Satuan pcs
AC Unit 1.5 PK | HVAC          | Piece     | good      | 1         | AC split 1.5 PK Panasonic     | Peralatan pendingin & pemanas  | Satuan pcs
Fan Ceiling    | HVAC          | Piece     | good      | 3         | Fan ceiling 4 baling-baling   | Peralatan pendingin & pemanas  | Satuan pcs
Filter AC      | HVAC          | Box       | good      | 10        | Filter AC standard 20x25cm    | Peralatan pendingin & pemanas  | Kotak berisi
Refrigerant Gas| HVAC          | Bottle    | good      | 5         | Refrigerant R22 13kg          | Peralatan pendingin & pemanas  | Botol tabung
```

**Expected Results:**
- Category: HVAC (auto-created with code "HVA", deskripsi: "Peralatan pendingin & pemanas")
- Units: Piece, Box, Bottle (auto-created dengan deskripsi)
- Item codes: HVA-ITM-001 to HVA-ITM-005
- All categories and units memiliki description yang informatif

---

## ⚠️ Contoh 6: Dengan Error (for Testing)

**File: test-error-import.xlsx** (untuk test error handling)

```
item_name           | category_name | unit_name | condition | min_stock | description
---------------------|---------------|-----------|-----------|-----------|----------------------------
Monitor LG          | Electronics   | Piece     | good      | 4         | LG Monitor 24"
⚠️ EMPTY ROW (skip) |
Monitor Dell        | Electronics   | Piece     | invalid   | 5         | INVALID CONDITION ERROR
Keyboard Logitech   | Electronics   | Piece     | good      | -5        | NEGATIVE MIN_STOCK ERROR
Laptop              | Electronics   | Piece     | good      | 8         | OK
Laptop              | Electronics   | Piece     | good      | 10        | DUPLICATE ITEM NAME ERROR
Mouse               | New Category  | Piece     | good      | 15        | OK (New category created)
```

**Expected Results:**
- Row 1 (Monitor LG): ✅ SUCCESS
- Row 2 (EMPTY): ⏭️ SKIPPED
- Row 3 (invalid condition): ❌ ERROR "Invalid condition: 'invalid'"
- Row 4 (negative min_stock): ❌ ERROR "Min stock cannot be negative"
- Row 5 (Laptop): ✅ SUCCESS
- Row 6 (Duplicate Laptop): ❌ ERROR "Item name 'Laptop' already exists"
- Row 7 (Mouse): ✅ SUCCESS (creates new "New Category")

---

## 🎯 Contoh 7: Multi-Category Import

**File: multi-category-import.xlsx** (Import dengan beberapa kategori sekaligus)

```
item_name           | category_name    | unit_name  | condition | min_stock
---------------------|------------------|-----------|-----------|----------
Laptop              | Computers        | Unit      | good      | 5
Monitor             | Computers        | Unit      | good      | 3
Keyboard            | Computers        | Unit      | good      | 8
Printer             | Computers        | Unit      | good      | 2
Tinta Printer       | Consumables      | Box       | good      | 15
Kertas A4           | Consumables      | Ream      | good      | 10
Ballpoint           | Consumables      | Box       | good      | 20
Meja Kerja          | Furniture        | Unit      | good      | 2
Kursi               | Furniture        | Unit      | good      | 4
Lemari              | Furniture        | Unit      | good      | 1
Sapu                | Maintenance      | Unit      | good      | 3
Kemoceng            | Maintenance      | Unit      | good      | 4
```

**Expected Results:**
- Categories created: Computers (COM), Consumables (CON), Furniture (FUR), Maintenance (MAI)
- Units created: Unit (UN), Box (BO), Ream (RE)
- Item codes: COM-ITM-001 to COM-ITM-004, CON-ITM-001 to CON-ITM-003, FUR-ITM-001 to FUR-ITM-003, MAI-ITM-001 to MAI-ITM-002
- Total items imported: 12

---

## 📝 Template Kolom Lengkap

### Minimal (Required Only)
```
item_name | category_name | unit_name | condition | min_stock
```

### Standard (Recommended)
```
item_name | category_name | unit_name | condition | min_stock | description
```

### Complete (Full Details)
```
item_name | category_name | category_description | unit_name | unit_description | condition | min_stock | description
```

---

## 🔍 Validation Rules Quick Reference

| Field | Type | Rules | Example |
|-------|------|-------|---------|
| item_name | String | Required, Max 255, Unique | Laptop Dell XPS 13 |
| category_name | String | Required | Electronics |
| unit_name | String | Required | Piece, Box, Ream |
| condition | String | Required, Enum | good, damaged |
| min_stock | Number | Required, Integer, ≥0 | 5, 10, 0 |
| description | String | Optional | Laptop 15 inch i7 16GB |
| category_description | String | Optional | Electronic equipment |
| unit_description | String | Optional | Individual unit |

---

## 💡 Tips for Preparing Excel

1. **Use Template**
   - Download template dari aplikasi
   - Gunakan sebagai base

2. **Clean Data**
   - Hapus spasi berlebih
   - Hindari karakter khusus di item_name
   - Gunakan format konsisten

3. **Validate Before Upload**
   - Periksa condition values (hanya good/damaged)
   - Pastikan min_stock numeric
   - Cek tidak ada duplikat item_name

4. **Test Small**
   - Import 5-10 items dulu
   - Verifikasi hasilnya
   - Baru import dalam jumlah besar

5. **Keep Backup**
   - Save file Excel asli
   - Screenshot hasil import
   - Backup database sebelum import besar

---

**Template Version:** 1.0  
**Last Updated:** February 2, 2026
