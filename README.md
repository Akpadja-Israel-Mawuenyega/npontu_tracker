# npontu_tracker

An enterprise-grade **Application Support Team Activity Tracker** built with Laravel. Designed to streamline daily operations, provide real-time status updates on support tasks, and generate activity history reports for team handovers.

## Overview

npontu_tracker is a web-based application that helps support teams efficiently manage their workload, track activities, and maintain comprehensive audit trails. Perfect for IT support departments, customer success teams, and any organization requiring detailed activity documentation and team coordination.

## Features

- 📋 **Real-time Task Tracking** - Monitor support tasks as they progress through various stages
- 👥 **Team Activity Monitoring** - View team member activities and workload distribution
- 📊 **Activity History Reports** - Generate detailed reports for handovers and performance analysis
- 🔔 **Status Updates** - Real-time status notifications for critical updates
- 📝 **Comprehensive Audit Trail** - Track all activities with timestamps and user information
- 👤 **User Authentication** - Secure authentication and authorization
- 📱 **Responsive Design** - Works seamlessly on desktop and mobile devices

## Tech Stack

- **Framework**: Laravel 12.x
- **Language**: PHP 8.2+
- **Template Engine**: Blade
- **Database**: SQLite (default), MySQL/PostgreSQL compatible
- **Frontend**: Vite with modern JavaScript
- **Package Manager**: Composer, npm
- **Testing**: PHPUnit 11.x

## Requirements

- **PHP**: 8.2 or higher
- **Node.js**: 18.x or higher (for npm)
- **Composer**: Latest stable version
- **Database**: SQLite (included), MySQL 8.0+, or PostgreSQL 12+

## Installation

### Quick Setup

Clone the repository and run the automated setup script:

```bash
git clone https://github.com/Akpadja-Israel-Mawuenyega/npontu_tracker.git
cd npontu_tracker
composer run-script setup
```

This script will:
1. Install PHP dependencies via Composer
2. Copy `.env.example` to `.env` (if not exists)
3. Generate application key
4. Run database migrations
5. Install Node dependencies
6. Build frontend assets

### Manual Setup

If you prefer to set up manually:

```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate

# Build assets
npm run build

# Start development server
php artisan serve
```

### Environment Configuration

Edit `.env` to configure your application:

```env
APP_NAME=npontu_tracker
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=sqlite
# For MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=npontu_tracker
# DB_USERNAME=root
# DB_PASSWORD=

# Mail Configuration
MAIL_MAILER=log  # Change to 'smtp' for production
```

## Development

### Running Development Server

```bash
composer run-script dev
```

This command runs:
- Laravel development server
- Queue listener
- Pail logging
- Vite dev server (with HMR)

All processes run concurrently in your terminal.

### Useful Development Commands

```bash
# Serve the application
php artisan serve

# Run database migrations
php artisan migrate

# Create a new database migration
php artisan make:migration create_table_name

# Rollback migrations
php artisan migrate:rollback

# Seed the database
php artisan db:seed

# Clear application cache
php artisan cache:clear
php artisan config:clear

# Build frontend assets
npm run build

# Watch for frontend changes (dev mode)
npm run dev
```

## Testing

Run the test suite using:

```bash
composer run-script test
# or
php artisan test
```

Test files are located in the `tests/` directory.

## Deployment

### Production Build

```bash
composer install --no-dev --optimize-autoloader
npm run build
php artisan migrate --force
php artisan cache:clear
php artisan config:cache
php artisan route:cache
```

### Environment Setup for Production

Create a `.env` file with production values:

```env
APP_NAME=npontu_tracker
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=your.db.host
DB_PORT=3306
DB_DATABASE=npontu_tracker
DB_USERNAME=dbuser
DB_PASSWORD=secure_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

## Project Structure

```
npontu_tracker/
├── app/                 # Application code (Models, Controllers, etc.)
├── database/            # Migrations, Factories, Seeders
├── resources/
│   ├── views/          # Blade templates
│   ├── css/            # Stylesheets
│   └── js/             # JavaScript files
├── routes/             # API and web routes
├── storage/            # Application storage (logs, files)
├── tests/              # Test files
├── bootstrap/          # Framework bootstrap files
├── config/             # Configuration files
├── public/             # Public assets
├── vendor/             # Composer dependencies
├── node_modules/       # npm dependencies
├── .env.example        # Environment configuration template
├── composer.json       # PHP dependencies
├── package.json        # npm dependencies
└── vite.config.js      # Vite configuration
```

## Database Schema

### Activities Table
- `id` - Primary key
- `title` - Activity title (string)
- `description` - Activity description (text, nullable)
- `activity_date` - Date of activity (date)
- `created_by` - Foreign key to users (administrator/creator)
- `created_at` / `updated_at` - Timestamps

### ActivityUpdates Table
- `id` - Primary key
- `activity_id` - Foreign key to activities
- `user_id` - Foreign key to users (who submitted the update)
- `status` - Enum: `pending` or `done`
- `remark` - Update notes/handover comments (text, nullable)
- `created_at` / `updated_at` - Timestamps (captures exact update time)

### Handover System

The handover system is **documentation-based** through the remarks field:

1. Team members submit activity updates with status (`pending` or `done`)
2. The `remark` field contains handover notes for incoming shifts
3. The `updated_at` timestamp tracks when each update was made
4. Activity history reports compile all remarks for comprehensive shift handovers

Example workflow:
```
Activity: "Customer Issue Resolution"
├── Update 1: Status=pending, Remark="Started investigation, found config error", User="John", Time="09:00"
├── Update 2: Status=pending, Remark="Escalating to DB team - connection pool issue", User="John", Time="11:30"
└── Update 3: Status=done, Remark="Fixed by DB team. Verified customer access restored.", User="Sarah", Time="14:00"
```

## Database Migrations

Create and run migrations to set up your database schema:

```bash
# Create a new migration
php artisan make:migration create_support_tickets_table

# Run migrations
php artisan migrate

# Rollback last migration batch
php artisan migrate:rollback

# Reset all migrations
php artisan migrate:reset
```

## API Documentation

(Documentation for API endpoints should be added here based on your routes)

For route information, check:
```bash
php artisan route:list
```

## Troubleshooting

### Common Issues

**Port 8000 already in use:**
```bash
php artisan serve --port=8001
```

**Database connection error:**
- Verify `.env` database configuration
- Ensure database server is running
- Check database credentials

**Permission denied errors:**
```bash
chmod -R 775 storage bootstrap/cache
```

**Composer/npm issues:**
```bash
# Clear caches
composer clear-cache
npm cache clean --force

# Reinstall
composer install
npm install
```

## Performance Optimization

```bash
# Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Database query optimization
php artisan query:optimize
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For issues, questions, or suggestions, please open an issue on the GitHub repository.

## Roadmap

- [ ] Advanced reporting dashboard
- [ ] Team analytics and insights
- [ ] Integration with external support systems
- [ ] Mobile app (native)
- [ ] Real-time notifications via WebSocket
- [ ] Custom workflow automation
- [ ] Export to PDF/Excel reports

## Changelog

### v1.0.0 (Initial Release)
- Initial project setup
- Core activity tracking functionality
- Team activity monitoring
- Activity history reports

---

Built with ❤️ for efficient support team operations