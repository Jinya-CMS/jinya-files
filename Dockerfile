FROM nginx:stable

VOLUME /var/www

COPY ./nginx/nginx.conf /etc/nginx/conf.d/default.conf
