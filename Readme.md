# Url Shortener #

## Introducing ##

This application is simple url shorter tool. It makes possible 
to create a short link to any url automatically or with desired token.
Also origin link will be checked and with wrong url provided short link will not 
be created. Short link be stored during 14 days and then url pair 
will be removed from database. 

## System requirements ##

  * PHP 5.7 
  * Mysql server (if you desire to use mysql for storing urls)
  * Redis server (if you desire to use redis for storing urls)
  * Composer 

## Installation guide ##

#### Clone project ####

`$ git clone https://github.com/Rsnake2478/url_shortener.git`

#### Install project requirements ####
`$ cd url_shortener && composer install`

#### Configure web server ####

You need to configure your web server to {project}/public directory 
as a root. Below nginx configuration example.
`
server {
    server_name url.localhost;
    root /var/www/url-shortener/public;

    location / {
        # try to serve file directly, fallback to front controller
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        fastcgi_pass unix:/run/php/php7.0-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param HTTPS off;
    }
}
`

#### Configure application ####
{project}/app/config directory contains config.php file. In this file you need to specify base url for application,
database adapter (mysql or redis) and used adapter configuration. Also you can change log level and application log file.

#### Prepare databases ####

If you use mysql adapter you need to prepare database. Execute this statement to database:
`CREATE TABLE url (
  shortUrl varchar(255) NOT NULL,
  longUrl varchar(1024) NOT NULL,
  validBefore int(11) unsigned NOT NULL,
  PRIMARY KEY (shortUrl)
) ENGINE=InnoDB
`  
If you desire to run test, you need to create test database too.

## Using guide ##
After you load application in your browser you`ll see main page with two fields:
Full Url and Short Url token. If you just want to create short link then enter Full Url
and press save button - short link will be created automaticaly. 

Full Url must be callable (returns http 200 or 302 code) and no longer that 1024 character.
Short url token (if used) must be 4 to 255 character long and contains only lowercase letters and numbers in any combination.

After short link creation you will see new link page. In this page you can try your new link and go back to short url creation.
  

## Additional ##

#### perform codestyle check ###

in project directory run
`$ ./vendor/bin/phpcs src --standard=PSR2`

#### perform unit tests ###

in project directory run
`$ ./vendor/bin/phpunit test`
