# Data Transfer Object

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/codebooth/data-transfer-object/master.svg?style=flat-square)](https://travis-ci.org/codebooth/data-transfer-object)
[![SensioLabsInsight](https://img.shields.io/scrutinizer/quality/g/codebooth/data-transfer-object.svg?style=flat-square)](https://scrutinizer-ci.com/g/codebooth/data-transfer-object)
[![CodeClimate](https://img.shields.io/codeclimate/maintainability/codebooth/data-transfer-object.svg?style=flat-square)](https://codeclimate.com/github/codebooth/data-transfer-object)

A Data Transfer Object (DTO) is an object used to pass data between different 
layers in your application. It holds no business data, but only the minimum 
required data to transfer between layers or applications. 

The DTOs can help to put your unstructured arrays into a clean structure:
  - You clearly see what fields are available, and what type that field is 
  (using IDE you get fully type-hinting/autocomplete to be super quick here).
  - You get a clear exception/error on fields that are either unexpected or 
  missing where you want this kind of information – early on instead of 
  somewhere down the line.

## Getting Started

You can install the package via composer:

```bash
composer require codebooth/data-transfer-object
```

## Example

Let's consider the following scenario - you recieve following data from 
a HTTP POST request:

```php
$input = [
    'url' => 'https://github.com',
    'number' => 1234,
    'state' => 'open',
    'title' => 'new-feature',
];
```

A data transfer object of the above entity would look something as shown below: 

```php
use CodeBooth\DataTransferObject\DataTransferObject;

class ExampleObject extends DataTransferObject
{
    public $url;
    
    public $number;
    
    public $state;
    
    public $title;
}
```  

Now the data transfer object could be constructed like this:

```php
$object = new ExampleObject($input)

echo $object->url; // outputs 'https://github.com'
echo $object->number; // outputs 1234
echo $object->state; // outputs 'open'
``` 


## License

Copyright (c) 2019 CodeBooth. Released under the [MIT License](LICENSE.md).
