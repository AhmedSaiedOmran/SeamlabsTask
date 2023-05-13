# Seamlabs senior backend opportunity << Technical task >>> #

## Content ##
1. Tasks

    1.1. Count numbers except numbers contains 5.
   
    2.2. Index of this string.

    2.3. Steps Counter.
2. Resturant Order API

---

## Requirements ##

Or to run the project locally:
- PHP 8.1+
- MySQL 5.7+
- PHP (memory_limit) >= (512M)

---

## Local Installation ##
1. Clone the project to your workspace.

2. Copy .env.example to .env 
```
cp .env.example .env
```

3. Install the laravel dependencies
```
composer install
```

4. Generate Key
```
php artisan key:generate
```

5. Execute the migrations and seeders
```
php artisan migrate:fresh --seed
```

6. Start the laravel app server
```
php artisan serve
```

---

## APIs documentations:
- Postman collection is included (SeamLabs.postman_collection.json) in root directory please change host and port variable of postman collection

## /numbers-without-5?start_number={start_number}&end_number={end_number}  (GET)

- Description: Count all numbers between start and end except any number that contain 5
- Method: GET
- params : start_number (number) required filed, must be number, should be lower than end number
- params : end_number (number) required filed, must be number, should be greater than start number
- return type : JSON data with [data] key and the integer value

### example: 
- request: /numbers-without-5?start_number=1000&end_number=22222
- response: 
```
{
    "success": true,
    "message": "Success",
    "data": 14034
}   
```

---

## /index-of-string?input_string={input_string}  (GET)

- Description: Return the index of this string
- Method: GET
- params : input_string (number) required filed, must be alphabet
- return type : JSON data with [data] key and the integer value as index of string

### example: 
- request: /index-of-string?input_string=AA
- response: 
```
{
    "success": true,
    "message": "Success",
    "data": 27
}
```

---

## /calculate-steps?N={array_length}&Q={array_elements}  (GET)

- Description: Return the minimum number of moves for each element of array
- Method: GET
- params : array_length (number) required filed, must be numeric, min: 1, max: 99999
- params : array_elements (Array) [comma seprated numbers 2,3,4] required filed, should have at least one elemen and max 99999 elements, each element can be from: 0 to 99999, and N should be equal array_elements length
- return type : JSON data with  [data] key and Array value as required steps for each element

### example: 
- request: /calculate-steps?N=2&Q=3,4
- response: 
```
{
    "success": true,
    "message": "Success",
    "data": [3,3]
}
```

---


## How the restaurant order API works:
1. List Menu Items through ```/get-menu``` (GET Request)
2. Make Order through ```/make-order```  (POST Request) (See Postman Collection)
    - Request Body
        - type [dine-in, delivery, takeaway] : required filed
        ---
        - menu_items (array) : required filed
            - menu_items.* (array of menu items) : required filed
                - menu_items.*.id (int) : required filed, ID of menu item
                - menu_items.*.qty (decimal) : required filed, qty per item
        ---
        - table_number (string) : required filed if (type == dine-in)
        - waiter_name (string) : required filed if (type == dine-in)
        - service_charge (decimal) : required filed if (type == dine-in)
        ---
        - customer_name (string) : required filed if (type == delivery)
        - customer_phone (string) : required filed if (type == delivery)
        - delivery_fees (decimal) : required filed if (type == delivery)

    -Return 
        - success (boolean) true if success
        - message (string)  "Order Created Successfully!"
        - data: json contain order info

