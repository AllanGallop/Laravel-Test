# WatchTwr Assessment

### Setup Instructions

> Docker

1. Download and extract project files or clone repository
2. Navigate to the `.docker` directory
2. Run the following command
```docker-compose up -d --build```



> Laravel Environment

Alternatvely if a local development environment is already availble (PHP, Node, Composer, MySQL) then this project can be operated directly.

1. Download and extract or pull this repository
2. Update `.env` with SQL database connection details 
3. run the following commands:

```
composer install --no-interaction --prefer-dist --optimize-autoloader

php artisan key:generate
php artisan migrate:fresh
php artisan db:seed

npm run build

php artisan serve
```

### Running Tests
Tests can be run by using the command ```php artisan test```


## Project Assignment

The project automatically seeds a default admin user for assesing the solution:

|key|value
|----|----|
|email|admin@example.com|
|password|password|


### API Endpoints

The API endpoints are described in supplied [Postman collection](Laravel-assessment.postman_collection.json) and also availble in [API documentation](api-doc.md).


### Admin Interface

The admin interface can be accessed using a browser via
[http:\\localhost\admin](http:\\localhost\admin) using the admin login credentials above.

## Planning & Assumptions

As an evaluation project the web interface is not styled as this was not a requirement stated in the provided instructions. Further assumptions were also made based on the context provided.

- No translations required
- Limit functionality to minimal requirements
- Use Livewire and Jetstream where possible
- AAA not required, maintain minimal authentication only
- No external logging

The project was planned and executed using [Github projects](https://github.com/users/AllanGallop/projects/6/views/1) and remains available to review.

### Part 1

Create an API for product listing and checkout system. The API requires the following features:

1. **View Products**
- Assumed: pagination required
- Assumed: guests can access
- Assumed: Products have single currency pricing
- Specified: Can list all products
- Specified: Can view specific product
- Specified: Products have a low stock level threshold

2. **Add Product to Cart**
- Specified: Can add a product to cart
- Assumed: Cart products can be updated / removed
- Assumed: User only has one cart
- Assumed: Products have quantity
- Assumed: Endpoint should implement a datamodel to limit action complexity

3. **List products in Cart**
- Specified: Can list all products in cart
- Assumed: Pagination required

4. **Checkout Cart**
- Specified: User can checkout cart
- Assumed: Entire cart is checked out
- Assumed: Check stock availability during checkout

5. **View Orders**
- Specified: User can view thier orders
- Specified: Orders have a status
- Assumed: Shipping information not required
- Assumed: Payment handler not required

### Part 2

1. **Admin - Orders**
- Specified: Admin can view all orders
- Specified: Admin can update order statuses

2. **Admin - Products**
- Specified: Admin can perform CRUD operations on products
- Assumed: Admin has a low stock level report