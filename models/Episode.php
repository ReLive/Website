<?php
class Episode extends ActiveRecord\Model
{
	static $table_name = 're_episode';
	static $belongs_to = array(
		array('podcast')
		);
}
?>