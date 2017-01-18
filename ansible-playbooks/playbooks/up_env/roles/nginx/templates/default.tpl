{% if letsencrypt is defined and letsencrypt_stage > 0 %}
server {
    listen 0.0.0.0:80 default;
    listen [::]:80 default;

    server_name {{ nginx.servername }};

    return 301 https://$host$request_uri;
}
{% endif %}
  
server {

    server_name {{ nginx.servername }};

{% if letsencrypt is defined and letsencrypt_stage > 0 %}
    listen              443 ssl;
    ssl_certificate     {{ letsencrypt.certs_dir }}/fullchain.pem;
    ssl_certificate_key {{ letsencrypt.certs_dir }}/privkey.pem;
{% else %}
    listen 0.0.0.0:80 default;
    listen [::]:80 default;
{% endif %}

    root {{ nginx.docroot }};
    index index.html index.php;

    location ~* \.(jpg|jpeg|gif|png|css|js|ico|xml)$ {
        access_log        off;
        log_not_found     off;
        expires           1d;
    }

    location / {
        client_max_body_size 10m;
        client_body_buffer_size 128k;
        try_files $uri $uri/ /index.php?$query_string;
    }

{% if letsencrypt is defined and letsencrypt.docroot is defined %}
    location ^~ /.well-known {
        alias {{ letsencrypt.docroot }}/.well-known;
    }
{% endif %}

    error_page 404 /404.html;

    error_page 500 502 503 504 /50x.html;
        location = /50x.html {
        root /usr/share/nginx/www;
    }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
