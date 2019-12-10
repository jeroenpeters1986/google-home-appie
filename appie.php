<?php
/**
 * Appie helper voor IFThisThenThat (IFFTT)
 */

/* Uncomment the lines within this function to create a call log */
function log_msg($msg)
{
    // $logfile_handle = fopen("debug.log", "a");
    // fwrite($logfile_handle, "[" . date("d-m-Y H:i:s") . "] " . $msg . PHP_EOL);
    // fclose($logfile_handle);
}

$config = [
    'ah' => [
        'username' => 'mijn_appie_account@gmail.com',
        'password' => 'B00dSch4pp3n!',
        'cookiefile' => __DIR__ . '/ah.cookie.txt',
        'api' => [
            'login_url' => 'https://www.ah.nl/mijn/api/login',
            'login_payload' => '{"password":"%s","username":"%s"}',
            'add_to_cart_url' => 'https://www.ah.nl/service/rest/shoppinglists/0/items',
            'add_to_cart_payload' => '{"quantity":1,"type":"UNSPECIFIED","label":"PROCESSING_UNSPECIFIED","item":{"description":"%s"}}',
            'add_to_cart_referrer' => 'https://www.ah.nl/mijnlijst',
        ]
    ],
    'curl_user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36'
];
$grocery = trim(urldecode($_GET['boodschap']));


$valid_cookiefile = false;
log_msg("Checking cookiefile..");
if ( file_exists($config['ah']['cookiefile']))
{
    log_msg(" - Cookiefile exists!");
    $cookie_contents = file_get_contents($config['ah']['cookiefile']);
    if ( ! empty( $cookie_contents ) )
    {
        $valid_cookiefile = true;
        log_msg("  - cookiefile has contents!");
    }
    else
    {
        log_msg("  - it's empty, deleting!");
        unlink($config['ah']['cookiefile']);
    }
}

if ( ! $valid_cookiefile )
{
    log_msg("Logging in because there was no cookie!");

    /* Setup the cURL handler for the request */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, $config['curl_user_agent']);
    curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $config['ah']['cookiefile']);
    curl_setopt($ch, CURLOPT_COOKIE, "cookiename=0");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_NOBODY, false);
    curl_setopt($ch, CURLOPT_URL, $config['ah']['api']['login_url']);
    curl_setopt($ch, CURLOPT_REFERER, $config['ah']['api']['login_url']);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, sprintf($config['ah']['api']['login_payload'], $config['ah']['password'], $config['ah']['username']));

    $execute_login = curl_exec($ch);
    log_msg("Login call result: " . var_export($execute_login, 1));
    curl_close($ch);

    log_msg(file_get_contents($config['ah']['cookiefile']));
}
else
{
    log_msg("Cookiefile seemed OK, not logging in");
}



log_msg("Trying to add '" . $grocery . "' to the shopping list...");

/* Add the item to the list */
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_USERAGENT, $config['curl_user_agent']);
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $config['ah']['cookiefile']);
curl_setopt($ch, CURLOPT_COOKIEFILE, $config['ah']['cookiefile']);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_NOBODY, false);
curl_setopt($ch, CURLOPT_URL, $config['ah']['api']['add_to_cart_url']);
curl_setopt($ch, CURLOPT_POSTFIELDS, sprintf($config['ah']['api']['add_to_cart_payload'], $grocery));
curl_setopt($ch, CURLOPT_REFERER, $config['ah']['api']['add_to_cart_referrer']);

log_msg("Add to cart payload: " . var_export(sprintf($config['ah']['api']['add_to_cart_payload'], $_GET['boodschap']), 1));

$execute_add_to_cart = curl_exec($ch);
log_msg("Add to cart call result: " . var_export($execute_add_to_cart, 1));
curl_close($ch);

log_msg("Done, exiting\n\n\n");