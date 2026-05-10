# ☕ Sedjati Coffee POS

Web-Based Cafe Point of Sale and Operational Management System.

---

# 📌 Project Overview

Sedjati Coffee POS adalah sistem Point of Sale berbasis web yang dibuat untuk membantu operasional cafe secara lebih modern, cepat, dan terintegrasi.

Sistem ini memiliki fitur:

* Dashboard Monitoring
* Menu Management
* Order Management
* Payment System
* Receipt System
* Customer Management
* Settings Management
* Multi Role Authentication

---

# 🛠️ Tech Stack

| Component       | Technology             |
| --------------- | ---------------------- |
| Backend         | Laravel 12             |
| Frontend        | Blade                  |
| Database        | MySQL                  |
| Styling         | Bootstrap & Custom CSS |
| Server          | XAMPP                  |
| Version Control | Git & GitHub           |

---

# 🚀 Development Process

## 1. Project Initialization

Project dimulai dengan membuat project Laravel menggunakan Composer.

```bash
composer create-project laravel/laravel project_1
```

Masuk ke folder project:

```bash
cd project_1
```

Menjalankan development server:

```bash
php artisan serve
```

---

# 🔗 Git Initialization

Project dihubungkan dengan Git untuk version control.

```bash
git init
```

Commit pertama:

```bash
git add .
git commit -m "init: setup laravel project"
```

Project kemudian dihubungkan ke GitHub repository.

```bash
git remote add origin https://github.com/username/sedjati-coffe-pos.git
git push -u origin main
```

---

# 🤖 AI-Assisted Development

Project dikembangkan menggunakan bantuan OpenAI Codex yang terhubung ke Visual Studio Code.

## Workflow Development

1. Mendesain struktur sistem
2. Membuat database dan migration
3. Membuat model dan relasi database
4. Membuat controller Laravel
5. Mendesain UI dashboard
6. Mengintegrasikan frontend dengan backend
7. Menambahkan fitur transaksi dan pembayaran
8. Menambahkan customer management
9. Menambahkan settings management
10. Testing dan debugging sistem

---

# 🧱 Database Development

## Database Tables

Sistem menggunakan beberapa tabel utama:

* users
* customers
* produk
* pesanan
* detail_pesanan
* settings

Migration dibuat menggunakan Laravel migration system.

Contoh command:

```bash
php artisan make:migration create_produk_table
```

Menjalankan migration:

```bash
php artisan migrate
```

---

# 🎨 UI & Frontend Development

UI sistem dikembangkan menggunakan:

* Blade Template
* Bootstrap
* Custom CSS

Dashboard didesain menggunakan konsep modern admin dashboard dengan tampilan cafe aesthetic.

Fokus utama UI:

* Clean layout
* Responsive design
* Card-based dashboard
* Modern cafe color palette

---

# 🔐 Authentication System

Authentication menggunakan Laravel authentication system.

Fitur authentication:

* Login admin
* Login kasir
* Session management
* Logout system
* Role-based access

---

# 📊 Dashboard Development

Dashboard dikembangkan untuk monitoring operasional cafe.

Fitur dashboard:

* Total revenue
* Total orders
* Active tables
* Average prep time
* Weekly sales chart
* Trending menu
* Recent orders

---

# 🍽️ Menu Management Development

Menu management dibuat untuk mengelola produk cafe.

Fitur:

* CRUD produk
* Product category
* Product stock
* Product filter
* Product search
* Pagination
* Product status

---

# 🧾 Order Management Development

Order system dibuat untuk mengelola transaksi cafe.

Fitur:

* Create order
* Multiple order items
* Item option
* Item notes
* Table number
* Customer name
* Order history
* Export order
* Payment status

---

# 💳 Payment System Development

Payment system dibuat untuk mempermudah proses pembayaran.

Fitur:

* Payment method selection
* Cash payment
* QRIS payment
* Change calculation
* Payment validation
* Payment status

---

# 🧍 Customer Management Development

Customer management digunakan untuk mengelola data customer.

Fitur:

* Customer list
* Customer statistics
* Total transactions
* Total spending
* First purchase tracking

---

# ⚙️ Settings Management Development

Settings management digunakan untuk konfigurasi sistem.

Fitur:

* Business information
* Tax settings
* Service fee settings
* Payment methods
* User role management
* Order number format

---

# 🧪 Testing & Debugging

Testing dilakukan selama proses development.

Beberapa proses debugging:

* Route debugging
* Authentication debugging
* Logout system debugging
* Database relationship debugging
* UI responsiveness debugging
* Payment flow debugging

---

# 📂 Project Structure

```bash
app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
vendor/
```

---

# 📈 Future Improvements

Pengembangan berikutnya:

* Multi branch system
* Realtime dashboard
* Export PDF & Excel
* Loyalty point system
* Activity log
* Thermal printer integration
* Dark mode

---

# 👨‍💻 Developer Notes

Project ini dikembangkan sebagai implementasi sistem Point of Sale berbasis web menggunakan Laravel dengan pendekatan modern dashboard dan operational management system.

Fokus utama project:

* Modern UI/UX
* Structured database
* Transaction workflow
* Cafe operational management

---

# 📌 Installation Guide

Clone repository:

```bash
git clone https://github.com/username/sedjati-coffe-pos.git
```

Masuk ke folder project:

```bash
cd project_1
```

Install dependency:

```bash
composer install
```

Copy environment file:

```bash
copy .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Setup database dan migrate:

```bash
php artisan migrate
```

Jalankan server:

```bash
php artisan serve
```

---

# 📄 License

This project is developed for educational and portfolio purposes.
