<?php
$snippets = array();

/* create the snippets objects */

// lighten
$snippets[0] = $modx->newObject('modSnippet');
$snippets[0]->set('id',1);
$snippets[0]->set('name','akismetCommentCheck');
$snippets[0]->set('description','Calls the Akismet API to check for comment spam. Works as FormIt hook or stand-alone. Requires Akismet API key.');
$snippets[0]->set('snippet', getSnippetContent($sources['snippets'] . 'akismetCommentCheck.snippet.php'));

$properties = include $sources['snippets'].'akismetCommentCheck.properties.inc.php';
$snippets[0]->setProperties($properties);
unset($properties);

return $snippets;