<?php
include_once (__DIR__ . '/scr/User.php');
include_once (__DIR__ . '/scr/DB_open.php');
require('vendor/autoload.php');

$faker = \Faker\Factory::create('pl');

function addRandomUser (PDO $base) {
    global $faker;

    $newUser = new User();

    $newUser->setUsername($faker->firstName() . ' '
        . $faker->lastName());
    $newUser->setEmail($faker->email);
    $newUser->setPassword($faker->domainWord);

    $newUser->saveToDB($base);
}

$pdo = myOpenDatabase('Warsztaty_2');

addRandomUser($pdo);