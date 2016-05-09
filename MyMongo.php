<?php
require_once('helper.php');

class MyMongo
{
  private $dbName;
  private $collectionName;

  public function __construct(array $settings){
    $this->dbName = $settings['dbName'];
    $this->collectionName = $settings['collectionName'];
  }

  private function createConnection(){
    $dbName = $this -> dbName;
    $collectionName = $this -> collectionName;

    $m = new MongoClient();
    $db = $m->$dbName;

    $collection = $db->$collectionName;
    return $collection;
  }

  private function getMongoObjectID($collection){
    $cursor = $collection -> find();
    foreach($cursor as $doc){
      $objectID = $doc['_id'];
      return $objectID;
    }
  }

  public function storeMongoDB($json){
    $collection = $this -> createConnection();
    $objectID = $this -> getMongoObjectID($collection);
    $obj = json_decode($json);

    $count = $collection->count();

    if(empty($count)){
      $collection -> insert($obj);
      echo 'Inital record inserted';
    }else{

      foreach ($obj as $innerArray) {
          if (is_array($innerArray)){
              foreach ($innerArray as $tweet) {

                  $tweetID = $tweet -> id;
                  $query = $this -> isExist($objectID, $tweetID, $collection);
                  $newRecord = $this -> checkDifference($tweetID, $query);

                  if (isset($query)){
                    echo 'Record already exist - '.$query.'<br>';
                  }else{
                    foreach ($newRecord as $newTweetID) {
                      echo 'New record - '.$newTweetID.'<br>';

                      $newArray =  (array) $tweet;
                      $result = $this->searchArrayByID($newArray, 'id', $newTweetID);
                      $this->updateRecord($result, $collection);
                    }
                  }
              }
          }
      }
  }}

  private function searchArrayByID($array, $key, $value){
      $results = array();

      if (is_array($array)) {
          if (isset($array[$key]) && $array[$key] == $value) {
              $results[] = $array;
          }

          foreach ($array as $subarray) {
              $results = array_merge($results, $this->searchArrayByID($subarray, $key, $value));
          }
      }

      return $results;
  }

  private function checkDifference($tweetID, $query){

    $tweetIDArray = array();
    array_push($tweetIDArray, $tweetID);

    $queryArray = array();
    array_push($queryArray, $query);

    $result = array_diff($tweetIDArray, $queryArray);
    return $result;
  }

  private function isExist($objectID, $tweetID, $collection){
    $cursor = $collection->find(
        array('_id' => new MongoId($objectID)),
        array(
            'statuses' => array(
                '$elemMatch' => array(
                    'id' => $tweetID
                )
            )
        )
    );

    foreach ($cursor as $value) {
      if (array_key_exists('statuses', $value)) {
        foreach ($value['statuses'] as $result) {
          $id = $result['id'];
          return $id;
        }
      }
    }
  }

  private function updateRecord($json, $collection){
    $objectID = $this -> getMongoObjectID($collection);

    $collection->update(
      array('_id' => $objectID),
      array('$pushAll' => array('statuses' => $json))
    );
  }

}
?>
