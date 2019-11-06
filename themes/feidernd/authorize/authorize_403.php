<?php

/**
 * Show a 403 Forbidden page about not authorized to access an application.
 *
 * @package SimpleSAMLphp
 */

if (!array_key_exists('StateId', $_REQUEST)) {
	throw new SimpleSAML_Error_BadRequest('Missing required StateId query parameter.');
}
$state = SimpleSAML_Auth_State::loadState($_REQUEST['StateId'], 'authorize:Authorize');

$globalConfig = SimpleSAML_Configuration::getInstance();
$t = new SimpleSAML_XHTML_Template($globalConfig, 'authorize:authorize_403.php');
if (isset($state['Source']['auth'])) {
	$t->data['LogoutURL'] = SimpleSAML\Module::getModuleURL('core/authenticate.php', array('as' => $state['Source']['auth'])) . "&logout";
}
header('HTTP/1.0 403 Forbidden');
#$t->show();

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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">

<head>
	<title>Du saknar behörighet</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel='stylesheet' href="<?php echo SimpleSAML_Module::getModuleURL('scouterna-theme/feidernd.css'); ?>" type='text/css' />
</head>

<body class="login">

	<div id="logo">
		<img alt="logo" src="<?php echo SimpleSAML_Module::getModuleURL('scouterna-theme/logoscouterna.png') ?>" />
	</div>

	<div id="login">
		<h2>Du saknar behörighet</h2>
		<p>Du är inloggad men saknar behörighet för den här tjänsten.</p>
		<p>
			Kontakta
			<?php if (isset($foundContact)) : ?>
				<a href="<?= $foundContact['emailAddress'] ?>"><?= $foundContact['emailAddress'] ?></a>
			<?php elseif (isset($entity['OrganizationURL'])) : ?>
				administratören för
				<a href="<?= $entity['OrganizationURL'] ?>"><?= $entity['OrganizationURL'] ?></a>
			<?php else : ?>
				administratören för tjänsten du försöker komma åt
			<?php endif; ?>
			för mer information om detta fel.
		</p>
	</div>

</body>

</html>