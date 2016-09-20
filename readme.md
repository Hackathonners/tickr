# Tickr - Built by Hackathonners

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Requirements

* [PHP](http://php.net/) >= 5.6
* [Composer](https://getcomposer.org/) - Installs package dependencies
* [NodeJS](https://nodejs.org/en/) - Provides NPM to install node packages

## Installation

Download or clone this repository.

Rename ```.env.example``` to ```.env``` and fill the options.

Run the following commands:

```
composer install
npm install
php artisan key:generate
php artisan migrate
php artisan db:seed
```

If you are making changes to JavaScript or Styles make sure you run ```gulp watch```.


## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
