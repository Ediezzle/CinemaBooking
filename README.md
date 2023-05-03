# cinema-booking
A docker containerised demo web application booking performances, for a specified film, at a specified cinema, at
a specified show time.

# Tech stack
PHP
Laravel Sail
Jetstream
Mysql
Tailwind
Inertia
Vue.js
Docker

# Caveats:
Users should be able to register and log in.
Users should be logged in to complete a booking, but can view and select
whilst unauthenticated.
Users should be given a unique booking reference number to use as a
redemption method. ( No need to mail it, displaying it will be fine )
Users should be able to view their booking specifics after having booked.
Users should be able to cancel a booking up until one hour before the show
starts.
Cinema theaters should have a maximum number of 30 seats.
When booking, a user need only choose a cinema, a film, a show time, and the
number of tickets.

# Assumptions:
There are only two cinema locations, each has two theatres, with two films
currently showing.
Users won’t need to pay.
No admin panel needed for this proof of concept so most of the stuff is done through database seeding and a console command. In a real life scenarion there would be need for one where priviledged users can upload content such as images and film details.
There is no booking cutoff time for as long as the film hasn't started.
Users can book the same schedule more than once because they could be booking for others.

# Set up and running the application
install Docker Compose (https://docs.docker.com/desktop/install/linux-install/)
Clone project 
Cd into project root directory
Create a new file under the name .env and copy the contents of the .env.example file into the newly created .env file
Execute command "vendor/bin/sail artisan up -d"
Execute command "vendor/bin/sail artisan storage:link"
Execute command "vendor/bin/sail npm install"
Execute command "vendor/bin/sail npm build"
Execute command "vendor/bin/sail artisan migrate --seed"
Execute command "vendor/bin/sail artisan generate:schedules"

Visit the application on http://localhost:8005 in your browser
If the port is being used on your machine you may change the value of the APP_PORT
in your .env file
Also if PORT 3307 is in use on your machine you may change the value of the FORWARD_DB_PORT in your .env file

at any point you may access your database via shell by running sail mysql -u sail
at any point if you want to generate random schedules for all theatres run the command sail artisan generate:schedules. It will only generate up to a maximum of 2 films per theatre
register an account and use that to authenticate so as to book movies and do more

