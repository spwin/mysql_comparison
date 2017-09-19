# mysql_comparison
Comparison of mysql JSON type column filtering by attributes vs inner join on attributes.

- Install as regular Laravel project
- don't forget to create .env
- php artisan migrate
- go to /fields url and populate database
- select either Pivot joins or JSON columns on the second layout (PHP filter is not implemented)
- enter the amount of filters you want to be records filtered with
- submit and enjoy your results

11.79s vs 0.002s

It turns out JSON columns operations are like 1000 times faster!
====
