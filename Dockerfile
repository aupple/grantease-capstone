# Dockerfile (use this)
FROM php:8.2-cli

# Install OS packages required for gd and common tools
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Configure & install PHP extensions (gd, pdo_mysql)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Copy Composer binary from official composer image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working dir
WORKDIR /app

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --optimize-autoloader --no-scripts

# Expose port (Railway provides $PORT)
EXPOSE 8080

# Start command — use built-in PHP server (simple)
CMD php -S 0.0.0.0:${PORT:-8080} -t public
