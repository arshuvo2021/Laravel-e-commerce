# Laravel Developer Assessment Project

<div align="center">
  
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![REST API](https://img.shields.io/badge/REST%20API-009688?style=for-the-badge&logo=fastapi&logoColor=white)

</div>

This project is a Laravel-based e-commerce API system designed to showcase best practices in:
- 🔄 RESTful API development with versioning
- ⚡ Performance optimization (e.g., fixing N+1 issues, indexing)
- 🔒 Security enhancements (validation, request sanitization)
- 🧪 Feature testing
- 📊 Real-time CSV export with chunking

## 📦 Features

<div align="center">

| Feature | Description |
|---------|-------------|
| 🔢 **API Versioning** | Support for v1, v2, and v3 endpoints |
| 🛠️ **Product CRUD** | Complete operations via API and Web interface |
| 📈 **CSV Export** | Large dataset export using memory-efficient chunking |
| ⚡ **Performance** | Eloquent optimization and eager loading |
| 📊 **Indexing** | SQL indexing for faster query execution |
| 🧪 **Testing** | Comprehensive feature tests using PHPUnit |
| 🔐 **Security** | Role-based route protection via `auth` middleware |

</div>

## 📁 Project Structure

```
laravel-dev-test/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/V1/
│   │   │   ├── Api/V2/
│   │   │   └── Api/V3/
│   │   └── Requests/
├── routes/
│   ├── api.php
│   └── web.php
├── tests/
│   └── Feature/Api/V2/ProductTest.php
├── database/
│   ├── factories/
│   └── migrations/
├── README.md
└── report.pdf
```

## ⚙️ Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/arshuvo2021/Laravel-e-commerce.git
cd Laravel-e-commerce
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Frontend Assets

```bash
npm install
npm run dev
```

### 4. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set up your database credentials.

### 5. Run Migrations and Seeders

```bash
php artisan migrate --seed
```

### 6. Start Laravel Development Server

```bash
php artisan serve
```

### 7. Access the app

- 🌐 Web: `http://localhost:8000`
- 🔄 API (v1): `http://localhost:8000/api/v1/products`
- 🔄 API (v2): `http://localhost:8000/api/v2/products`
- 📊 Export CSV: `http://localhost:8000/api/v2/products/export/csv`

## 🧪 Testing

To run the test suite:

```bash
php artisan test
```

## 🚀 API Endpoints

<div align="center">

| Version | Method | Endpoint                      | Description                |
|---------|--------|-------------------------------|----------------------------|
| v1      | GET    | `/api/v1/products`            | Product listing (basic)    |
| v2      | GET    | `/api/v2/products`            | Product listing (eager)    |
|         | POST   | `/api/v2/products`            | Create a product           |
|         | GET    | `/api/v2/products/{id}`       | View single product        |
|         | PUT    | `/api/v2/products/{id}`       | Update product             |
|         | DELETE | `/api/v2/products/{id}`       | Delete product             |
|         | GET    | `/api/v2/products/export/csv` | Export all products to CSV |
| v3      | GET    | `/api/v3/products`            | Extended logic placeholder |

</div>

## ✅ Summary of Improvements

- 🚀 Used eager loading to fix N+1 issues
- 📊 Added database indexing on `price` and `category_id`
- 💾 Created chunked export functionality for large CSV files
- 🔒 Secured all APIs and web routes with validation and auth middleware
- 🧪 Wrote PHPUnit feature tests for API behavior verification

## 🧑‍💻 Author

<div align="center">
  
  ### **Md Abdur Rahman Shuvo**
  Laravel & PHP Developer  
  [GitHub Profile](https://github.com/arshuvo2021)
  
</div>

## 📄 License

<div align="center">
  
This project is open-source and available under the [MIT license](LICENSE).

</div>

---

<div align="center">
  
[GitHub Repository](https://github.com/arshuvo2021/Laravel-e-commerce)

</div>