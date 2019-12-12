<?php

/**
 * Show a 403 Forbidden page about not authorized to access an application.
 *
 * @package SimpleSAMLphp
 */

header('HTTP/1.0 403 Forbidden');

if (!array_key_exists('StateId', $_REQUEST)) {
    throw new SimpleSAML_Error_BadRequest('Missing required StateId query parameter.');
}

$state = SimpleSAML_Auth_State::loadState($_REQUEST['StateId'], 'authorize:Authorize');

$globalConfig = SimpleSAML_Configuration::getInstance();
$template = new SimpleSAML_XHTML_Template($globalConfig, 'authorize:authorize_403.php');
if (isset($state['Source']['auth'])) {
    $authUrl = SimpleSAML\Module::getModuleURL(
        'core/authenticate.php',
        ['as' => $state['Source']['auth']]
    );
    $template->data['LogoutURL'] = "{$authUrl}&logout";
}

//<editor-fold desc="$contactParagraph">
if (isset($state['SPMetadata'])) {
    $entity = $state['SPMetadata'];
} elseif (isset($state['Destination'])) {
    $entity = $state['Destination'];
} else {
    $entity = [];
}

$foundContact = null;
if (isset($entity['contacts'])) {
    foreach ($entity['contacts'] as $contact) {
        if (in_array($contact['contactType'], ['technical', 'support', 'administrative'])) {
            $foundContact = $contact;
            break;
        }
    }
}

$contactParagraph = '<p>Kontakta administratören för tjänsten du försöker komma åt för mer information om detta fel.</p>';
if (isset($foundContact)) {
    $contactParagraph = <<<HTML
    <p>
        Kontakta
        <a href="mailto:{$foundContact['emailAddress']}">{$foundContact['emailAddress']}</a>
        för mer information om detta fel.
    </p>
    HTML;
} elseif (isset($entity['OrganizationURL'])) {
    $contactParagraph = <<<HTML
    <p>
        Kontakta administratören för
        <a href="{$entity['OrganizationURL']}">{$entity['OrganizationURL']}</a>
        för mer information om detta fel.
    </p>
    HTML;
}
//</editor-fold>

$themeUrl = SimpleSAML_Module::getModuleURL('scouterna-theme');

echo <<<HTML
<!DOCTYPE html>
<html lang="sv">
    <head>
        <title>Du saknar behörighet</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel='stylesheet' href="{$themeUrl}/feidernd.css" type='text/css' />
    </head>
    <body class="login">
        <div id="logo">
            <img alt="logo" src="{$themeUrl}/logoscouterna.png" />
        </div>
        <div id="login">
            <h2>Du saknar behörighet</h2>
            <p>Du är inloggad men saknar behörighet för den här tjänsten.</p>
            {$contactParagraph}
        </div>
    </body>
</html>
HTML;
