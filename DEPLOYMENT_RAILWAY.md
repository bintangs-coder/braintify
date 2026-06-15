# 🚀 Deploy ke Railway (Gratis)

## Prerequisites
- Akun Railway (railway.app)
- GitHub account (opsional tapi direkomendasikan)

## Langkah 1: Persiapan Project

### A. Buat Railway Project
1. Buka https://railway.app
2. Login / Sign up (bisa pakai GitHub)
3. Klik "New Project" → "Deploy from GitHub repo"

### B. Atau Deploy via CLI
```bash
# Install Railway CLI
npx @railway/cli@latest login

# Initialize project
npx @railway/cli@latest init
```

## Langkah 2: Setup Database

1. Di Railway Dashboard, klik "New" → "Database"
2. Pilih **PostgreSQL**
3. Copy connection string (akan dipakai di .env)

## Langkah 3: Environment Variables

Di Railway Dashboard → Settings → Environment Variables:

```env
APP_NAME=Braintify
APP_ENV=production
APP_KEY=base64:ZNsnIIhHqQ1oDuP7I5BmjXdTKSkUlCB3T6+RpDAwpf0=
APP_DEBUG=false
APP_URL=https://your-app.railway.app

DB_CONNECTION=pgsql
DB_HOST=your-postgres-host.railway.app
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=your-db-password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync
```

## Langkah 4: Konfigurasi Laravel untuk Railway

### A. Buat `railway.json` di root project:
```json
{
  "$schema": "https://railway.app schemas/railway-schema.json",
  "build": {
    "builder": "Laravel/Railway",
    "deploy": {
      "startCommand": "php artisan serve --host=0.0.0.0 --port=8080",
      "healthCheckPath": "/"
    }
  }
}
```

### B. Atau buat `Dockerfile` (lebih reliable):
```dockerfile
FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy files
COPY . .

# Install dependencies
RUN composer install --optimize --no-dev

# Expose port
EXPOSE 8080

# Start server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
```

## Langkah 5: Deploy

### Via GitHub (Direkomendasikan)
1. Push code ke GitHub repository
2. Di Railway Dashboard → "Deploy from GitHub repo"
3. Pilih repository
4. Railway auto-detect Laravel

### Via CLI
```bash
npx @railway/cli@latest up
```

## Langkah 6: Setup Domain (Optional)

1. Di Railway Dashboard → Settings → Networking
2. Klik "Generate Domain"
3. Atau custom domain

## Langkah 7: Verifikasi

Buka `https://your-app.railway.app`

Cek logs jika error:
```bash
npx @railway/cli@latest logs
```

## 📋 Checklist Deploy

- [ ] Buat akun Railway
- [ ] Buat PostgreSQL database
- [ ] Set environment variables
- [ ] Push ke GitHub (atau deploy via CLI)
- [ ] Setup domain
- [ ] Test semua fitur

## 🔧 Troubleshooting

### Error: Database Connection
```bash
# Check DATABASE_URL di Railway dashboard
# Pastikan format: postgres://user:pass@host:port/db
```

### Error: 500 Server
```bash
# Check logs
npx @railway/cli@latest logs

# Clear cache
npx @railway/cli@latest run php artisan config:clear
```

### Error: APP_KEY invalid
```bash
# Generate new key
php artisan key:generate --show

# Copy output ke APP_KEY di Railway
```

## 💰 Estimasi Biaya

| Service | Free Tier |
|---------|-----------|
| Railway App | 500 hours/month |
| PostgreSQL | 1GB storage |
| Outbound Bandwidth | 100GB/month |

**Total: GRATIS** (selama dalam free tier limits)

## 📞 Support

- Railway Docs: docs.railway.app
- Railway Discord: discord.gg/railway
