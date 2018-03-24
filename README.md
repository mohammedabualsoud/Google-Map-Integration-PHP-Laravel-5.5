# Google-Map-Integration-PHP-Laravel-5.5
Google Map Integration PHP-Laravel 5.5

# How To Install & test ?

1. Install Composer.
2. From the command line execute the below:
 composer install.
3. Run Laravel built-in server by the below command:
 php artisan serve
4. Test the below Endpoints:

a. To get data for a specific location Use this Example:
http://127.0.0.1:8000/api/map/place/textsearch/123 main street?&location=42.3675294,-71.186966&radius=10000

b. To get Data for resturant in sydey use this:
http://127.0.0.1:8000/api/map/place/textsearch/restaurants+in+Sydney
