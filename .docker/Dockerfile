FROM php:8.2-apache

USER root

WORKDIR /var/www/html

# install all the dependencies and enable PHP modules
RUN apt-get update && apt-get upgrade -y && apt-get install -y \
      gnupg2 \
      procps \
      git \
      unzip \
      libicu-dev \
      zlib1g-dev \
      libxml2 \
      libxml2-dev \
      libreadline-dev \
      supervisor \
      cron \
      sudo \
      libzip-dev \
      inetutils-ping \
      unixodbc-dev \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
      pdo_mysql \
      sockets \
      intl \
      opcache \
      zip \
    && rm -rf /tmp/* \
    && rm -rf /var/list/apt/* \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get clean

# Create log directory for supervisor
RUN mkdir -p /var/log/supervisor

# Copy apache config file
COPY .config/vhost.conf /etc/apache2/sites-available/000-default.conf

# Copy crontab file to the cron.d directory
COPY .config/crontab /etc/cron.d/crontab

# Copy supervisor config
COPY .config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/crontab

# Apply cron job
RUN crontab /etc/cron.d/crontab

# Get and Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory ownership
RUN a2enmod rewrite

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Ensure storage permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && chmod 775 -R storage \
    && chown -R www-data:www-data .

# Install Node.js and npm
RUN apt-get install -y curl \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm

# run Laravel migrations and seeders
COPY .docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]