# REST API
## Getting started
This is an example to showcase the creation of a basic REST API using the Zend Framework 2. To install, follow these steps:

1. Clone the git repository in your PHP server.
2. Install [Composer](https://getcomposer.org/) and make sure your PHP server has the sqlite extension loaded.
3. Go to the application's root and execute `php composer.phar install`.
4. Copy the SQLite database `/module/RestApi/data/db/restapi.clean.db` to `restapi.db` in the same folder. Make sure both the database and the containing folder have write permission for the web server.
5. Install [phpunit](https://phpunit.de/ if you want to run the automated tests). 

## Playing around
A demo interface has been provided to test the REST API. Open the application's root path in your web browser and feel free to try the different options available.

## Some ideas & concerns
1. This is my very first project with the Zend Framework; I paid attention to follow the recommended best practices, but I have still a lot to learn!
1. I couldn't make the framework load a `global|local.php` file in the `/module/RestApi/config` folder; I searched on the internet for a while but did not found the issue.
1. Not implemented HTTP verbs will produce a 405 error code.
1. The authentication protocol I implemented is very simplistic: the Token header as it is used by the [Ruby on Rails framework](http://api.rubyonrails.org/classes/ActionController/HttpAuthentication/Token.html).
1. The POST return format follows the Ember.js style guides, by putting the data under `customers` or `customer`; this allows for additional metadata information without interferring with the actual data.
1. The DELETE action returns a `204 No Content` response, since no information is required from this action; search, however, still returns a `200` code with an empty `customers` array, to allow for further metadata to be returned too.
1. Special attention has been paid to returning the right HTTP codes, like 200, 204, 400, 404, depending on the case. 
1. To validate the POST and PUT input when creating and updating customers, I had to make the data go through a Form class so that it would get filtered and validated. This feels rather cumbersome to me, and although I didn't find a reason to do it this way, I kept it since it is the recommended method according to the Zend documentation.
1. Models have a complete set of unit tests to showcase the feature; to run them, go to `/module/RestApi/test` and type `phpunit`. Behavior tests are missing for controllers - I plan to use [Behat](https://github.com/Behat/Behat), which has a plugin to connect to Zend [developed by the community](https://github.com/mvlabs/zf2behat-extension).
1. The demo interface to demonstrate the CRUD functions is based on a skeleton application provided by Zend; I did not clean all the code, just modified what was needed to get it running.
1. I normally would use Sass or Less, but in this case the CSS code was pretty simple so... keeping it simple I am!
1. Finally, the JSON responses contains just data; some metadata would be useful, like `count`, `page`, `pageSize` for collections, and `_links` for resources (like in the [HAL Model](http://stateless.co/hal_specification.html))
