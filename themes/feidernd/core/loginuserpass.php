<?php

$themeUrl = SimpleSAML_Module::getModuleURL('scouterna-theme');

// Translations (I guess t() translates and html-encode the strings)
$title = $this->t('{login:user_pass_header}');
$passwordLabel = $this->t('{login:password}');
$errorLabel = $this->t('{login:error_header}');
$loginButtonTitle = $this->t('{login:login_button}');

//<editor-fold desc="$usernameInputValueAttribute">
$usernameInputValueAttribute = '';
if (isset($this->data['username'])) {
    $usernameInputValueAttribute = 'value="' . htmlspecialchars($this->data['username'], ENT_QUOTES) . '"';
}
//</editor-fold>

//<editor-fold desc="$errorDiv">
$errorDiv = '';
if (!empty($this->data['errorcode'])) {
    $errorTitle = $this->t('{errors:title_' . $this->data['errorcode'] . '}');
    $errorMessage = $this->t('{errors:descr_' . $this->data['errorcode'] . '}');
    $errorDiv = <<<HTML
    <div id="error">
        <img src="{$this->data['baseurlpath']}resources/icons/experience/gtk-dialog-error.48x48.png" style="float: right; margin: 15px " alt="Error" />
        <h2>{$errorLabel}</h2>
        <p style="clear: both"><b>{$errorTitle}</b></p>
        <p>{$errorMessage}</p>
    </div>
    HTML;
}
//</editor-fold>

//<editor-fold desc="$linkUl">
$linkUl = '';
if (!empty($this->data['links'])) {
    $links = [];
    foreach ($this->data['links'] AS $l) {
        $linkUrl = htmlspecialchars($l['href'], ENT_QUOTES);
        $linkText = htmlspecialchars($this->t($l['text']), ENT_QUOTES);
        $links[] = <<<HTML
        <li><a href="{$linkUrl}">{$linkText}</a></li>
        HTML;
    }
    $linkUl = implode(PHP_EOL, $links);
    $linkUl = <<<HTML
    <ul class="links" style="margin-top: 2em">
        {$linkUl}
    </ul>
    HTML;
}
//</editor-fold>

//<editor-fold desc="$inputsHtml">
$inputsHtml = '';
if (!empty($this->data['stateparams'])) {
    $inputs = [];
    foreach ($this->data['stateparams'] as $rawName => $rawValue) {
        $name = htmlspecialchars($rawName, ENT_QUOTES);
        $value = htmlspecialchars($rawValue, ENT_QUOTES);
        $inputs[] = <<<HTML
        <input type="hidden" name="{$name}" value="{$value}" />
        HTML;
    }
    $inputsHtml = implode(PHP_EOL, $inputs);
}
//</editor-fold>

//<editor-fold desc="$languagebarDiv">
$languagebarDiv = '';
if (empty($_POST) && ($this->data['hideLanguageBar'] ?? false) !== true) {
    $languages = $this->getLanguageList();
    $langnames = [
        'no' => 'Bokmål',
        'nn' => 'Nynorsk',
        'se' => 'Sámegiella',
        'sam' => 'Åarjelh-saemien giele',
        'da' => 'Dansk',
        'en' => 'English',
        'de' => 'Deutsch',
        'sv' => 'Svenska',
        'fi' => 'Suomeksi',
        'es' => 'Español',
        'eu' => 'Euskara',
        'fr' => 'Français',
        'nl' => 'Nederlands',
        'lb' => 'Luxembourgish',
        'cs' => 'Czech',
        'sl' => 'Slovenščina', // Slovensk
        'hr' => 'Hrvatski', // Croatian
        'hu' => 'Magyar', // Hungarian
        'pl' => 'Język polski', // Polish
        'pt' => 'Português', // Portuguese
        'pt-br' => 'Português brasileiro', // Portuguese
        'tr' => 'Türkçe',
    ];

    $links = [];
    foreach ($languages as $lang => $current) {
        if ($current) {
            $links[] = $langnames[$lang];
            continue;
        }

        $rawUrl = SimpleSAML_Utilities::addURLparameter(SimpleSAML_Utilities::selfURL(), ['language' => $lang]);
        $url = htmlspecialchars($rawUrl, ENT_QUOTES);

        $links[] = <<<HTML
        <a href="{$url}">{$langnames[$lang]}</a>
        HTML;
    }
    $languagebarDiv = implode(' | ', $links);
    $languagebarDiv = <<<HTML
    <div id="languagebar">{$languagebarDiv}</div>
    HTML;
}
//</editor-fold>

echo <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
	<title>{$title}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel='stylesheet' href="{$themeUrl}/feidernd.css" type='text/css' />
	<!--[if IE]><style type="text/css">#login h1 a { margin-top: 35px; } #login #login_error { margin-bottom: 10px; }</style><![endif]--><!-- Curse you, IE! -->

	<script type="text/javascript">
		function focusit() {
			document.getElementById('username').focus();
		}
		window.onload = focusit;
	</script>
</head>
<body class="login">

<div id="logo">
   <img alt="logo" src="{$themeUrl}/logoscouterna.png" />
</div>

<div id="login">
	<form name="loginform" id="loginform" action="?" method="post">
		<h2>Inloggning</h2>
		<p>
			<label>Medlemsnummer<br />
			<input type="text" name="username" id="username" class="input" {$usernameInputValueAttribute} size="20" tabindex="10" /></label>
		</p>
		<p>
			<label>{$passwordLabel}<br />
			<input type="password" name="password" id="user_pass" class="input" value="" size="20" tabindex="20" /></label>
		</p>
		<p><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" /> Kom ihåg mig</label></p>
		<p class="submit">
			<input type="submit" name="wp-submit" id="wp-submit" value="{$loginButtonTitle} &raquo;" tabindex="100" />
		</p>
                <p><a href="https://www.scoutnet.se/request_password">Glömt lösenordet - klicka här.</a></p>

    {$errorDiv}
    {$linkUl}
    {$inputs}
	</form>
</div>

{$languagebarDiv}

</body>
</html>

HTML;
