FROM httpd:2.4

RUN mkdir -p /usr/local/apache2/conf/custom \
&& mkdir -p /var/www/html \
&& sed -i '/LoadModule proxy_module/s/^#//g' /usr/local/apache2/conf/httpd.conf \
&& sed -i '/LoadModule proxy_fcgi_module/s/^#//g' /usr/local/apache2/conf/httpd.conf \
&& echo >> /usr/local/apache2/conf/httpd.conf && echo 'Include conf/custom/*.conf' >> /usr/local/apache2/conf/httpd.conf

RUN sed -i '/LoadModule headers_module/s/^#//g' /usr/local/apache2/conf/httpd.conf
RUN sed -i '/LoadModule rewrite_module/s/^#//g' /usr/local/apache2/conf/httpd.conf