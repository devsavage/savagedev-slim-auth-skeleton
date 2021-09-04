# SavageDev Slim Auth Skeleton
This skeleton app structures a basic authentication system for a Slim 4 application.  

*This skeleton uses the SavageDev namespace. To change this, you will need to update all the php files in the app directory as well as the command, console and middleware stubs in app/SavageDev/Console.*

## Features  

#### Authentication 
	 - Login & Register
	 - Update profile details (username, email)
	 - Update password
    View "slim-auth-skeleton-v2.sql" to see database structure
#### Console Commands
	- Make controllers
	- Make middleware
	- Make console command
    Run "php craft" in the app's directory to view more information.

#### Styling
    - Use resources/assets folder for styling and javascript files
    Utilizes webpack to style your application (default: bootstrap)	

## Installation
 1. Clone this repo to your web root
 2. Open your console and run: "composer install"
 3. To add styling support: run "npm install" or "yarn install"
 4. Import the sql file into your database
 5. Edit the .env-example file, including database connection and rename it to .env (Change APP_ENV to "production" before deployment)
 7. Point to your app directory's public folder within your web configuration
 8. View website in your browser

## Help and Feedback
For any help and/or feedback, send me an email to the public e-mail shown on GitHub.
