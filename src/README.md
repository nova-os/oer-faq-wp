# nova Standard Wordpress

## Requirements

* PHP >= 7.1
* Composer - [Install](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)

## Installation

1. Update environment variables in the `.env` (`src` folder, copy .env.example) file:
  * Database variables
    * `DB_NAME` - Database name
    * `DB_USER` - Database user
    * `DB_PASSWORD` - Database password
    * `DB_HOST` - Database host
    * Optionally, you can define `DATABASE_URL` for using a DSN instead of using the variables above (e.g. `mysql://user:password@127.0.0.1:3306/db_name`)
  * `WP_ENV` - Set to environment (`development`, `staging`, `production`)
  * `WP_HOME` - Full URL to WordPress home (https://example.com)
  * `WP_SITEURL` - Full URL to WordPress including subdirectory (https://example.com/wp)
  * `AUTH_KEY`, `SECURE_AUTH_KEY`, `LOGGED_IN_KEY`, `NONCE_KEY`, `AUTH_SALT`, `SECURE_AUTH_SALT`, `LOGGED_IN_SALT`, `NONCE_SALT`
    * Generate with [wp-cli-dotenv-command](https://github.com/aaemnnosttv/wp-cli-dotenv-command)
    * Generate with [our WordPress salts generator](https://roots.io/salts.html)
  * `WP_ALLOW_MULTISITE` - Activating Multisite (set to true/false)
2. Add theme(s) in `web/app/themes/` as you would for a normal WordPress site
3. **Set the document root on your webserver to Bedrock's `web` folder: `/path/to/site/web/`**
4. Access WordPress admin at `https://example.com/wp/wp-admin/`
5. Install:
    ```sh
    cd src
    php ../bin/composer.phar install
    ```
    or
    ```sh
    cd src
    composer install
    ```


## Multisite
See Readme in git root: [Readme](https://bitbucket.org/codekunst/standard-wp/src/master/README.md)

## [Bedrock](https://roots.io/bedrock/)
[![Packagist](https://img.shields.io/packagist/v/roots/bedrock.svg?style=flat-square)](https://packagist.org/packages/roots/bedrock)
[![Build Status](https://img.shields.io/travis/roots/bedrock.svg?style=flat-square)](https://travis-ci.org/roots/bedrock)

Bedrock is a modern WordPress stack that helps you get started with the best development tools and project structure.

### Bedrock Documentation

Bedrock documentation is available at [https://roots.io/bedrock/docs/](https://roots.io/bedrock/docs/).
