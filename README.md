<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About

An incomplete SMS sending service for ReNet sms. When I saw the project brief, I was both glad and unsure. Glad because it looked like a good excuse to finally learn Laravel, but unsure in that, the extent of my PHP development has been WordPress plugins and themes. Also, as a developer working primarily in front-end for the past two years, you can imagine my concern when reading "no front-end necessary"!

In my spare moments over the past week and a bit, I've been reading the Laravel docs. Over the past day and a bit, I've been trying to put into practice what I've read.

I wasn't able to put much time into this, getting it functioning was my top priority. This came at the expense of code quality and good design decisions. Code should be split into more modular units, be more reusable, and the database model should have been designed better (I find myself traversing many relationships to get the info I need). Happy to point out all areas that are lacking, and what I should do instead.

## Decisions
- Laravel is likely overkill for this project since there is no front-end. I did consider Symfony, but have been meaning to learn Laravel.
- I interpreted testing from CLI to mean either writing PHP unit tests, or a cli client of sorts. I chose the latter, utilising php artisan commands.
- I interpreted the bulk of the task as being an API, with the cli commands hitting the API (in most cases).
- I envision 'Product' to be the top of the hierarchy, with 'Tenants'/Users as children. By default, Users are unique by email address, but I've made them unique by a combination of email and product_id. This is because a user with the same email could register for multiple products.
- Theres a one-to-one correlation between users and phone-numbers (I haven't explicitly defined this in the user model).
- Changing sms service providers happens on the product level. So the provider for the product is changed, and all containing users/tenants send messages through the new provider. I've ignored the fact that switching providers would probably change phone numbers.
- Since there was a note that third-party services code isn't important, this fell to the bottom of the priority list. I just gave the service providers a fail_rate, and dispatched an event when a message is sent. The event listener is the "service provider", and just updates the status of the message and assigns billing rates.
- Following on from the previous point, I should have created an API for the service provider, taking as input the webhook url, which returns a UUID and current status. Then update the message status when the webhook is hit.
- Left authentication out altogther.


## Installation
- Clone repository to local machine: git clone url
- Move to project directory: cd renet_sms
- Install dependencies: composer install
- Seed database: php artisan migrate:fresh --seed
- Serve on localhost: php artisan serve


## Usage
- Since API is incomplete, adding different service providers/products/etc should be done in app/database/seeders/DatabaseSeeder.php followed by a php artisan migrate:fresh --seed
- Limited validation and error handling. Go easy :)
- Can use cli commands to hit the API, or your favourite API tester. Check routes in routes/api.php


## Commands
All commands preceeded by "php artisan". If unsure of expected parameters, use "php artisan help scope:command

products:changeprovider - Change a products SMS provider 

products:list - Get a list of existing products 

providers:list - Get a list of sms providers 

sms:list - List sms messages

sms:send - Send SMS from tenant

users:list - List users and their phone numbers

## Challenges
- Limited time.
- building more advanced db queries. Particularly ( (whereConditionA) && ( whereConditionB || whereConditionC ) )
conditional api resources depending on endpoint. Less of a challenge more a question of best practice.
- Defining database model as I was coding was a mistake. Should redesign the tables and their relationships.


## Todo
- Endpoint and command validation
- Error handling on endpoints and commands
- Make SMS provider its own API, rather than a simple event listener that changes the message status directly on the db.
- Create exception handler so that internal errors are returned as json objects, not as blade templates.
- Ensure right HTTP response is being returned.
- Split controller code into functions. Increase reusability (table generating code is good example)
- Redesign db model and relationships
