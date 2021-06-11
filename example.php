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

/* print_r($GeneratorEmail->getEmail('hraed.amale', 'boranora.com')); */

/**
 * Check Status Email
 */
/* print_r($GeneratorEmail->CheckValidation()); */

sleep(30);

/**
 * Read All Messages in Inbox
 */
print_r($GeneratorEmail->ReadEmail());

die();
/**
 * Delete All Email in Inbox
 */
print_r($GeneratorEmail->DeleteAll($GeneratorEmail->ReadEmail()['Dell_key']));

/**
 * Read Body Messages by specific Message
 */

foreach ($GeneratorEmail->ReadEmail()['Data'] as $key => $value) {
    print_r($GeneratorEmail->ReadSpecific($value['LongAddress']));

    /**
     * Delete Message by specific Message
     */
    print_r($GeneratorEmail->DeleteMessage($GeneratorEmail->ReadSpecific($value['LongAddress'])['Dell_key'], $value['LongAddress']));

    echo "\n";
}
