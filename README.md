
First clone this repository, install the dependencies, and setup your .env file.

  git clone https://github.com/harshmore785/EventTicketing.git
  composer install
  cp .env.example .env
  php artisan key:generate
Then create the necessary database and run the initial migrations and seeders.

  php artisan migrate
  php artisan db:seed

  or

  php artisan migrate --seed
Now after run the server by using command

  php artisan serve
Go to generated local server fill the username and password to login or register page and use the system

Superadmin 

  Username : superadmin@gmail.com
  Username : 12345678

  Orgnizer

  Username : organizer@gmail.com
  Username : 12345678

  Attendee

  Username : attendee@gmail.com
  Username : 12345678