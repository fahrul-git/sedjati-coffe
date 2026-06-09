Codex berhasil terhubung

## Catatan Kekurangan Project

Catatan ini dibuat berdasarkan pemeriksaan struktur project pada 2026-06-09.

### Prioritas Tinggi

- Dokumentasi stack belum konsisten: AGENTS.md menyebut Laravel 10, sementara composer.json menggunakan Laravel 12 dan PHP 8.4. Perlu diputuskan versi target agar deployment, requirement, dan README tidak saling berbeda.
- Pengaturan pajak dan service charge sudah ada di halaman Settings, tetapi belum dihitung saat membuat pesanan. Total order masih berasal dari subtotal produk tanpa breakdown pajak, service charge, dan grand total.
- Dashboard masih mencampur data database dengan nilai statis seperti avg_prep_time, revenue_change, orders_change, active_tables_change, prep_time_change, dan grafik salesOverview. Metrik ini perlu dihitung dari transaksi asli.
- Order belum terhubung langsung dengan tabel customers. Form order masih menyimpan customer_name bebas, sehingga total transaksi, total spending, dan first purchase customer tidak otomatis diperbarui.
- Belum ada pencegahan pembayaran ulang untuk pesanan yang sudah paid/completed. Halaman pembayaran masih bisa dipanggil lewat route jika user mengetahui URL.

### Fitur Yang Belum Lengkap

- Export laporan baru tersedia dalam CSV. Belum ada export PDF/Excel, belum ada controller method/route khusus, dan belum ada test untuk hasil file export.
- Print struk masih memakai window.print browser. Layout belum dibuat khusus untuk thermal receipt 58mm/80mm dan belum ada integrasi printer kasir.
- Notifikasi stok menipis/habis baru terlihat di halaman produk/order. Belum muncul sebagai alert dashboard/topbar dan belum punya threshold restock yang bisa diatur.
- Manajemen staff masih berupa tambah user dan update role di Settings. Belum ada edit profil staff, reset password, nonaktifkan/hapus staff, audit aktivitas, atau halaman staff terpisah.
- Topbar search masih berupa input tampilan. Belum ada fungsi global search untuk order, produk, dan customer.
- Opsi varian menu masih hard-coded di PesananController. Idealnya opsi panas/es/rasa disimpan di database agar admin bisa mengubah dari UI.
- DeviceController, model Device, dan view devices masih ada dengan data dummy bertema sensor/greenhouse, tetapi belum terhubung ke route dan tidak relevan dengan sistem coffee POS. Perlu diputuskan untuk dihapus atau dijadikan modul perangkat kasir yang nyata.
- Belum ada fitur pembatalan/refund pesanan yang mengembalikan stok dan mencatat alasan pembatalan.
- Belum ada nomor meja/status dine-in yang lebih lengkap, misalnya occupied, served, selesai, atau pindah meja.

### Kualitas Kode Dan Testing

- Validasi masih berada langsung di controller. Untuk Laravel best practice, Product, Order, Customer, Settings, dan Payment sebaiknya memakai Form Request terpisah.
- Otorisasi masih berbasis RoleMiddleware sederhana. Belum ada policy/gate granular untuk aksi create/update/delete/export/payment.
- Test sudah mencakup login, role access, produk, customer, settings, order, dan payment dasar, tetapi belum mencakup dashboard metric, report CSV, pembayaran debit, pembayaran kurang dari total, stok tidak cukup, opsi item tidak valid, receipt print view, dan flow customer otomatis.
- Belum ada coverage untuk soft failure seperti route admin saat belum login, validasi upload logo/gambar, dan penghapusan gambar lama.
- Belum ada dokumentasi setup database seed akun demo yang lengkap di README_TEST.md, termasuk email/password admin dan kasir dari seeder.
- Belum ada CI/CD test workflow yang menjalankan composer test atau php artisan test sebelum deploy.
