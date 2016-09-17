FROM centos:6

RUN rpm -ivh http://dl.fedoraproject.org/pub/epel/6/i386/epel-release-6-8.noarch.rpm
RUN yum update -y
RUN yum install -y httpd
RUN yum install --enablerepo=epel -y \
                              php \
                              php-cli \
                              php-gd \
                              php-mbstring \
                              php-mcrypt \
                              php-mysqlnd \
                              php-pdo \
                              php-xml \
                              php-xdebug \
                              php-memcached \
                              mysql-server phpmyadmin
RUN sed -i -e "s|^;date.timezone =.*$|date.timezone = Asia/Tokyo|" /etc/php.ini

COPY httpd.conf /etc/httpd/conf.d/zendphp.conf
COPY phpMyAdmin.conf /etc/httpd/conf.d/phpMyAdmin.conf

EXPOSE 80
CMD ["/usr/sbin/apachectl", "-D", "FOREGROUND"]
