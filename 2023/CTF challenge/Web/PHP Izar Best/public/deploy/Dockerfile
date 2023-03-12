FROM ubuntu:16.04

RUN sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list

ENV TZ=Asia/Shanghai
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update -y

RUN apt-get install -y wget curl

RUN apt-get install -y nginx \
    php7.0-fpm


COPY ./default /etc/nginx/sites-available/default
COPY ./src/* /var/www/html/

COPY ./start.sh /start.sh
RUN rm /var/www/html/*.html
RUN chmod a+x /start.sh

RUN echo 'flag{sens1t1ve_sess10n}' > /flag
RUN chown -R www-data:www-data /var/www/html \
    && ln -s /var/www/html /html

RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*

EXPOSE 80 
CMD ["/start.sh"]