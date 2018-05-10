# PlaceToPay Develop Engineer Test

This app is developed with Laravel 5.6 for backend and Vue.Js to frontend.

## Instalation

To install and run, you must have installed NPM follow the nexts steps:

1. Clone this repo https://github.com/daniemc/p2ptest.git
2. cd p2ptest
3. Copy .env file: **cp .env.example .env**
4. Run: **composer install**
5. Run: **php artisan key:generate**
6. Run: **php artisan jwt:secret**
7. Edit **.env** file:
- Add WS credentials 
- Add **callback** route to checkout (root URL proyect and add callback route at the end)
- Add **time configurations** to schedule validations, if not asigned the default values will be **7** when user doesn't return to callback and **12** when transaction still pending. **That time is in mins**
8. Run: **php artisan migrate**
9. Run: **npm install**
10. run: **npm run production**
11. In order to run queued jobs, you must run **php artisan queue:work** and let it active




