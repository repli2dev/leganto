<?php
echo ' Client IP: ';
if ( isset($_SERVER["REMOTE_ADDR"]) )    {
    echo '' . $_SERVER["REMOTE_ADDR"] . ' ';
}
echo $_SERVER['HTTP_USER_AGENT'] . "\n\n";

$browser = get_browser(null, true);
print_r($browser);
phpinfo();
