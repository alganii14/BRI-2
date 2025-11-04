# CRUD RMFT dengan Relasi Uker - Dokumentasi

## Fitur yang Sudah Dibuat

### 1. Database & Model
- ✅ Migration tabel `rmfts` dengan field:
  - pernr (Personnel Number)
  - completename (Nama Lengkap)
  - jg (Job Grade)
  - esgdesc (Status Kepegawaian)
  - kanca (Nama Kantor Cabang)
  - **uker_id** (Foreign Key ke tabel ukers)
  - uker (Nama Uker saat ini)
  - uker_tujuan (Uker Tujuan penempatan)
  - keterangan (Keterangan tambahan)
  - kelompok_jabatan (Kelompok Jabatan RMFT)
  
- ✅ Model `RMFT` dengan:
  - Relasi `belongsTo` ke model `Uker`
  - Method `ukerRelation()` untuk akses data Uker terkait

### 2. Relasi Database
- ✅ **Foreign Key** dari `rmfts.uker_id` ke `ukers.id`
- ✅ Relasi berdasarkan **nama Kanca**
- ✅ Logic auto-matching saat import CSV
- ✅ Constraint: `onDelete('set null')` - jika Uker dihapus, uker_id jadi null

### 3. CRUD Operations
- ✅ **Create**: Tambah RMFT dengan dropdown pilih Kanca dari Uker
- ✅ **Read**: List RMFT dengan info relasi Uker (badge ✓)
- ✅ **Update**: Edit data RMFT
- ✅ **Delete**: Hapus single/bulk delete
- ✅ **Search**: Cari berdasarkan nama, PERNR, Kanca, Kelompok Jabatan

### 4. Import CSV
- ✅ Upload file CSV (max 10MB)
- ✅ Parse dengan delimiter `;` (semicolon)
- ✅ **Auto-matching Kanca ke Uker**
- ✅ Duplicate prevention (PERNR + Nama)
- ✅ Error handling

## Cara Import CSV

### Format CSV
```
No;PERNR;COMPLETENAME;JG;ESGDESC;Kanca;Uker;Uker Tujuan;Keterangan;Kelompok Jabatan RMFT Baru
1;382168;Anisya Islamiyani;JG04;Pekerja Kontrak;KC Bandung A.H. Nasution;Unit;Unit;;RMFT Individu Unit
```

**Penting:**
- Delimiter: **Semicolon (;)**
- Baris pertama adalah header (akan di-skip)
- Kolom Kanca akan dicocokkan otomatis dengan tabel Uker

### Langkah Import

1. **Pastikan data Uker sudah ada** (import Uker dulu jika belum)
2. Klik menu **RMFT** di sidebar
3. Klik tombol **"📁 Import CSV"**
4. Pilih file CSV
5. Klik **"Import"**
6. Sistem akan:
   - Membaca setiap baris
   - Mencari Uker yang cocok berdasarkan nama Kanca
   - Menyimpan dengan relasi otomatis
   - Skip duplicate records

## Relasi Uker - RMFT

### Cara Kerja Auto-Matching

Saat import CSV, sistem akan:

1. Membaca kolom **Kanca** dari CSV (contoh: "KC Bandung A.H. Nasution")
2. Mencari di tabel `ukers` dengan query `LIKE`
3. Jika ditemukan, simpan `uker_id` 
4. Jika tidak ditemukan, `uker_id` = null (tetap tersimpan)

```php
// Logic di Controller
$ukerRecord = Uker::where('kanca', 'like', "%{$kancaClean}%")->first();
```

### Tampilan Relasi

Di halaman index, jika RMFT punya relasi dengan Uker, akan muncul:
- Nama Kanca
- Badge hijau **✓** (tanda terhubung ke Uker)

## Struktur Field CSV

| No | Field | Deskripsi | Required |
|----|-------|-----------|----------|
| 0 | No | Nomor urut | - |
| 1 | PERNR | Personnel Number | - |
| 2 | COMPLETENAME | Nama lengkap RMFT | ✅ |
| 3 | JG | Job Grade (JG04, JG05, dll) | - |
| 4 | ESGDESC | Status (PT/Kontrak) | - |
| 5 | Kanca | Nama Kantor Cabang | 🔗 Relasi |
| 6 | Uker | Uker saat ini | - |
| 7 | Uker Tujuan | Uker tujuan | - |
| 8 | Keterangan | Keterangan tambahan | - |
| 9 | Kelompok Jabatan | RMFT Individu/Business/dll | - |

## Query Relasi

### Mengambil data RMFT dengan Uker
```php
$rmfts = RMFT::with('ukerRelation')->get();

foreach($rmfts as $rmft) {
    if($rmft->ukerRelation) {
        echo $rmft->ukerRelation->kanca; // Akses data Uker
    }
}
```

### Filter RMFT berdasarkan Kanca
```php
$rmfts = RMFT::whereHas('ukerRelation', function($query) {
    $query->where('kanca', 'KC Bandung AA');
})->get();
```

## API Routes

```php
// Resource routes
GET     /rmft              -> index (list dengan relasi)
GET     /rmft/create       -> create form (dropdown Kanca)
POST    /rmft              -> store
GET     /rmft/{id}/edit    -> edit form
PUT     /rmft/{id}         -> update
DELETE  /rmft/{id}         -> destroy

// Custom routes
POST    /rmft/import       -> import CSV dengan auto-matching
DELETE  /rmft-delete-all   -> delete all records
```

## File Structure

```
app/
├── Http/Controllers/
│   └── RMFTController.php      # CRUD + Import + Auto-matching
└── Models/
    ├── RMFT.php                # Model dengan relasi
    └── Uker.php                # Model Uker

database/
└── migrations/
    ├── *_create_ukers_table.php
    └── *_create_rmfts_table.php    # Dengan foreign key

resources/views/rmft/
├── index.blade.php             # List + Badge relasi
├── create.blade.php            # Form + Dropdown Kanca
└── edit.blade.php              # Edit form
```

## Validation Rules

### Create & Update
```php
'pernr'             => 'nullable'
'completename'      => 'required'
'jg'                => 'nullable'
'esgdesc'           => 'nullable'
'kanca'             => 'nullable'
'uker_id'           => 'nullable|exists:ukers,id'  // FK validation
'uker'              => 'nullable'
'uker_tujuan'       => 'nullable'
'keterangan'        => 'nullable'
'kelompok_jabatan'  => 'nullable'
```

## Tips & Best Practices

### 1. Import Urutan
```
1. Import Uker dulu (Kanca harus ada)
2. Baru import RMFT (akan auto-match)
```

### 2. Cek Relasi
Setelah import, cek kolom Kanca:
- **Ada badge ✓** = Berhasil terhubung ke Uker
- **Tidak ada badge** = Kanca tidak ditemukan di Uker

### 3. Update Manual
Jika ada RMFT tanpa relasi, edit manual dan pilih Kanca dari dropdown.

### 4. Data Consistency
Pastikan nama Kanca di CSV sama dengan di tabel Uker:
- ✅ "KC Bandung AA" = "KC Bandung AA"
- ❌ "KC Bandung AA" ≠ "Kanca Bandung AA"

## Troubleshooting

### RMFT tidak terhubung ke Uker setelah import

**Penyebab:**
- Nama Kanca di CSV tidak sama dengan di Uker
- Uker belum diimport

**Solusi:**
1. Cek nama Kanca di CSV vs tabel Uker
2. Import Uker dulu jika belum
3. Edit RMFT manual, pilih Kanca dari dropdown

### Error saat import

**Error: Foreign key constraint**
- Pastikan tabel `ukers` sudah ada
- Jalankan migration Uker dulu

**Error: Duplicate entry**
- Data dengan PERNR + Nama sama sudah ada
- Ini normal, sistem akan skip

### Relasi tidak muncul

**Cek:**
```bash
php artisan tinker
>>> $rmft = App\Models\RMFT::find(1);
>>> $rmft->ukerRelation
```

## Enhancement (Optional)

- [ ] Filter RMFT by Kanwil
- [ ] Statistik RMFT per Kanca
- [ ] Export RMFT to Excel
- [ ] Bulk update Uker assignment
- [ ] History perubahan penempatan
- [ ] Notifikasi RMFT tanpa relasi

## Summary

✅ **CRUD Lengkap** dengan Create, Read, Update, Delete
✅ **Relasi Database** RMFT → Uker (Foreign Key)
✅ **Auto-Matching** Kanca saat import CSV
✅ **Visual Indicator** badge relasi di table
✅ **Search & Pagination** untuk navigasi mudah
✅ **Error Handling** robust untuk import

Sekarang Anda bisa import CSV RMFT dan sistem akan otomatis menghubungkannya dengan Uker berdasarkan nama Kanca! 🎉
