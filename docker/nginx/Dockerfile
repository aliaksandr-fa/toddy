FROM nginx

COPY config/nginx.conf /etc/nginx/nginx.conf
COPY config/conf.d /etc/nginx/conf.d
COPY bin/boot.sh /usr/local/bin/boot_nginx

EXPOSE 80 443

CMD ["sh", "/usr/local/bin/boot_nginx"]