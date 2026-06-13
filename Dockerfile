FROM php:8.2-apache

# تثبيت الإضافات المطلوبة للارافيل
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# تفعيل مود الـ Rewrite الخاص بـ Apache لتشغيل روابط لارافيل بشكل صحيح
RUN a2enmod rewrite

# توجيه السيرفر إلى مجلد public الخاص بلارافيل
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# نسخ ملفات المشروع بالكامل (بما فيها مجلد public/build الذي أنشأناه محلياً)
COPY . /var/www/html

# تثبيت Composer والحزم
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# ضبط الصلاحيات للمجلدات الأساسية
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# إجبار لارافيل على إرسال الأخطاء وتفعيل وضع التطوير لتظهر في الـ Logs
ENV LOG_CHANNEL=stderr
ENV APP_LOG_LEVEL=debug
ENV APP_DEBUG=true

EXPOSE 80

# تهيئة قاعدة البيانات، تصفير الإعدادات، بناء الجداول تلقائياً، تنظيف كاش النظام، ثم التشغيل
CMD ["sh", "-c", "mkdir -p database && touch database/database.sqlite && chown -R www-data:www-data database && php artisan config:clear && php artisan migrate --force && php artisan cache:clear && apache2-foreground"]
