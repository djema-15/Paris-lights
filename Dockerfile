FROM php:7.4-fpm-alpine

# Apk install
RUN apk --no-cache update && apk --no-cache add bash git



# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && php composer-setup.php && php -r "unlink('composer-setup.php');" && mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-install pdo pdo_mysql
COPY . /var/www/html/Paris_lights

# Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony/bin/symfony /usr/local/bin/symfony
ARG INSTALL_INTL=false
RUN if [ ${INSTALL_INTL} = true ]; then \
    # Install intl and requirements
    apt-get install -y zlib1g-dev libicu-dev g++ && \
    docker-php-ext-configure intl && \
    docker-php-ext-install intl \
;fi

WORKDIR /var/www/html/