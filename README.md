# cinema-booking
A docker containerised demo web application booking performances, for a specified film, at a specified cinema, at
a specified show time.

# Tech stack
PHP
Laravel
Jetstream
Mysql
Tailwind
Inertia
Vue.js
Docker

# Caveats:
● Users should be able to register and log in.
● Users should be logged in to complete a booking, but can view and select
whilst unauthenticated.
● Users should be given a unique booking reference number to use as a
redemption method. ( No need to mail it, displaying it will be fine )
● Users should be able to view their booking specifics after having booked.
● Users should be able to cancel a booking up until one hour before the show
starts.
● Cinema theaters should have a maximum number of 30 seats.
● When booking, a user need only choose a cinema, a film, a show time, and the
number of tickets. ( Use whichever display method and process / flow you like
best, simple drop downs will do as well )

# Assumptions:
● There are only two cinema locations, each has two theatres, with two films
currently showing.
● Users won’t need to pay.