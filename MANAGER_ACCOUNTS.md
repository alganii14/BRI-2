# 👥 Daftar Akun Manager

## 🔐 Kredensial Login

**Password untuk semua akun:** `password`

---

## 📋 Daftar Email Manager (30 Akun)

| No | Kanca | Email | Password |
|----|-------|-------|----------|
| 1 | KC Bandung A.H. Nasution | manager.kcbandungahnasution@bri.co.id | password |
| 2 | KC Bandung AA | manager.kcbandungaa@bri.co.id | password |
| 3 | KC Bandung Dago | manager.kcbandungdago@bri.co.id | password |
| 4 | KC Bandung Dewi Sartika | manager.kcbandungdewisartika@bri.co.id | password |
| 5 | KC Bandung Kopo | manager.kcbandungkopo@bri.co.id | password |
| 6 | KC Bandung Martadinata | manager.kcbandungmartadinata@bri.co.id | password |
| 7 | KC Bandung Naripan | manager.kcbandungnaripan@bri.co.id | password |
| 8 | KC Bandung Setiabudi | manager.kcbandungsetiabudi@bri.co.id | password |
| 9 | KC Bandung Soekarno Hatta | manager.kcbandungsoekarnohatta@bri.co.id | password |
| 10 | KC Banjar | manager.kcbanjar@bri.co.id | password |
| 11 | KC Ciamis | manager.kcciamis@bri.co.id | password |
| 12 | KC Cianjur | manager.kccianjur@bri.co.id | password |
| 13 | KC Cibadak | manager.kccibadak@bri.co.id | password |
| 14 | KC Cimahi | manager.kccimahi@bri.co.id | password |
| 15 | KC Cirebon Gunung Jati | manager.kccirebongunungjati@bri.co.id | password |
| 16 | KC Cirebon Kartini | manager.kccirebonkartini@bri.co.id | password |
| 17 | KC Garut | manager.kcgarut@bri.co.id | password |
| 18 | KC Indramayu | manager.kcindramayu@bri.co.id | password |
| 19 | KC Jatibarang | manager.kcjatibarang@bri.co.id | password |
| 20 | KC Kuningan | manager.kckuningan@bri.co.id | password |
| 21 | KC Majalaya | manager.kcmajalaya@bri.co.id | password |
| 22 | KC Majalengka | manager.kcmajalengka@bri.co.id | password |
| 23 | KC Pamanukan | manager.kcpamanukan@bri.co.id | password |
| 24 | KC Purwakarta | manager.kcpurwakarta@bri.co.id | password |
| 25 | KC Singaparna | manager.kcsingaparna@bri.co.id | password |
| 26 | KC Soreang | manager.kcsoreang@bri.co.id | password |
| 27 | KC Subang | manager.kcsubang@bri.co.id | password |
| 28 | KC Sukabumi | manager.kcsukabumi@bri.co.id | password |
| 29 | KC Sumedang | manager.kcsumedang@bri.co.id | password |
| 30 | KC Tasikmalaya | manager.kctasikmalaya@bri.co.id | password |

---

## 📌 Catatan

- **Total Manager:** 30 akun ✅
- Semua KC sudah tercover, termasuk KC Bandung Soekarno Hatta
- Seeder sudah handle perbedaan ejaan (Soekarno vs Sukarno)
- Setiap Manager hanya bisa melihat dan mengelola RMFT dari Kanca mereka sendiri
- Format email: `manager.[nama_kanca_tanpa_spasi]@bri.co.id`

---

## 🔄 Cara Refresh Manager Accounts

Jika ingin membuat ulang semua akun manager (menghapus yang lama dan buat baru):

```bash
php artisan db:seed --class=FreshManagerSeeder
```

Perintah ini akan:
1. ✅ Menghapus semua akun manager yang ada
2. ✅ Membuat akun manager baru untuk setiap Kanca
3. ✅ Password semua akun: `password`

---

## 🧪 Testing Login

**Contoh Login untuk KC Bandung Dago:**
- Email: `manager.kcbandungdago@bri.co.id`
- Password: `password`

Setelah login, Manager KC Bandung Dago hanya akan melihat:
- RMFT yang berada di KC Bandung Dago
- Aktivitas dari RMFT di KC Bandung Dago
- Dashboard khusus untuk KC Bandung Dago

---

**Generated on:** 2025-11-12
