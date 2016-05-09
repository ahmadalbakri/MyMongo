<?php

class Helper{

    // To print out nicely
    public function printr($data) {
       echo "<pre>";
          print_r($data);
       echo "</pre>";
    }

    // To convert Twitter URL string to real links
    public function linkify_tweet($tweet) {

      //Convert urls to <a> links
      $tweet = preg_replace("/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/", "<a target=\"_blank\" href=\"$1\">$1</a>", $tweet);

      //Convert hashtags to twitter searches in <a> links
      $tweet = preg_replace("/#([A-Za-z0-9\/\.]*)/", "<a target=\"_new\" href=\"http://twitter.com/search?q=$1\">#$1</a>", $tweet);

      //Convert attags to twitter profiles in <a> links
      $tweet = preg_replace("/@([A-Za-z0-9\/\.]*)/", "<a href=\"http://www.twitter.com/$1\">@$1</a>", $tweet);

      return $tweet;
    }
}

?>
