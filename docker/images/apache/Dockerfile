FROM php:7.1-apache

RUN echo 'APT::Install-Recommends "0";' >>/etc/apt/apt.conf.d/99-recommends && \
    echo 'APT::Install-Suggests "0";' >>/etc/apt/apt.conf.d/99-suggests

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && \
    apt-get install -y automake \
                       build-essential \
                       zlib1g-dev \
                       libtool && \
    apt-get -y autoremove && \
    rm -rf /var/lib/apt/lists/*

ENV RABBITMQ_VERSION 0.8.0

RUN cd /tmp && \
    curl --stderr - -L -O https://github.com/alanxz/rabbitmq-c/releases/download/v${RABBITMQ_VERSION}/rabbitmq-c-${RABBITMQ_VERSION}.tar.gz && \
    tar xf rabbitmq-c-${RABBITMQ_VERSION}.tar.gz && \
    cd rabbitmq-c-${RABBITMQ_VERSION} && \
    autoreconf -i && \
    ./configure && \
    make && \
    make install && \
    cd /tmp && \
    rm -rf rabbitmq-c-${RABBITMQ_VERSION} && \
    rm rabbitmq-c-${RABBITMQ_VERSION}.tar.gz

RUN pecl install xdebug amqp-1.7.0 && \
    docker-php-ext-enable xdebug amqp && \
    docker-php-ext-install pdo_mysql

# Due to issue https://github.com/docker-library/php/issues/233
# Crappy workaround
RUN docker-php-ext-install zlib; exit 0
RUN cp /usr/src/php/ext/zlib/config0.m4 /usr/src/php/ext/zlib/config.m4
RUN docker-php-ext-install zlib

ADD bootstrap.sh /root/bootstrap.sh

ENTRYPOINT [ "sh", "/root/bootstrap.sh" ]

RUN a2enmod rewrite && \
    mkdir -p /var/www/onyx/var && \
    chown -Rf www-data:www-data /var/www/onyx && \
    rm -r /var/www/html && \
    ln -s /var/www/onyx/www /var/www/html

WORKDIR /var/www/onyx

COPY php.ini /usr/local/etc/php/conf.d

CMD ["apache2-foreground"]
