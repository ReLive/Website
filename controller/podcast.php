<?php

	$podcast = Podcast::find_by_prefix($route->podcast);
	if (count($podcast) == 0){
		echo $twig->render('error.html', array(
			'error_title'		=> 'Ugh… Fehler 404!',
			'error_description'	=> 'Es gibt keinen Podcast mit dem Namen '.$route->podcast));
		die();
	}
if ($route->action == 'index'){
	// Render Podcast episode list
	echo $twig->render('podcast_index.html', array(
		"podcast" => $podcast,
		"episodes" => Episode::find('all', array('conditions' => array('podcast_id = ?', $podcast->id)))
		));
}
else if ($route->action == 'episode'){
	$episode = Episode::find_by_episode($route->id);
	if (count($episode) == 0){
		echo $twig->render('error.html', array(
			'error_title'		=> 'Ugh… Fehler 404!',
			'error_description'	=> 'Es gibt keine Episode '.$route->id));
		die();
	}
	echo $twig->render('podcast_episode.html', array(
		"podcast" => $podcast,
		"episode" => $episode,
		"episodes" => Episode::find('all', array('conditions' => array('podcast_id = ?', $podcast->id)))
		));
}
?>