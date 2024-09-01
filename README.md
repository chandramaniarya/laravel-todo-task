## Installation Steps
## 1. Clone the Repository
	First, clone the project repository to your local machine:
	git clone https://github.com/chandramaniarya/laravel-todo-task.git
	cd laravel-todo-task
## 2. Install Dependencies
	Use Composer to install PHP dependencies:

	composer install
## 3. Create the .env File
	
	Copy the example environment file and create your own:

	cp .env.example .env

## 4. Generate Application Key
	Generate a unique application key:

	php artisan key:generate

## 5. Configure the Environment Variables

	Open the .env file and configure the necessary environment variables, especially the database settings:

	DB_CONNECTION=mysql
	DB_HOST=127.0.0.1
	DB_PORT=3306
	DB_DATABASE=your_database_name
	DB_USERNAME=your_username
	DB_PASSWORD=your_password


## 6. Run Database Migrations
	
	Run the following command to create the required database tables:

	php artisan migrate

	If your project includes seed data, you can also run:

	php artisan db:seed

## 7. Serve the Application
	
	You can serve the application locally using Artisan:

	php artisan serve

