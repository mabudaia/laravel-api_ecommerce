# استخدم صورة PHP مع Apache
FROM php:8.2-apache

# تثبيت المتطلبات الأساسية
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev curl \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# تثبيت Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# نسخ ملفات Laravel إلى السيرفر
WORKDIR /var/www/html
COPY . .

# تثبيت مكتبات Laravel
RUN composer install --no-dev --optimize-autoloader

# إعداد أذونات التخزين والتخزين المؤقت
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# إعداد مفتاح التطبيق (اختياري - يمكن تعيينه لاحقًا)
RUN php artisan key:generate || true

# تعريف المجلد العام
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# تحديث إعداد Apache لاستخدام مجلد public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

# فتح المنفذ 80
EXPOSE 80

# بدء Apache
CMD ["apache2-foreground"]
