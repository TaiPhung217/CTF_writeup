FROM ubuntu:16.04

RUN sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list

ENV TZ=Asia/Shanghai
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update -y

RUN apt-get install -y wget curl

RUN apt-get install -y nginx \
    php7.0-fpm

RUN apt-get install -y cron
RUN echo '0 4 * * * root rm -rf /sandbox/*' >> /etc/crontab

COPY ./default /etc/nginx/sites-available/default
COPY ./src/index.php /var/www/html/index.php
COPY ./start.sh /start.sh
RUN rm /var/www/html/*.html
RUN chmod a+x /start.sh

RUN echo 'flag{rce-me}' > /flag
RUN ln -s /bin/true /bin/orange
RUN chown -R www-data:www-data /var/www/html \
    && ln -s /var/www/html /html
RUN mkdir /sandbox
RUN chown -R www-data /sandbox
RUN chmod -R 775 /sandbox

RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*

EXPOSE 80 
CMD ["/start.sh"]