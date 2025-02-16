# Assessment API Documentation
This is the API documentation for the WatchTwr assessment

## Version: 1.0.0

**Contact information:**  
allangallop@gmail.com  

**License:** [MIT](https://opensource.org/licenses/MIT)

### /cart

#### GET
##### Summary:

Get the user's cart

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Cart retrieved successfully |

##### Security

| Security Schema | Scopes |
| --- | --- |
| sanctum | |

#### POST
##### Summary:

Add or update a product in the cart

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Product added/updated successfully |

##### Security

| Security Schema | Scopes |
| --- | --- |
| sanctum | |

#### DELETE
##### Summary:

Clear the authenticated user's cart

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Cart cleared successfully |

##### Security

| Security Schema | Scopes |
| --- | --- |
| sanctum | |

### /checkout

#### POST
##### Summary:

Checkout a user's cart and place an order

##### Description:

This endpoint allows a user to checkout their cart and create an order.

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Order successfully created. |
| 401 | Unauthorized. User must be authenticated. |
| 422 | Failed to process the checkout. |

##### Security

| Security Schema | Scopes |
| --- | --- |
| bearerAuth | |

### /api/orders

#### GET
##### Summary:

Get list of orders

##### Description:

List all orders for the authenticated user.

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | List of orders retrieved successfully |

##### Security

| Security Schema | Scopes |
| --- | --- |
| sanctum | |

### /api/orders/{id}

#### GET
##### Summary:

Get order details

##### Description:

Get details of a specific order.

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| id | path | Order ID | Yes | integer |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Order details retrieved successfully |
| 401 | Unauthorized |
| 404 | Order not found |

##### Security

| Security Schema | Scopes |
| --- | --- |
| sanctum | |

### /api/products

#### GET
##### Summary:

Get list of products

##### Description:

Returns a paginated list of products

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Successfully retrieved products |

### /api/products/{id}

#### GET
##### Summary:

Get product by ID

##### Description:

Returns details of a single product by ID

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| id | path | ID of the product to retrieve | Yes | integer |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Successfully retrieved product |
| 404 | Product not found |
