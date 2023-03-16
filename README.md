<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Project Run Instruction

git clone https://github.com/riad1302/covid-vaccine-registration-system.git

docker run --rm --interactive --tty -v $(pwd):/app composer install

./vendor/bin/sail up -d

copy .env.example to .env

./vendor/bin/sail php artisan key:generate

./vendor/bin/sail php artisan migrate

./vendor/bin/sail php artisan db:seed
 
 ## Queue Run
 
./vendor/bin/sail queue:work

## Task Scheduler Run

./vendor/bin/sail php artisan schedule:run

## User search optimise the performance 

use caching server redis. when user registration then user 
vaccination date plus other some information store redis server.
After successfully registration user search information get redis 
server.

## Additional requirement of sending ‘SMS’ 

 dispatch sms async job  

