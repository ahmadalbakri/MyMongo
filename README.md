# MyMongo
Works with http://github.com/j7mbo/twitter-api-php<br />
Included - helper and MyMongo classes<br />
<br />
How it works.<br />
1) Plugin scrap twitter in JSON format, pass it to MyMongo class.<br />
2) Upon first execution, MyMongo class will store all the data passed to Mongo DB<br />
3) Upon next execution, will iterate the JSON and find if the tweet ID already exist in the DB<br />
4) If no record, store into DB. Else, pass.<br />
5) Helper class is to pretty print and to linkify the tweets.
