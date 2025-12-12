# 🍔 Foodeli - Food Delivery Platform

A comprehensive full-stack food delivery application built with Laravel 9 and Vue.js 3, featuring real-time order tracking, multi-role management, and seamless user experience.

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Laravel](https://img.shields.io/badge/Laravel-9.x-red.svg)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.x-green.svg)](https://vuejs.org)

## 🌟 Features

### For Customers
- 🔍 Browse restaurants and menus with advanced filtering
- 🛒 Smart cart management with real-time pricing
- 📍 Multiple delivery address management
- 💳 Secure checkout with multiple payment options
- 📦 Real-time order tracking
- ⭐ Review and rating system
- ❤️ Favorite restaurants
- 🎟️ Coupon and discount management

### For Restaurant Owners
- 📊 Restaurant dashboard with analytics
- 🍽️ Menu management (add, edit, delete items)
- 📋 Order management and status updates
- 💰 Revenue tracking
- 📈 Performance insights

### For Riders/Delivery Partners
- 🚴 Delivery dashboard
- 📱 Order assignment notifications
- 🗺️ Navigation to delivery locations
- 💵 Earnings tracking
- 📊 Delivery history

### For Administrators
- 👨‍💼 Complete system management
- 🏪 Restaurant approval and management
- 👥 User management
- 🚴‍♂️ Rider verification and assignment
- 📊 Comprehensive analytics dashboard
- 🎫 Coupon management
- 🔍 Review moderation

## 🛠️ Tech Stack

### Backend
- **Framework:** Laravel 9
- **Language:** PHP 8.0+
- **Database:** MySQL 8.0 / PostgreSQL
- **Authentication:** Laravel Sanctum (API token-based)
- **Image Processing:** Intervention Image
- **Permissions:** Spatie Laravel Permission
- **Queue System:** Redis (recommended) / Database

### Frontend
- **Framework:** Vue.js 3 (Composition API)
- **Build Tool:** Vite
- **State Management:** Pinia
- **Routing:** Vue Router 4
- **HTTP Client:** Axios
- **UI Framework:** Tailwind CSS 3
- **Components:** Headless UI
- **Icons:** Heroicons
- **Form Validation:** VeeValidate + Yup
- **Notifications:** SweetAlert2

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

- **PHP** >= 8.0
- **Composer** >= 2.0
- **Node.js** >= 16.x
- **npm** or **yarn**
- **MySQL** >= 8.0 or **PostgreSQL** >= 12
- **Redis** (optional, for queues and caching)

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/farjana-maya/Foodeli-Project.git
cd Foodeli-Project
```

### 2. Backend Setup

```bash
# Navigate to backend directory
cd foodeli-backend

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your .env file with database credentials
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=foodeli
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed

# Create storage symlink
php artisan storage:link

# Install Sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Start the development server
php artisan serve
```

The backend API will be available at `http://localhost:8000`

### 3. Frontend Setup

```bash
# Navigate to frontend directory (from project root)
cd foodeli-frontend

# Install dependencies
npm install

# Copy environment file
cp .env.example .env.local

# Configure your .env.local file
# VITE_API_BASE_URL=http://localhost:8000/api

# Start development server
npm run dev
```

The frontend will be available at `http://localhost:5173`

## 📁 Project Structure

```
Foodeli-Project/
├── foodeli-backend/           # Laravel backend
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/   # API controllers
│   │   │   ├── Requests/      # Form validation
│   │   │   └── Resources/     # API resources
│   │   ├── Models/            # Eloquent models
│   │   ├── Services/          # Business logic
│   │   └── Repositories/      # Data access layer
│   ├── database/
│   │   ├── migrations/        # Database migrations
│   │   └── seeders/           # Database seeders
│   ├── routes/
│   │   └── api.php            # API routes
│   └── storage/               # File uploads
│
└── foodeli-frontend/          # Vue.js frontend
    ├── src/
    │   ├── components/        # Reusable components
    │   ├── pages/             # Page components
    │   ├── router/            # Vue Router config
    │   ├── store/             # Pinia stores
    │   ├── services/          # API services
    │   ├── composables/       # Vue composables
    │   └── utils/             # Utility functions
    ├── public/                # Static assets
    └── index.html             # Entry HTML
```

## 🔧 Configuration

### Backend Configuration

Edit `foodeli-backend/.env`:

```env
APP_NAME=Foodeli
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=foodeli
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password

QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Frontend Configuration

Edit `foodeli-frontend/.env.local`:

```env
VITE_API_BASE_URL=http://localhost:8000/api
VITE_APP_NAME=Foodeli
```

## 🗄️ Database Schema

### Key Tables

- **users** - User accounts (customers, owners, riders, admin)
- **restaurants** - Restaurant information
- **categories** - Food categories
- **menus** - Menu items
- **orders** - Customer orders
- **order_items** - Order line items
- **addresses** - Delivery addresses
- **riders** - Delivery rider information
- **reviews** - Restaurant and order reviews
- **favorites** - User favorite restaurants
- **coupons** - Discount coupons

## 🔐 Authentication

The application uses **Laravel Sanctum** for API authentication:

1. User registers/logs in
2. Backend generates API token
3. Frontend stores token in localStorage
4. Token is sent with each API request in Authorization header
5. Backend validates token for protected routes

## 🧪 Testing

### Backend Tests

```bash
cd foodeli-backend

# Run all tests
php artisan test

# Run specific test
php artisan test --filter=OrderTest
```

### Frontend Tests

```bash
cd foodeli-frontend

# Run unit tests
npm run test:unit

# Run with coverage
npm run test:coverage
```

## 📦 Deployment

### Backend Deployment

1. **Configure production environment**
```bash
APP_ENV=production
APP_DEBUG=false
```

2. **Optimize application**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

3. **Setup queue worker**
```bash
php artisan queue:work --daemon
```

4. **Configure web server** (Nginx/Apache)

### Frontend Deployment

```bash
# Build for production
npm run build

# Deploy dist/ folder to:
# - Netlify
# - Vercel
# - Cloudflare Pages
# - AWS S3 + CloudFront
```

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 API Documentation

API endpoints are organized as follows:

### Authentication
- `POST /api/register` - User registration
- `POST /api/login` - User login
- `POST /api/logout` - User logout
- `GET /api/me` - Get authenticated user

### Restaurants
- `GET /api/restaurants` - List all restaurants
- `GET /api/restaurants/{id}` - Get restaurant details
- `GET /api/restaurants/featured` - Get featured restaurants

### Orders
- `POST /api/orders` - Create new order
- `GET /api/orders` - Get user orders
- `GET /api/orders/{id}` - Get order details
- `PUT /api/orders/{id}/cancel` - Cancel order

For complete API documentation, visit `/api/documentation` after setup.

## 🐛 Known Issues

- Real-time order tracking requires WebSocket configuration
- Payment gateway integration is placeholder (needs actual integration)

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**Farjana Maya**
- GitHub: [@farjana-maya](https://github.com/farjana-maya)

## 🙏 Acknowledgments

- Laravel framework
- Vue.js framework
- Tailwind CSS
- All open-source contributors

## 📞 Support

For support, email your-email@example.com or open an issue in the repository.

---

**⭐ If you find this project useful, please consider giving it a star!**
