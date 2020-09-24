<?php
$codes = ['en', 'ja', 'jp', 'nl', 'de', 'dach', 'id', 'indo', 'ru', 'hu', 'fr', 'test'];
if (in_array($_REQUEST['hard_lang'], $codes)) {
	header('HTTP/1.1 301 Moved Permanently');
	header(
		'Location: '
		. 'https://'
		. $_REQUEST['hard_lang']
		. '.scratch-wiki.info'
		. $_SERVER['REQUEST_URI']
	);
	die();
}
function preferred_language($available, $accept) {
	$default_language = 'test';
	$available = array_flip($available);
	$langs = [];
	preg_match_all('~([\w-]+)(?:[^,\d]+([\d.]+))?~', strtolower($accept), $matches, PREG_SET_ORDER);
	foreach($matches as $match) {
		list($a, $b) = explode('-', $match[1]) + array('', '');
		$value = isset($match[2]) ? (float) $match[2] : 1.0;
		if(isset($available[$match[1]])) {
			$langs[$match[1]] = $value;
			continue;
		}
		if(isset($available[$a])) {
			$langs[$a] = $value - 0.1;
		}
	}
	if($langs) {
		arsort($langs);
		return key($langs); // We don't need the whole array of choices since we have a match
	} else {
		return $default_language;
	}
}
if (in_array($_REQUEST['lang'], $codes)) {
	define('LANGUAGE', $_REQUEST['lang']);
} else {
	define('LANGUAGE', preferred_language($codes, $_SERVER['HTTP_ACCEPT_LANGUAGE']));
}
$strings = json_decode(file_get_contents("i18n/" . LANGUAGE . ".json"), true);
if ($strings == null) {
	$strings = json_decode(file_get_contents("i18n/en.json"), true);
	echo '<!-- falling back to en.json -->';
}
define('PATH', $_SERVER['REQUEST_URI']);
?>
<!doctype html><?='<!-- ' . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . ' -->'?>
<html lang="<?=LANGUAGE?>">
<head>
	<title><?=$strings['title']?></title>
	<meta name="description" content="<?=$strings['meta.description']?>" />
	<meta name="og:title" content="Scratch Wiki" />
	<meta name="og:description" content="<?=$strings['meta.description']?>" />
	<meta property="og:image" content="https://scratch.mit.edu/images/scratch-og.png" />
	<meta name="keywords" content="wiki, scratch, documentation" />
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
  .<?=LANGUAGE?> {
	border: 2.5px dashed #f80;
	border-radius: 5px;
  }
  </style>
</head>
<body>
	<div id="cover">
    <img src="assets/img/logo.png" alt="<?=$strings['cover.logoalt']?>">
		<h1><?=$strings['cover.title']?></h1>
		<p><?=$strings['cover.content']?></p>
	</div>
	<div id="wikis">
		<h1><?=$strings['wikis.title']?></h1>
		<p><?=$_REQUEST['lang'] ? $strings['wikis.content'] : $strings['wikis.content.nolang']?></p>
		<br/>
<table align="center"><tr>
	<th class="test" colspan="4"><img src="assets/img/logos/test.png"><a href="https://test.scratch-wiki.info<?=PATH?>">Test</a></th>
</tr><tr>
	<td class="en"><img src="assets/img/logos/en.png"><a href="https://en.scratch-wiki.info<?=PATH?>">English</a></td>
	<td class="dach de"><img src="assets/img/logos/de.png"><a href="https://de.scratch-wiki.info<?=PATH?>">Deutsch</a></td>
	<td class="ru"><img src="assets/img/logos/ru.png"><a href="https://ru.scratch-wiki.info<?=PATH?>">Pусский</a></td>
	<td class="nl"><img src="assets/img/logos/nl.png"><a href="https://nl.scratch-wiki.info<?=PATH?>">Nederlands</a></td>
</tr><tr>
	<td class="id indo"><img src="assets/img/logos/id.png"><a href="https://id.scratch-wiki.info<?=PATH?>">Bahasa Indonesia</a></td>
	<td class="jp ja"><img src="assets/img/logos/ja.png"><a href="https://ja.scratch-wiki.info<?=PATH?>">日本語</a></td>
	<td class="hu"><img src="assets/img/logos/hu.png"><a href="https://hu.scratch-wiki.info<?=PATH?>">Magyar</a></td>
	<td class="fr"><img src="assets/img/logos/fr.png"><a href="https://fr.scratch-wiki.info<?=PATH?>">Français</a></td>
</tr></table>
	</div>
</body>
</html>
