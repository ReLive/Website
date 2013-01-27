<?php
// Main controller

if ($route->action == 'index'){
	// Render and display the index page
	echo $twig->render('index.html', array(
		"podcasts" => Podcast::find('all'),
		"episodes" => Episode::find('all', array('select' => 'name, episode, podcast_id'))
		));
}
?>