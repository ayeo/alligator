# Alligator [![Build Status](https://travis-ci.org/ayeo/alligator.svg?branch=master)](https://travis-ci.org/ayeo/alligator) [![Coverage](https://codecov.io/gh/ayeo/alligator/branch/master/graph/badge.svg)](https://codecov.io/gh/ayeo/alligator)
![Logo](alligator.png) 

DO NOT USE IT YET: it is draft 

Extremely simple yet powerful validation utility. Alligator is intended to fulfill any kind of resource validation.
 It is happy to traverse trough nested objects. Alligator also works well with protected/private properties.

## Installation

```bash
composer requrie ayeo/alligator
```

## Rules

Alligator use scenario is all about working with entire resoucr objects. Rules are defined at level of the object also.
Lets consider simple example.
```php
class Person 
{
    /** @var string */
    private $firstname;
    /** @var string */
    private $lastname;
    /** @var int */
    private $age;
    /** @var string */
    private $insuranceNumber;
    
    ...
    //getters/setters comes here
}
```
Rules by default are defined as simple array (but this is not mandatory - see "Custom format" for more details).
```php
$recipe = [
    'firstname' => [
        ['not_null', 'required'],
        ['min_length:5, 'too_short'],
        ['max_length:25, 'too_long'],
        ['letters', 'letters_only']
     ],
    'lastname' => [
        ['not_null', 'required'],
        ['min_length:5, 'too_short'],
        ['max_length:25, 'too_long'],
        ['letters', 'letters_only']
    ],
    'age' => 
        ['integer', 'must_be_integer'], //single rule
    'insuranceNumber' =>
        'age>21' => ['regexp:^[0-4]{4}-[0-4]-{4}$', 'invalid_insurance_number']
]
```
Above rules expect firstname and lastname to be letters only and long between 5 and 25 chars. Age is optional but
if set must be integer. Insurance number is required only if age is above 21 and it's defined as an regexp here. 

## Usage

Having rules at place we are ready to try what Alligator can do for us.
```php
$food = new Person('Rocky', 'Balboa');
$food->setAge(37);
$food->setInsuranceNumber('3423-543'); 

$alligator = new Alligator();
$result = $alligator->taste($food, $recipe);
$errors = $alligator->getErrors();
```
Errors has same structure as input resource object.  (todo: describe collection checking)

