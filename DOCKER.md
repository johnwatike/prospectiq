# Docker Deployment Guide - Perfex CRM / CodeIgniter 3

This guide explains how to build and run the Perfex CRM application using Docker in production mode.

## Prerequisites

- Docker Engine 20.10+
- Docker Compose 2.0+
- Minimum 2GB RAM recommended
- Minimum 10GB disk space

## Quick Start

1. **Build and run the container:**
   ```bash
   docker-compose up -d --build
   ```

2. **Access the application:**
   Open your browser and navigate to: http://localhost:8081

3. **If this is a fresh installation:**
   - Navigate to the installation wizard at: http://localhost:8081/installxx
   - Follow the installation steps
   - Configure your database connection

## Production Features

- ✅ PHP 8.2 with Apache
- ✅ Production dependencies only (no dev dependencies)
- ✅ Optimized Composer autoloader
- ✅ OPcache enabled for better performance
- ✅ Proper file permissions
- ✅ Security headers configured
- ✅ PHP version hidden for security
- ✅ Apache mod_rewrite enabled
- ✅ ImageMagick support
- ✅ Health checks configured
- ✅ Large file upload support (50MB)

## Configuration

### Environment Variables

The application uses configuration files in `application/config/`. Key files:

- `app-config.php` - Main application configuration (database, base URL, etc.)
- `config.php` - CodeIgniter configuration

### Port Configuration

Default port is `8081`. To change it, edit `docker-compose.yml`:

```yaml
ports:
  - "YOUR_PORT:80"
```

### Database Configuration

If you need a MySQL database, uncomment the MySQL service in `docker-compose.yml`:

```yaml
mysql:
  image: mysql:8.0
  container_name: perfex-mysql
  environment:
    MYSQL_ROOT_PASSWORD: your_root_password
    MYSQL_DATABASE: perfex_crm
    MYSQL_USER: perfex
    MYSQL_PASSWORD: your_password
  volumes:
    - mysql_data:/var/lib/mysql
  ports:
    - "3306:3306"
  networks:
    - perfex-network
```

Then update your `app-config.php` with the database credentials.

## Building the Image

```bash
docker build -t perfex-crm .
```

## Running the Container

```bash
docker run -d -p 8081:80 --name perfex-crm perfex-crm
```

## Stopping the Container

```bash
docker-compose down
```

Or:

```bash
docker stop perfex-crm
docker rm perfex-crm
```

## Viewing Logs

```bash
docker-compose logs -f
```

Or:

```bash
docker logs -f perfex-crm
```

## File Structure

```
public_html/
├── Dockerfile              # Production Docker image
├── docker-compose.yml      # Docker Compose configuration
├── .dockerignore          # Files to exclude from Docker build
├── index.php              # Entry point
├── application/           # Application code
│   ├── config/            # Configuration files
│   ├── controllers/       # Controllers
│   ├── models/            # Models
│   ├── views/             # Views
│   ├── cache/             # Cache directory (mounted as volume)
│   ├── logs/              # Logs directory (mounted as volume)
│   └── vendor/            # Composer dependencies
├── system/                # CodeIgniter system files
├── modules/               # Application modules
├── uploads/               # Uploaded files (mounted as volume)
├── temp/                  # Temporary files (mounted as volume)
└── backups/               # Backup files (mounted as volume)
```

## Production Optimizations

The Dockerfile includes:
- OPcache configuration for PHP performance
- Optimized Composer autoloader
- Production dependencies only
- Security hardening (PHP version hidden)
- Proper file permissions
- Large file upload support
- ImageMagick for image processing

## Troubleshooting

### Permission Issues

If you encounter permission issues with writable directories:

```bash
docker exec -it perfex-crm chown -R www-data:www-data /var/www/html/application/cache
docker exec -it perfex-crm chown -R www-data:www-data /var/www/html/application/logs
docker exec -it perfex-crm chown -R www-data:www-data /var/www/html/uploads
docker exec -it perfex-crm chmod -R 775 /var/www/html/application/cache
docker exec -it perfex-crm chmod -R 775 /var/www/html/application/logs
docker exec -it perfex-crm chmod -R 775 /var/www/html/uploads
```

### View PHP Info

```bash
docker exec -it perfex-crm php -i
```

### Access Container Shell

```bash
docker exec -it perfex-crm bash
```

### Check Application Logs

```bash
docker exec -it perfex-crm tail -f /var/www/html/application/logs/log-*.php
```

### Rebuild After Code Changes

```bash
docker-compose up -d --build
```

## Notes

- The `application/cache`, `application/logs`, `uploads`, `temp`, and `backups` directories are mounted as volumes for persistent data
- All dependencies are installed during the build process
- The image uses PHP 8.2 with Apache
- Minimum PHP version required: 8.1
- Make sure your `app-config.php` has the correct base URL configured
- For production, ensure SSL/TLS is configured (consider using a reverse proxy like nginx)

## Security Considerations

1. **Change default passwords** in `app-config.php`
2. **Use environment variables** for sensitive data (consider using Docker secrets)
3. **Enable HTTPS** in production (use a reverse proxy)
4. **Regular backups** of the database and uploads directory
5. **Keep dependencies updated** by rebuilding the image periodically
6. **Review file permissions** regularly

## Performance Tips

1. **Enable OPcache** (already configured in Dockerfile)
2. **Use a reverse proxy** (nginx) for static file serving
3. **Configure database connection pooling**
4. **Use Redis/Memcached** for session storage if needed
5. **Optimize images** before upload
6. **Enable gzip compression** at the web server level
