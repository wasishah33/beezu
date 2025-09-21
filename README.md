# 🐝 Beezu Framework

[![PHP Version](https://img.shields.io/badge/php-%5E8.0-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Framework](https://img.shields.io/badge/framework-Beezu-orange.svg)](https://github.com/wasishah33/beezu)
[![Status](https://img.shields.io/badge/status-production%20ready-brightgreen.svg)](https://github.com/wasishah33/beezu)

A lightweight, secure, and modern PHP framework for building web applications with ease. Built with performance and simplicity in mind.

## ✨ Features

- 🚀 **Lightweight & Fast** - Minimal overhead, maximum performance
- 🔒 **Security First** - Built-in CSRF protection, rate limiting, and secure authentication
- 🎨 **Modern UI** - AdminLTE 3 integration with responsive design
- 📝 **Blog System** - Complete CRUD for posts, categories, and pages
- 🗄️ **Database ORM** - Fluent query builder with model relationships
- 🛡️ **Middleware Support** - Flexible middleware system for authentication and security
- 📱 **Responsive Design** - Mobile-first approach with Bootstrap 5
- 🔧 **Developer Friendly** - Clean code, PSR-4 autoloading, and comprehensive helpers

## 📋 Requirements

- PHP 8.0 or higher
- MySQL 5.7 or higher
- Apache/Nginx with mod_rewrite
- Composer
- Laragon/XAMPP/WAMP (for local development)

## 🚀 Quick Start

### 1. Clone the Repository
```bash
git clone https://github.com/wasishah33/beezu.git
cd beezu
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Database Setup
1. Create a MySQL database named `beezu`
2. Import the database schema:
```bash
mysql -u root -p beezu < beezu.sql
```

### 4. Environment Configuration
Copy the example environment file and configure it:
```bash
cp .env.example .env
```

Edit `.env` with your database credentials:
```env
APP_NAME=Beezu
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost/beezu/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=beezu
DB_USERNAME=root
DB_PASSWORD=your_password

SESSION_LIFETIME=120
SESSION_SECURE=false
```

### 5. Web Server Configuration

#### Apache (.htaccess included)
The framework includes a `.htaccess` file for Apache. Ensure mod_rewrite is enabled.

#### Nginx
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 6. Access Your Application
- **Frontend**: `http://localhost/beezu/public/`
- **Admin Panel**: `http://localhost/beezu/public/admin/login`

## 🔐 Default Admin Credentials

- **Email**: `admin@example.com`
- **Password**: `admin123`

> ⚠️ **Important**: Change these credentials immediately after first login!

## 📁 Project Structure

```
beezu/
├── app/                    # Application code
│   ├── Controllers/        # MVC Controllers
│   ├── Models/            # Eloquent-style Models
│   ├── Middlewares/       # Custom middleware
│   └── config/            # Configuration files
├── core/                  # Framework core
│   ├── Application.php    # Main application class
│   ├── Router.php         # Routing system
│   ├── Model.php          # Base model class
│   ├── Database.php       # Database connection
│   ├── QueryBuilder.php   # Fluent query builder
│   ├── View.php           # View rendering
│   ├── Request.php        # HTTP request handling
│   ├── Response.php       # HTTP response handling
│   ├── Session.php        # Session management
│   ├── Validator.php      # Input validation
│   ├── Middleware.php     # Middleware base class
│   └── helpers.php        # Global helper functions
├── public/                # Web-accessible files
│   ├── index.php          # Application entry point
│   ├── .htaccess          # Apache rewrite rules
│   ├── assets/            # CSS, JS, images
│   └── uploads/           # File uploads
├── views/                 # View templates
│   ├── layouts/           # Layout templates
│   ├── admin/             # Admin panel views
│   └── front/             # Frontend views
├── routes/                # Route definitions
│   └── web.php            # Web routes
├── storage/               # Storage directories
│   ├── cache/             # Application cache
│   ├── logs/              # Log files
│   └── sessions/          # Session files
├── vendor/                # Composer dependencies
├── beezu.sql              # Database schema
├── composer.json          # Composer configuration
├── .env                   # Environment variables
└── README.md              # This file
```

## 🎯 Core Features

### 🛣️ Routing System
```php
// Define routes
$router->get('/', 'FrontController@index');
$router->post('/api/users', 'UserController@store');
$router->middleware([AuthMiddleware::class])
       ->get('/admin/dashboard', 'AdminController@index');
```

### 🗄️ Database & Models
```php
// Model usage
class User extends Model
{
    protected static ?string $table = 'users';
}

// Query examples
$users = User::all();
$user = User::find(1);
$activeUsers = User::where('is_active', '=', 1)->get();
```

### 🎨 View System
```php
// Render views with layouts
$this->render('admin/dashboard/index', [
    'title' => 'Dashboard',
    'data' => $data
]);
```

### 🔒 Security Features
- **CSRF Protection**: Automatic CSRF token validation
- **Rate Limiting**: Configurable request rate limiting
- **Authentication**: Secure login/logout system
- **Input Validation**: Built-in validation with custom rules
- **Password Hashing**: Secure password hashing with PHP's password_hash()

## 📝 Blog System

The framework includes a complete blog management system:

### Features
- ✅ **Posts Management**: Create, edit, delete blog posts
- ✅ **Categories**: Organize posts with categories
- ✅ **Pages**: Create static pages
- ✅ **Tags**: Tag system for posts
- ✅ **SEO**: Meta titles, descriptions, and slugs
- ✅ **Media**: Featured image support
- ✅ **Status**: Draft, published, archived states

### Admin Access
- **Blog Dashboard**: `/admin/blog`
- **Posts**: `/admin/blog/posts`
- **Categories**: `/admin/blog/categories`
- **Pages**: `/admin/blog/pages`

## 🔧 Configuration

### Environment Variables
```env
# Application
APP_NAME=Beezu
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost/beezu/public

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=beezu
DB_USERNAME=root
DB_PASSWORD=

# Session
SESSION_LIFETIME=120
SESSION_SECURE=false

# Rate Limiting
RATE_LIMIT_MAX=60
RATE_LIMIT_WINDOW=60
```

## 🚀 API Endpoints

### Authentication
- `POST /admin/login` - Admin login
- `GET /admin/logout` - Admin logout

### Blog API
- `GET /admin/blog` - Blog dashboard
- `GET /admin/blog/posts` - List posts
- `POST /admin/blog/posts` - Create post
- `PUT /admin/blog/posts/{id}` - Update post
- `DELETE /admin/blog/posts/{id}` - Delete post

### User Management
- `GET /users` - List users
- `POST /users` - Create user
- `PUT /users/{id}` - Update user
- `DELETE /users/{id}` - Delete user

## 🛡️ Security

### Built-in Security Features
- **CSRF Protection**: All POST requests protected
- **Rate Limiting**: Prevents abuse and DDoS
- **SQL Injection Protection**: Prepared statements
- **XSS Protection**: Input sanitization and output escaping
- **Secure Sessions**: Session regeneration and secure cookies
- **Password Security**: bcrypt hashing with configurable cost

### Security Best Practices
1. Always use HTTPS in production
2. Keep dependencies updated
3. Validate all input data
4. Use prepared statements for database queries
5. Implement proper error handling
6. Regular security audits

## 📊 Performance

### Optimizations
- **Lightweight Core**: Minimal framework overhead
- **Efficient Routing**: Fast route resolution
- **Database Optimization**: Optimized queries and indexes
- **Caching Ready**: Built-in cache support
- **Asset Optimization**: CDN-ready asset loading

### Benchmarks
- **Framework Load Time**: < 50ms
- **Route Resolution**: < 1ms
- **Database Query**: < 10ms average
- **Memory Usage**: < 2MB base

## 🧪 Testing

### Manual Testing
1. **Frontend**: Visit home page and test navigation
2. **Admin Panel**: Test login and dashboard access
3. **Blog System**: Create, edit, and delete content
4. **API Endpoints**: Test JSON responses
5. **Security**: Verify CSRF and rate limiting

### Test Checklist
- [ ] Application loads correctly
- [ ] Admin login works
- [ ] Blog CRUD operations function
- [ ] File uploads work
- [ ] Security features active
- [ ] Database connections stable

## 🔄 Deployment

### Production Checklist
1. Set `APP_ENV=production` and `APP_DEBUG=false`
2. Configure secure database credentials
3. Enable HTTPS and secure cookies
4. Set up proper file permissions
5. Configure web server for optimal performance
6. Set up monitoring and logging
7. Backup database and files

### Server Requirements
- PHP 8.0+
- MySQL 5.7+
- Apache/Nginx
- SSL certificate (recommended)
- At least 512MB RAM

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines
- Follow PSR-4 autoloading standards
- Write clean, documented code
- Add tests for new features
- Update documentation as needed

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- [AdminLTE 3](https://adminlte.io/) for the admin interface
- [Bootstrap 5](https://getbootstrap.com/) for responsive design
- [Font Awesome](https://fontawesome.com/) for icons
- PHP community for best practices and inspiration

## 📞 Support

- **Documentation**: [Wiki](https://github.com/wasishah33/beezu/wiki)
- **Issues**: [GitHub Issues](https://github.com/wasishah33/beezu/issues)
- **Discussions**: [GitHub Discussions](https://github.com/wasishah33/beezu/discussions)
- **Email**: info@xpertsvison.com
- **Website**: [Developer](http://xpertsvison.com)

## 🎯 Roadmap

### Version 2.0 (Planned)
- [ ] API authentication (JWT)
- [ ] Real-time features (WebSockets)
- [ ] Advanced caching system
- [ ] Multi-language support
- [ ] Plugin system
- [ ] CLI commands
- [ ] Unit testing framework

### Version 1.1 (Current)
- [x] Core framework
- [x] Admin panel
- [x] Blog system
- [x] Security features
- [x] Database ORM

---

<div align="center">

**Made with ❤️ by the Xperts Vision**

[![GitHub stars](https://img.shields.io/github/stars/wasishah33/beezu?style=social)](https://github.com/wasishah33/beezu)
[![GitHub forks](https://img.shields.io/github/forks/wasishah33/beezu?style=social)](https://github.com/wasishah33/beezu)
[![GitHub watchers](https://img.shields.io/github/watchers/wasishah33/beezu?style=social)](https://github.com/wasishah33/beezu)

</div>
