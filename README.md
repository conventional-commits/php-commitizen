# PHP Commitizen

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/damianopetrungaro/php-commitizen/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/damianopetrungaro/php-commitizen/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/damianopetrungaro/php-commitizen/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/damianopetrungaro/php-commitizen/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/damianopetrungaro/php-commitizen/badges/build.png?b=master)](https://scrutinizer-ci.com/g/damianopetrungaro/php-commitizen/build-status/master)

Commitizen is a tool built for create good commits for a clean and readable git history.

This tool follow the [Conventional Commit specs](https://conventionalcommits.org/) and some best practices described in [this slides](https://slides.com/damianopetrungaro/working-with-git)

# Installation and usage

You can install it easily with composer

`$ php composer.phar require --dev damianopetrungaro/php-commitizen`

Usage is simple too 

`$ php vendor/bin/php-commitizen commit`

You can also 
- pass a flag for add all the file to the stage: `-a` 
- specify a custom configuration file adding the file path as argument 

You can ask for more information using: `$ php vendor/bin/php-commitizen commit --help`

# Configuration file

The configuration file must return an array (or partial override)

```
<?php

return [
    'type' => [
        'lengthMin' => 1, // Min length of the type
        'lengthMax' => 5, // Max length of the type
        'acceptExtra' => false, // Allow adding types not listed in 'values' key
        'values' => ['feat', 'fix'], // All the values usable as type
    ],
    'scope' => [
        'lengthMin' => 0, // Min length of the scope
        'lengthMax' => 10, // Max length of the scope
        'acceptExtra' => true, // Allow adding scopes not listed in 'values' key
        'values' => [], // All the values usable as scope
    ],
    'description' => [
        'lengthMin' => 1, // Min length of the description
        'lengthMax' => 44, // Max length of the description
    ],
    'subject' => [
        'lengthMin' => 1, // Min length of the subject
        'lengthMax' => 50, // Max length of the subject
    ],
    'body' => [
        'wrap' => 72, // Wrap the body at 72 characters
    ],
    'footer' => [
        'wrap' => 72, Wrap the footer at 72 characters
    ],
];
```
