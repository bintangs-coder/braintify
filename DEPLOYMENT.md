# Deployment Checklist - Braintify

## ✅ Yang Sudah Dilakukan
- [x] APP_KEY generated
- [x] Storage linked
- [x] Permissions set (storage, bootstrap/cache)
- [x] Config cached
- [x] Routes cached
- [x] Views cached
- [x] Migrations ran

## 📋 Langkah Deploy di Server Production

### 1. Setup Environment
```bash
# Copy environment file
cp .env.example .env

# Generate key ( jika belum ada )
php artisan key:generate

# Atau set manual di .env:
# APP_KEY=base64:ZNsnIIhHqQ1oDuP7I5BmjXdTKSkUlCB3T6+RpDAwpf0=
```

### 2. Database Setup
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE braintify CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations
php artisan migrate --force
```

### 3. Environment Production
```bash
# Set di .env:
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_HOST=127.0.0.1
DB_DATABASE=braintify
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### 4. Web Server (Nginx)
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/braintify/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location /storage {
        expires 1y;
        log_not_found off;
        add_header Cache-Control "public, immutable";
    }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 5. SSL (Recommended)
```bash
sudo certbot --nginx -d yourdomain.com
```

### 6. Queue Worker (Optional)
```bash
# Untuk production, gunakan supervisor
# Install supervisor: sudo apt install supervisor

# Buat file: /etc/supervisor/conf.d/braintify-worker.conf
[program:braintify-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/braintify/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/braintify/storage/logs/worker.log
stopwaitsecs=3600
```

### 7. Final Check
```bash
# Clear cache jika ada perubahan
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear all cache
php artisan optimize:clear

# Recache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🔧 Troubleshooting

### Error: 500 Server
```bash
# Check storage/logs/laravel.log
# Check PHP-FPM error logs
sudo tail -f /var/log/php8.2-fpm.log
```

### Error: Permission Denied
```bash
sudo chown -R www-data:www-data /path/to/braintify/storage
sudo chown -R www-data:www-data /path/to/braintify/bootstrap/cache
```

### Error: Database Connection
```bash
# Test database connection
php artisan tinker
DB::connection()->getPdo();
```

## 📊 Production Checklist
- [ ] Domain configured
- [ ] SSL certificate installed
- [ ] Database created and migrated
- [ ] Environment variables set
- [ ] Queue worker running (if needed)
- [ ] Scheduled tasks configured (if any)
- [ ] Backup strategy in place
- [ ] Monitoring setup
