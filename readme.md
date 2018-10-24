# Alligator [![Build Status](https://travis-ci.org/ayeo/alligator.svg?branch=master)](https://travis-ci.org/ayeo/alligator)

DO NOT USE IT YET: it is draft 

Extremely simple yet powerful validator

Install
=======

Using composer
```
composer require ayeo/alligator
```

Example objects
===============

Let's consider simplified objects as below
```php
class Company
{
    /** @var Address */
    private $address;
    
    /** @var string */ 
    public $name;
    
    public function getAddress(): Address
    {
        return $this->address();
    }
}
```

```php
class Address
{
    /** @var string */ 
    public $street;
    
    /** @var string */ 
    public $town;
    
    /** @var string */ 
    public $countries;
    
}
```

## Basic usage


Validator is able to traversable through nested objects. In case of private/protected propeties object must provide respective getter. Validation rules are defined as array. One field may get more than one rule. 
```php
$rules = [
    'name' => new Rule(new MinLength(5), 'Name must be at least 5 chars long'],
    'address' => [
        'street' => new Rule(new MinLength(5), 'Street name is to shrot'),
        'town' => new Rule (new MinLength(5), 'Town name is to short),
        'country' => new OneOf(['USA', 'UK', 'Poland']), 'Country is not allowed')
    ]
] 
```

```php
$company = new Company;
$company->name = "Test Company";

$validator = new Validator();
$isValid = $validator->validate($company, $rules);
$errors = $validator->getErrors();
```

## Allowed object properties checking

todo: describe "*"

## Conditional rules

todo: describe conditional rules

## Related

todo: desribe validating field base on other field value 

Availaible constraints
======================

- Length
- MinLength
- MaxLength
- Integer
- Numeric
- NumericMin
- NumericMax
- NotNull
- NonEmpty
- ClassInstance
- NotClassInstance



