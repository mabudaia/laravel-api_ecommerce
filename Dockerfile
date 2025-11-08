# استخدم صورة PHP مع Apache
FROM php:8.2-apache

# تثبيت المتطلبات الأساسية للـ Laravel
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev curl \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd


# تثبيت Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# نسخ ملفات المشروع إلى مجلد السيرفر
WORKDIR /var/www/html
COPY . .

# تثبيت مكتبات Laravel
RUN composer install --no-dev --optimize-autoloader

# إنشاء ملف قاعدة بيانات SQLite في مجلد المشروع
RUN mkdir -p database \
    && touch database/database.sqlite \
    && chown -R www-data:www-data database \
    && chmod 664 database/database.sqlite


# إعداد متغيرات البيئة الافتراضية
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/tmp/database.sqlite

# تحديث إعداد Apache لاستخدام مجلد public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

# فتح المنفذ 80 (الافتراضي في Render)
EXPOSE 80

# توليد مفتاح التطبيق (لن يفشل إن كان موجود مسبقًا)
RUN php artisan key:generate || true

# تنفيذ الأوامر النهائية عند تشغيل الحاوية
CMD ["apache2-foreground"]
