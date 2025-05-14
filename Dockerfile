FROM shinsenter/php:8.3-frankenphp

# Install imagick, swoole and xdebug
RUN phpaddmod http

# Add your instructions here
# For example:
# ADD --chown=$APP_USER:$APP_GROUP ./myproject/ /var/www/html/
