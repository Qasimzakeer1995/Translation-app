Translation API Service

A robust, scalable API service designed for managing translations across multiple locales and for use with frontend applications. The API allows users to create, view, and search translations by key, tag, or content. It also supports exporting translations as JSON

API Endpoints
Auth Routes

POST /login: User login.

POST /register: User registration.

Translation Routes (Requires Authentication)

POST /translations: Add a new translation.

GET /translations/search: Search translations by tags, keys, or content.

GET /translations/export: Export translations as a JSON file.


Setup Instructions

Clone this repository:

git clone https://github.com/Qasimzakeer1995/Translation-app.git
cd into project

Build and start the Docker containers:

From the root of your project, run:

docker-compose up -d --build


Set up your .env file:

cp .env.example .env


Run the migrations:

To run the database migrations, execute the following command inside the Docker container:

docker-compose exec app php artisan migrate

Access the application:

After the containers are up and running, your API will be available at http://localhost:8000/ (or the host you configured). You can make API requests to the relevant endpoints.

For DB
Visit http://localhost:8080/
user name = root
password = secret