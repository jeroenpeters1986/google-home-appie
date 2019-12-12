<?php
/**
 * Appie helper voor IFThisThenThat (IFFTT)
 *
 * Instructions:
 *   After installation, please call this file one time manually by doing url.nl/appie.php?boodschap=test
 *   This will create the cookiefile
 */


$config = [
    'ah' => [
        'username' => 'mijn_appie_account@gmail.com',
        'password' => 'B00dSch4pp3n!',
        'cookiefile' => __DIR__ . '/ah.cookie',
        'api' => [
            'login_url' => 'https://www.ah.nl/mijn/api/login',
            'login_payload' => '{"password":"%s","username":"%s"}',
            'add_to_cart_url' => 'https://www.ah.nl/service/rest/shoppinglists/0/items',
            'add_to_cart_payload' => '{"quantity":1,"type":"UNSPECIFIED","label":"PROCESSING_UNSPECIFIED","item":{"description":"%s"}}',
            'add_to_cart_referrer' => 'https://www.ah.nl/mijnlijst',
        ]
    ],
    'debug_log' => false,
    'curl_user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36'
];

$curl_shared_options = [
    CURLOPT_COOKIEJAR       => $config['ah']['cookiefile'],
    CURLOPT_COOKIESESSION   => true,
    CURLOPT_CUSTOMREQUEST   => 'POST',
    CURLOPT_HEADER          => false,
    CURLOPT_HTTPHEADER      => ['Content-Type: application/json'],
    CURLOPT_NOBODY          => false,
    CURLOPT_POST            => true,
    CURLOPT_RETURNTRANSFER  => true,
    CURLOPT_SSL_VERIFYHOST  => false,
    CURLOPT_SSL_VERIFYPEER  => false,
    CURLOPT_USERAGENT       => $config['curl_user_agent'],
];

$grocery = trim(urldecode($_GET['boodschap']));

/* Delete cookies */
if( ! empty( $_GET['reset'] ) )
{
    unlink($config['ah']['cookiefile']);
}

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

    $curl_login_options = [
        CURLOPT_COOKIE => "cookiename=0",
        CURLOPT_URL => $config['ah']['api']['login_url'],
        CURLOPT_REFERER => $config['ah']['api']['login_url'],
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_POSTFIELDS => sprintf($config['ah']['api']['login_payload'], $config['ah']['password'], $config['ah']['username'])
    ];

    /* Setup the cURL handler for the request */
    $ch = curl_init();
    curl_setopt_array($ch, $curl_shared_options + $curl_login_options);
    $execute_login = curl_exec($ch);
    log_msg("Login call result: " . var_export($execute_login, 1));

    /* We need to close the cURL handler here, this ensures that a cookiefile will be written */
    curl_close($ch);

    log_msg(file_get_contents($config['ah']['cookiefile']));
}
else
{
    log_msg("Cookiefile seemed OK, not logging in");
}


log_msg("Trying to add '" . $grocery . "' to the shopping list...");
$curl_cart_options = [
    CURLOPT_COOKIEFILE => $config['ah']['cookiefile'],
    CURLOPT_URL => $config['ah']['api']['add_to_cart_url'],
    CURLOPT_POSTFIELDS => sprintf($config['ah']['api']['add_to_cart_payload'], $grocery),
    CURLOPT_REFERER => $config['ah']['api']['add_to_cart_referrer'],
];

/* Add the item to the list */
$ch = curl_init();
curl_setopt_array($ch, $curl_shared_options + $curl_cart_options);

log_msg("Add to cart payload: " . var_export(sprintf($config['ah']['api']['add_to_cart_payload'], $_GET['boodschap']), 1));

$execute_add_to_cart = curl_exec($ch);
log_msg("Add to cart call result: " . var_export($execute_add_to_cart, 1));
curl_close($ch);

log_msg("Done, exiting\n\n\n");


function log_msg($msg)
{
    global $config;
    if ( $config['debug_log'] )
    {
        $logfile_handle = fopen("debug.log", "a");
        fwrite($logfile_handle, "[" . date("d-m-Y H:i:s") . "] " . $msg . PHP_EOL);
        fclose($logfile_handle);
    }
}