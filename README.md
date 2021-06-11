# Generator Email Unofficial API
###### Very simple Object Oriented  library to receive messages from generator.email build with php 
![](https://i.ibb.co/W28CyS9/ezgif-com-gif-maker.gif)

#### Available Method
| Method  | Description  |
| ------------ | ------------ |
| getEmail()  | To Get New Random Email  |
| CheckValidation()  | To Check Uptime Email  |
| ReadEmail()  | To Read All Inbox Messages  |
| ReadSpecific()  | To Read Specific Inbox Messages  |
| DeleteMessage()  | To Delete Specific Inbox Messages  |
| DeleteAll()  | To Delete All Inbox Messages  |
| getData()  | To Convert Object to Json  |
| GetSpecific()  | To find string using specific pattren / Regex  |

#### How to use
`git clone https://github.com/sandrocods/New-generator-email.git`
```php
include 'src/GeneratorEmail.php'; // Include Library File

$GeneratorEmail = new GeneratorEmail(); // Inisiasi Library File
```
#### Example Method Use
Convert Object to Json

```php
<?php
include 'src/GeneratorEmail.php';
$GeneratorEmail = new GeneratorEmail();

$GeneratorEmail->getEmail(); // Getting New Email 

print_r($GeneratorEmail->ReadEmail()->getData());

/* Result Array
Array
(
    [status] => 1
    [Count_message] => 1
    [Dell_key] => t223j5748423v2y344946426e4k4v274m4u5x3946434347424s5h5b406d476j5h4l4n206r5u5g4q5l4k40484b433d48454t2t264v2s213s28474f59454w2j5b4l53454y284q2o594w2w2v214n5t584r5c484v57476s2w2i5q2o2o2
    [Data] => Array
        (
            [From] => krisandromartinus@gmail.com
            [Subject] => OTP
            [Time] => 2021-06-11 12:14:51
            [Body_message] => OTP : 3105
        )

)
/*
```
Get Specific String in Body Message Using Regex
```php
<?php

include 'src/GeneratorEmail.php';

$GeneratorEmail = new GeneratorEmail();
$GeneratorEmail->getEmail(); // Get New Email Address

// Only Works in one messages in inbox
print_r($GeneratorEmail->ReadEmail()->GetSpecific('/OTP : (.*)/m')); // Get Specific String Using Regex Pattren

/* Result Regex
Array
(
    [0] => Array
        (
            [0] => OTP : 3192
        )

    [1] => Array
        (
            [0] => 3192
        )

)
/*
```
Get Specific String in Body All Messages Using Regex
```php
<?php

include 'src/GeneratorEmail.php';

$GeneratorEmail = new GeneratorEmail();
$GeneratorEmail->getEmail(); // Get New Email Address

foreach ($GeneratorEmail->ReadEmail()->getData()['Data'] as $key => $value) {

        /**
         * Read Specific only works when Inbox have 2 email
         * if you read one message only use $GeneratorEmail->getEmail('user', 'domain')->ReadEmail()->getData()
         */
        print_r($GeneratorEmail->ReadSpecific($value['LongAddress'])->GetSpecific('/OTP : (.*)/m'));

        echo "\n";
    }
```

#### Example Code
[ example.php ](https://github.com/sandrocods/New-generator-email/blob/main/example.php "Example Code ") Use All Method in library
