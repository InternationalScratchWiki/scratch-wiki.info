<?php
require_once 'wikis.php';

$langs = isset($_GET['lang']) ? $_GET['lang'] : $_SERVER['HTTP_ACCEPT_LANGUAGE']; // get browser languages
$langs = explode(',', $langs); // split languages string
$langs = array_filter($langs, function($lang) { return strpos($lang, '*') !== 0; }); // remove wildcard
$langs = array_map(function($lang) { return strtolower(substr($lang, 0, 2)); }, $langs); // cleanup languages
$langs = array_values(array_unique($langs)); // remove duplicate languages
define('LANGUAGE', $langs[0] ?: 'en' ); // set page language or 'en' as default

array_push($langs, '*'); // add default language
$langs = array_reduce($langs, function($previous, $lang) use ($wikis) { // get supported wikis
	$fil = array_filter($wikis, function($wiki) use ($lang) { // find wikis using the language
		return in_array($lang, $wiki['languages']);
	});
	return array_merge_recursive($previous, $fil);
}, []);
$langs = array_keys($langs); // get wikis
define('WIKI', $langs[0]); // taking first wiki and setting wiki

$strings = json_decode(file_get_contents("i18n/" . LANGUAGE . ".json"), true);
if ($strings == null) {
	$strings = json_decode(file_get_contents("i18n/en.json"), true);
	echo '<!-- falling back to en.json -->';
}

$PATH = $_SERVER['REQUEST_URI'];

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
	<link rel="stylesheet" href="/assets/css/style.css" />
	<link rel="icon" href="favicon.ico" />
</head>
<body>
	<header>
    	<img src="/assets/img/logo.png" alt="<?= $strings['cover.logoalt'] ?>">
		<h1><?= $strings['cover.title'] ?></h1>
		<p><?=sprintf($strings['cover.content'], sprintf('<a href="https://scratch.mit.edu">%s</a>', $strings['cover.scratch']))?></p>
	</header>
	<main>
		<h2><?= $strings['wikis.title'] ?></h2>
		<p><?= isset($_REQUEST['lang']) ? $strings['wikis.content'] : $strings['wikis.content.nolang'] ?></p>
		<ul>
<?php
foreach($wikis as $wiki => $props) {
$suggested = WIKI === $wiki ? ' class="suggested"' : '';
$name = $props['name'];
echo <<<EOT
			<li$suggested>
				<a href="https://$wiki.scratch-wiki.info$PATH">
					<img src="/assets/img/logos/$wiki.png" alt="$wiki-wiki-logo">
					<div>$name</div>
				</a>
			</li>

EOT;
}
?>
		</ul>
	</main>
</body>
</html>
