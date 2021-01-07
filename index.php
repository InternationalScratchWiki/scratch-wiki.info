<?php

$wikis = [
	// lang => wiki
	'en' => 'en',
	'ja' => 'ja',
	'nl' => 'nl',
	'de' => 'de',
	'id' => 'id',
	'ru' => 'ru',
	'hu' => 'hu',
	'fr' => 'fr',
	'it' => 'test',
	'es' => 'test',
	'pt' => 'test',
	'pl' => 'test',
	'tr' => 'test',
	'zh' => 'test',
	'ko' => 'test',
	'hi' => 'test',
	'he' => 'test',
	'sl' => 'test',
	'ar' => 'test',
	'la' => 'test',
	'uk' => 'test',
	'sq' => 'test',
	'ro' => 'test',
	'*' => 'test',
];

$langs = $_GET['lang'] ?: $_SERVER['HTTP_ACCEPT_LANGUAGE']; // get browser languages
$langs = explode(',', $langs); // split languages string
$langs = array_filter($langs, function($lang) { return strpos($lang, '*') !== 0; }); // cleanup string
$langs = array_map(function($lang) { return strtolower(substr($lang, 0, 2)); }, $langs); // more cleanup
$langs = array_values(array_unique($langs)); // remove duplicate languages
define('LANGUAGE', $langs[0] ?: 'en' ); // set page language or en as default

array_push($langs, '*'); // add default case
$langs = array_flip($langs); // get wiki instead of lang
$langs = array_intersect_key($langs, $wikis); // get supported wikis
$langs = array_keys($langs); // get wikis
define('WIKI', $wikis[$langs[0]]); // set wiki

$strings = json_decode(file_get_contents("i18n/" . LANGUAGE . ".json"), true);
if ($strings == null) {
	$strings = json_decode(file_get_contents("i18n/en.json"), true);
	echo '<!-- falling back to en.json -->';
}

define('PATH', $_SERVER['REQUEST_URI']);

?>
<!doctype html>
<!-- <?= $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?> -->
<html lang="<?= LANGUAGE ?>">
<head>
	<title><?= $strings['title'] ?></title>
	<meta name="description" content="<?= $strings['meta.description'] ?>" />
	<meta property="og:title" content="Scratch Wiki" />
	<meta property="og:description" content="<?= $strings['meta.description'] ?>" />
	<meta property="og:image" content="https://scratch.mit.edu/images/scratch-og.png" />
	<meta name="keywords" content="wiki, scratch, documentation" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="theme-color" content="#7953C4" />
	<link rel="stylesheet" href="assets/css/style.css" />
	<link rel="icon" href="favicon.ico" />
</head>
<body>
	<div id="cover">
    	<img src="assets/img/logo.png" alt="<?= $strings['cover.logoalt'] ?>">
		<h1><?= $strings['cover.title'] ?></h1>
		<p><?=sprintf($strings['cover.content'], sprintf('<a href="https://scratch.mit.edu">%s</a>', $strings['cover.scratch']))?></p>
	</div>
	<div id="wikis">
		<h1><?= $strings['wikis.title'] ?></h1>
		<p><?= $_REQUEST['lang'] ? $strings['wikis.content'] : $strings['wikis.content.nolang'] ?></p>
		<div>
			<div <?= WIKI == 'en' ? 'class="suggested"' : '' ?>>
				<a href="https://en.scratch-wiki.info<?= PATH ?>">
					<img src="assets/img/logos/en.png" alt="English Wiki">
					<div>English</div>
				</a>
			</div>
			<div <?= WIKI == 'de' ? 'class="suggested"' : '' ?>>
				<a href="https://de.scratch-wiki.info<?= PATH ?>">
					<img src="assets/img/logos/de.png" alt="Deutsch Wiki">
					<div>Deutsch</div>
				</a>
			</div>
			<div <?= WIKI == 'ru' ? 'class="suggested"' : '' ?>>
				<a href="https://ru.scratch-wiki.info<?= PATH ?>">
					<img src="assets/img/logos/ru.png" alt="Pусский Wiki">
					<div>Pусский</div>
				</a>
			</div>
			<div <?= WIKI == 'nl' ? 'class="suggested"' : '' ?>>
				<a href="https://nl.scratch-wiki.info<?= PATH ?>">
					<img src="assets/img/logos/nl.png" alt="Nederlands Wiki">
					<div>Nederlands</div>
				</a>
			</div>
			<div <?= WIKI == 'id' ? 'class="suggested"' : '' ?>>
				<a href="https://id.scratch-wiki.info<?= PATH ?>">
					<img src="assets/img/logos/id.png" alt="Bahasa Indonesia Wiki">
					<div>Bahasa Indonesia</div>
				</a>
			</div>
			<div <?= WIKI == 'ja' ? 'class="suggested"' : '' ?>>
				<a href="https://ja.scratch-wiki.info<?= PATH ?>">
					<img src="assets/img/logos/ja.png" alt="日本語 Wiki">
					<div>日本語</div>
				</a>
			</div>
			<div <?= WIKI == 'hu' ? 'class="suggested"' : '' ?>>
				<a href="https://hu.scratch-wiki.info<?= PATH ?>">
					<img src="assets/img/logos/hu.png" alt="Magyar Wiki">
					<div>Magyar</div>
				</a>
			</div>
			<div <?= WIKI == 'fr' ? 'class="suggested"' : '' ?>>
				<a href="https://fr.scratch-wiki.info<?= PATH ?>">
					<img src="assets/img/logos/fr.png" alt="Français Wiki">
					<div>Français</div>
				</a>
			</div>
			<div <?= WIKI == 'test' ? 'class="suggested"' : '' ?>>
				<a href="https://test.scratch-wiki.info<?= PATH ?>">
					<img src="assets/img/logos/test.png" alt="Test Wiki">
					<div>Test</div>
				</a>
			</div>
		</div>
	</div>
</body>
</html>
