FROM insignficant/laravel-base-image:latest
WORKDIR /var/www/html
COPY . /var/www/html

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV MAX_UPLOAD_SIZE 10M

RUN chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap \
        && chmod -R 777 /var/www/html/storage \
        /var/www/html/bootstrap
        
COPY docker/php/php.ini /usr/local/etc/php/php.ini

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
  
EXPOSE 80

ENTRYPOINT [ "sh","docker/docker-entrypoint.sh" ]