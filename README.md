## Project Description

This is a microservices application for the PHP Talks Micro Services Series.  
It is a simple e-commerce Laravel application with 3 services. Each service has its own database and later, all 3 services will have an API gateway that will receive the requests and channel it to the appropriate service.
1. The Product Service.
2. The Inventory Service.
3. The Checkout Service.


## POSTMAN COLLECTION

[Postman Collection](https://api.postman.com/collections/11058525-7379fc15-203a-42b5-89fe-b4cc643ee459?access_key=PMAT-01HN8X34ZJSMPCH05S1K44SK0V)

## MIGRATIONS

# CHECKOUT SERVICE
php artisan migrate --path=/database/migrations/2024_01_27_200918_create_cart_items_table.php
php artisan migrate --path=/database/migrations/2024_01_27_200918_create_cart _table.php

# INVENTORY SERVICE
php artisan migrate --path=/database/migrations/2024_01_27_141832_create_inventory_table.php

# PRODUCT SERVICE
php artisan migrate --path=/database/migrations/2024_01_27_141559_create_products_table.php

