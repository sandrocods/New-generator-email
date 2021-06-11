<?php

include 'src/GeneratorEmail.php';

$GeneratorEmail = new GeneratorEmail();

/**
 * Generate New Random Email
 */

 print_r($GeneratorEmail->getEmail());

/**
 * Generate New Email with specific Name & Domain
 */

print_r($GeneratorEmail->getEmail('mdiulyano.sinihun', 'defvit.com'));

/**
 * Check Status Email
 */
print_r($GeneratorEmail->CheckValidation());

/**
 * Get Data Inbox Convert to array
 */
print_r($GeneratorEmail->ReadEmail()->getData());

/**
 * Regex String in body message , Only Works In 1 Messages
 * Return All Regex by your pattren
 */

print_r($GeneratorEmail->ReadEmail()->GetSpecific('/OTP : (.*)/m'));


/**
 * Delete All Email in Inbox
 */

$GeneratorEmail->DeleteAll($GeneratorEmail->ReadEmail()->getData()['Dell_key']);

/**
 * Read Specific only works when Inbox have 2 email
 * if you read one message only use $GeneratorEmail->getEmail('user', 'domain')->ReadEmail()->getData()
 */

if ($GeneratorEmail->ReadEmail()->getData()['Count_message'] > 1) {

    /**
     * Read Body Messages by specific Message
     */

    foreach ($GeneratorEmail->ReadEmail()->getData()['Data'] as $key => $value) {

        print_r($GeneratorEmail->ReadSpecific($value['LongAddress']));

        /**
         * Read Specific only works when Inbox have 2 email
         * if you read one message only use $GeneratorEmail->getEmail('user', 'domain')->ReadEmail()->getData()
         */
        print_r($GeneratorEmail->ReadSpecific($value['LongAddress'])->GetSpecific('/OTP : (.*)/m'));

        /**
         * Delete Message by specific Message
         */
        print_r($GeneratorEmail->DeleteMessage($GeneratorEmail->ReadSpecific($value['LongAddress'])->getData()['Dell_key'], $value['LongAddress']));

        echo "\n";
    }

} else {

    print_r($GeneratorEmail->ReadEmail()->getData());

}
