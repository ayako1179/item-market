php artisan make:model Chat -m
php artisan make:model Message -m
php artisan make:model MessageRead -m
php artisan make:model Review -m
php artisan make:controller ChatController
php artisan make:controller MessageController
php artisan make:controller ReviewController
php artisan make:request Messages/StoreMessageRequest
php artisan make:request Messages/UpdateMessageRequest
php artisan make:migration add_status_to_orders_table
exit
