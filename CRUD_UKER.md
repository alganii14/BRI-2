# CRUD Uker - Dokumentasi

## Fitur yang Sudah Dibuat

### 1. Database & Model
- ✅ Migration tabel `ukers` dengan field:
  - kode_sub_kanca
  - sub_kanca
  - segment
  - kode_kanca
  - kanca
  - kanwil
  - kode_kanwil
- ✅ Model Eloquent `Uker` dengan fillable fields

### 2. CRUD Operations
- ✅ **Create**: Tambah data Uker manual
- ✅ **Read**: Tampilkan list Uker dengan pagination
- ✅ **Update**: Edit data Uker
- ✅ **Delete**: Hapus data Uker (single & bulk delete)
- ✅ **Search**: Cari Uker berdasarkan nama, segment, dll

### 3. Import CSV
- ✅ Upload file CSV
- ✅ Parse CSV dengan delimiter `;` (semicolon)
- ✅ Validasi data sebelum import
- ✅ Skip duplicate records
- ✅ Error handling

## Cara Menggunakan

### 1. Akses Halaman Uker
Login terlebih dahulu, kemudian klik menu **Uker** di sidebar.

### 2. Import Data dari CSV

#### Langkah-langkah:
1. Klik tombol **"📁 Import CSV"**
2. Modal akan terbuka
3. Pilih file CSV Anda (contoh: `KODE UKER BRI SELINDO.csv`)
4. Klik **"Import"**
5. Tunggu proses import selesai
6. Data akan muncul di tabel

#### Format CSV yang Didukung:
```
Kode Sub Kanca;Sub Kanca;SEGMENT;Kode Kanca;Kanca;Kanwil;Kode Kanwil
4219;UNIT WIDASARI JATIBARANG;MIKRO;165;KC Jatibarang ;Bandung ;F
3295;UNIT WERU CIREBON ;MIKRO;107;KC Cirebon Kartini;Bandung ;F
```

**Penting:**
- Delimiter harus `;` (semicolon)
- Baris pertama adalah header (akan di-skip)
- Duplicate records akan otomatis di-skip

### 3. Tambah Data Manual
1. Klik tombol **"+ Tambah Uker"**
2. Isi form:
   - **Kode Sub Kanca** (Required)
   - **Sub Kanca** (Required)
   - **Segment** (Opsional: MIKRO, RETAIL, KOMERSIAL)
   - **Kode Kanca** (Opsional)
   - **Kanca** (Opsional)
   - **Kanwil** (Opsional)
   - **Kode Kanwil** (Opsional)
3. Klik **"Simpan"**

### 4. Edit Data
1. Klik icon **✏️ Edit** pada baris data yang ingin diubah
2. Update informasi yang diperlukan
3. Klik **"Update"**

### 5. Hapus Data

#### Hapus Single:
- Klik icon **🗑️ Hapus** pada baris data
- Konfirmasi penghapusan

#### Hapus Semua:
- Klik tombol **"🗑️ Hapus Semua"**
- Konfirmasi (HATI-HATI: Semua data akan terhapus!)

### 6. Cari Data
1. Gunakan search box di kanan atas
2. Ketik keyword (bisa nama Sub Kanca, Kanca, Kanwil, atau Segment)
3. Klik **"Cari"**

## API Routes

```php
// Resource routes (CRUD)
GET     /uker              -> index (list)
GET     /uker/create       -> create form
POST    /uker              -> store (save new)
GET     /uker/{id}         -> show (detail)
GET     /uker/{id}/edit    -> edit form
PUT     /uker/{id}         -> update
DELETE  /uker/{id}         -> destroy (delete)

// Custom routes
POST    /uker/import       -> import CSV
DELETE  /uker-delete-all   -> delete all records
```

## File Structure

```
app/
├── Http/Controllers/
│   └── UkerController.php          # CRUD + Import logic
└── Models/
    └── Uker.php                    # Eloquent model

database/
└── migrations/
    └── 2025_11_04_030524_create_ukers_table.php

resources/views/uker/
├── index.blade.php                 # List + Search + Import modal
├── create.blade.php                # Form tambah
└── edit.blade.php                  # Form edit

routes/
└── web.php                         # Route definitions
```

## Features Detail

### Import CSV Logic
```php
- Read CSV file dengan fgetcsv()
- Skip header row
- Loop setiap baris
- Check duplicate berdasarkan kode_sub_kanca + sub_kanca
- Insert jika belum ada
- Return success message dengan jumlah data imported
```

### Search Functionality
Pencarian dilakukan pada field:
- sub_kanca
- kanca
- kanwil
- segment

### Pagination
- 15 records per halaman
- Bootstrap pagination style
- Search query preserved saat pagination

## Validation Rules

### Create & Update
```php
'kode_sub_kanca' => 'required'
'sub_kanca'      => 'required'
'segment'        => 'nullable'
'kode_kanca'     => 'nullable'
'kanca'          => 'nullable'
'kanwil'         => 'nullable'
'kode_kanwil'    => 'nullable'
```

### Import CSV
```php
'csv_file' => 'required|file|mimes:csv,txt|max:10240' // max 10MB
```

## Tips & Best Practices

1. **Import Data Besar**: Jika CSV sangat besar, import bisa memakan waktu. Pastikan tidak refresh halaman.

2. **Duplicate Prevention**: System otomatis skip duplicate berdasarkan kombinasi `kode_sub_kanca` dan `sub_kanca`.

3. **CSV Format**: Pastikan delimiter adalah semicolon (`;`) bukan comma (`,`).

4. **Backup Data**: Sebelum menggunakan "Hapus Semua", backup data terlebih dahulu.

5. **Search**: Search tidak case-sensitive dan menggunakan LIKE query.

## Error Handling

- ✅ File validation (CSV only, max 10MB)
- ✅ Empty row handling
- ✅ Parse error handling
- ✅ Database error handling
- ✅ User-friendly error messages

## Next Steps (Optional Enhancement)

- [ ] Export to Excel/CSV
- [ ] Bulk edit
- [ ] Soft delete
- [ ] Activity log
- [ ] Advanced filters (by Kanwil, Segment, etc)
- [ ] Import validation preview before commit
- [ ] Download template CSV

## Troubleshooting

### Error: CSV tidak bisa diimport
- Pastikan format CSV benar (delimiter semicolon)
- Cek size file (max 10MB)
- Pastikan encoding UTF-8

### Error: Data duplicate
- Data dengan kombinasi kode_sub_kanca + sub_kanca yang sama akan di-skip
- Ini normal dan bukan error

### Error: Table tidak ditemukan
```bash
php artisan migrate
```

### Clear cache jika ada masalah
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```
