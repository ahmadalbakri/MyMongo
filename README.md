# MyMongo
Works with http://github.com/j7mbo/twitter-api-php
helper and MyMongo classes

How it works.
1) Plugin scrap twitter in JSON format, pass it to MyMongo class.
2) Upon first execution, MyMongo class will store all the data passed to Mongo DB
3) Upon next execution, will iterate the JSON and find if the tweet ID already exist in the DB
4) If no record, store into DB. Else, pass.
