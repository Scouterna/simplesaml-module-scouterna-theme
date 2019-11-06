<?php

$themeUrl = SimpleSAML_Module::getModuleURL('scouterna-theme');

echo <<<HTML
<!DOCTYPE html>
<html lang="sv">
    <head>
        <title>Utloggad</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel='stylesheet' href="{$themeUrl}/feidernd.css" type='text/css' />
    </head>
    <body class="login">
        <div id="logo">
           <img alt="logo" src="{$themeUrl}/logoscouterna.png" />
        </div>
        <div id="login">
            <h2>DU ÄR NU UTLOGGAD</h2>
            <p>Tack och välkommen åter!</p>
        </div>
    </body>
</html>

HTML;
