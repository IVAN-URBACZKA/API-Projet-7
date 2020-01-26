# api-p7
Api BileMo for my Project 7 Openclassrooms

Setup
Download Composer dependencies

Make sure you have Composer installed and then run:

composer install
Configure the the .env File

First, you should have an .env file. If you don't, copy .env.dist to create it.

Next, look at the configuration and make any adjustments you need - specifically DATABASE_URL.

Setup the Database

Again, make sure .env is setup for your computer. Then, create the database & tables!

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
If you get an error that the database exists, that should be ok. But if you have problems, completely drop the database (doctrine:database:drop --force) and try again.

DOC

click on the green button "Authorize" :

api1

Enter:

Bearer %yourtoken%
api2

Now, you can see 6 operations:

GET /api/products

GET /api/products/{id}

GET /api/users

GET /api/users/{id}

POST /api/users

DELETE /api/users/{id}

You can also use Postman or Insomnia !

Postman/Insomnia

In both cases, start with a POST {yourdomain}/api/login_check with your credentials to get your token.

Don't forget in the header : (KEY) Content-Type, (VALUE) application/json.

In the body, select "raw" and JSON(application/json) and write your credentials.

Then, Do your requests using the Bearer Authorization and paste your token.

HTTP
Request

Request Line (HTTP Method, URI, protocol version).
Headers.
Body.
Response

Status Line (protocol version, code status, code status text).
Headers.
Body.
Richardson Maturity Model
Level1: Resources

example: /users or /users/{id}

Level2: Verbs

example: GET /users/{id}

Level3: Hypermedia controls

The point of hypermedia controls is that they tell us what we can do next.

Any Questions ?

Feel free to contact me !
