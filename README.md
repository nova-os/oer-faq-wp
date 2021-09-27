# OER FAQ

In dieser Repository befindet sich der Quellcode für das OER-FAQ. Das Projekt basiert auf Wordspress auf
Basis des [Bedrock-Projektes](https://roots.io/bedrock/). Die Struktur des Projektes ist der
[Bedrock-Dokumentation](https://roots.io/bedrock/) zu entnehmen. Die Projektspezifischen Wordpress-Anpassungen
sind im Theme oer-faq-child-theme zu finden.

## Wordpess-Themes und Plugins

### Theme
 * Das [Bootscore.me Theme](https://bootscore.me/category/documentation/) (MIT License) dient als Basis-Theme.

### Essentielle Plugins:
 * [GDPR Cookie Consent](https://www.webtoffee.com/plugins/) (GPLv3) Easily set up cookie notice, cookie policy and get GDPR cookie compliance with our cookie scan. Supports GDPR, DSGVO, RGPD, LGPD, CCPA Do Not Sell and CNIL.
 * [Gravity Forms](https://gravityforms.com) (GPLv2 or later) Easily create web forms and manage form entries within the WordPress admin.
 * [Was This Article Helpful?]( https://yellowpencil.waspthemes.com) (GPLv2 or later) Simple article feedback plugin. 

### Nützliche Plugins zur Administation
 * Autoptimize (GPLv2) Autoptimize speeds up your website by optimizing JS, CSS, images (incl. lazy-load), HTML and Google Fonts, asyncing JS, removing emoji cruft and more.
 * [Disable Comments](https://wpdeveloper.net/) (GPLv3.0 or later) Allows administrators to globally disable comments on their site. Comments can be disabled according to post type. Multisite friendly. Provides tool to delete comments according to post type.
 * [Duplicate Page](https://duplicatepro.com) (GPLv2 or later) Duplicate Posts, Pages and Custom Posts easily using single click. You can duplicate your pages, posts and custom post by just one click and it will save as your selected options (draft, private, public, pending).
 * [Email Address Encoder](https://encoder.till.im/) (GPLv3) A lightweight plugin that protects email addresses from email-harvesting robots by encoding them into decimal and hexadecimal entities.
 * Simple Image Sizes (GPLv2 or later) This plugin allow create custom image sizes for your site. Override your theme sizes directly on the media option page.
 * User Switching (GPLv2 or later) This plugin allows you to quickly swap between user accounts in WordPress at the click of a button.
 * [WordPress Zero Spam](https://www.benmarshall.me/wordpress-zero-spam) (GNU GPLv3) uses AI in combination with proven spam detection techniques and databases of known malicious IPs from around the world to detect and block unwanted visitors.

## Ausführen des Projektes mit Docker

docker und docker-compose müssen installiert sein:

  * Copy `.env.example` to `.env`.
  * Make sure `DOMAIN` and `HTTP_PORT` are set to a valid URL Port for local development. For example:
    ```
    DOMAIN='http://orm-faq.internal'
    HTTP_PORT=80
    ```
 * Add the local domain to your `/etc/hosts` file (if not `localhost` is used).
 * Install dependencies: `./bin/composer install`
 * Start the webserver: `docker-compose up` (or in the background `docker-compose up -d`)

## Theme-Kompilieren

Das Theme benutzt Laravel Mix zur generierrung der Asset-Dateien (JS/CSS). Mehr details dazu in der
[Laravel Mix Dokumentation](https://laravel.com/docs/8.x/mix#running-mix).

Der watch-Modus kann folgenderweise gestartet werden:

```
  cd src/web/app/themes/oer-faq-child-theme
  npm run watch
```
## Tools
### Run PHP-Composer

```
./bin/composer <<command>>
```

### Start phpMyAdmin

Start phpMyAdmin on http://localhost:9000

```
./bin/phpmyadmin up
```

(or `./bin/phpmyadmin up -d`, `./bin/phpmyadmin stop`, `./bin/phpmyadmin rm`)

## Lizenz

Das Theme steht unter MIT-Lizenz.
## Further reading

  * Bedrock Dokumentation  [https://roots.io/bedrock/docs/](https://roots.io/bedrock/docs/).
  * Laravel-Mix [https://laravel.com/docs/8.x/mix#running-mix](https://laravel.com/docs/8.x/mix#running-mix).
