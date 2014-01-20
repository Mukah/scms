<?php
require_once("class.dbobject.php");

class Post extends DBObject {
	public static $plural = "posts";
	public static $singular = "post";

	function __construct() {
		parent::__construct();
		parent::__belongs_to("User", "author_id", "author");
		parent::__has_many("PostCategory", "post_id", "categories");
		parent::__has_many("PostTag", "post_id", "tags");
	}
}

//$post = Post::find_by_id(1);
//$post = new Post();
//$post->set('title', 'Como cuidar de um macaco! 2');
//$post->set('author', 1);
//echo $post->get('title') . "<br />";
//echo $post->get('content');
//var_dump($post->save());
//$post->destroy();
//echo $post->get('title');
//var_dump($post->get('author'));
//echo $post->id();

//var_dump(Post::where('post_id = 1'));
//$post = Post::find_by_id(3);
//$posts = Post::all();

//foreach($posts as $post){
//echo $post->get('title') . "<br>";
//$post->set('title', 'Como cuidar de um macaco simpatico 2');
//$post->set_attributes(array('title' => 'Vai menina senta na pica'));
//echo $post->get('title');
//var_dump($post->save());
//}
?>
