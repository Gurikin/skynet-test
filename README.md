# skynet-test
Для понимания как был реализован редирект при 404 ошибке, прикладываю файл настроек nginx
```server {
   	listen 80 default_server;
   	listen [::]:80 default_server;	
   
   	root YOUR_SERVER_ROOT_FOLDER;
   
   	index index.php index.html index.htm index.nginx-debian.html;
   
   	server_name YOUR_SITE_URL_HERE;
   
   	location / {
# Если не нашли нужный адрес - идем до соответствующего index.php
   		try_files $uri $uri/ /tests/skynet-test/index.php;
   	}
   
     index test.php index.php index.html index.htm index.nginx-debian.html;
   
   	location ~ \.php$ {
     	include snippets/fastcgi-php.conf;
     	fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
   	}
   
   	location ~ /\.ht {
   		deny all;
   	}
   }
```