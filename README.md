# Voucher REST API &middot; [![Build Status](https://travis-ci.org/mathnogueira/voucher-api.svg?branch=master)](https://travis-ci.org/mathnogueira/voucher-api) [![codecov](https://codecov.io/gh/mathnogueira/voucher-api/branch/master/graph/badge.svg)](https://codecov.io/gh/mathnogueira/voucher-api)




REST API responsible for managing discount vouchers.

## Prerequirements
To run this project you will need the following software:

- A web server (apache2 or nginx);
- PHP 7.1+
- MariaDB

### Running the project
First of all, you are going to need to install all dependencies of this project. You can achieve that by running the following command inside the project directory:

```shell
composer install
```

After installing all dependencies, create a database named `voucher_pool` and execute the content of the file `database/evolutions/01.sql` on it.

Now you are good to go.

## Endpoints

In this section, we are going to discover all endpoints available in this API.

### List all recipients
```
GET /api/v1.0/recipient
```
Returns a list of all the existing recipients on the database. Always result in a status `200 OK` response.

### Get a specific recipient by its id
```
GET /api/v1.0/recipient/{id}
```
Returns the data of a recipient identified by its id number. Always result in a status `200` response.

### Register a new recipient
```
POST /api/v1.0/recipient
{
    "name": "recipient name",
    "email": "recipient@email.com"
}
```
Creates a new recipient. This endpoint can result in three HTTP status:

- If the email is already in use, this endpoint will result in a `409 Conflict` HTTP Response;
- If name is empty or the email is invalid, it will result in a `400 BadRequest` HTTP Response;
- If everything is ok and the new recipient was created, it will result in a `201 Created` HTTP Response.

### List all special offers
```
GET /api/v1.0/offer
```
Returns a list of all the existing special offers on database. Always result in a status `200 OK` response containing the following body:
```
{
    "id": 123,
    "type": "Recipient"
}
```

### Get special offer by code
```
GET /api/v1.0/offer/{code}
```
Returns the data of a special offer using its code as identifier. A offer code is a four character alphanumeric string. Note: Codes are case-sensitive, therefore, `ax7d` is not equal to `Ax7d`.

### Register a new special offer
```
POST /api/v1.0/offer
{
    "name": "Black friday",
    "discount": 12.5
}
```
Creates a new special offer. This endpoint can result in two HTTP status:

- If `name` is empty or `discount` is empty or not a number, it will return in a `400 BadRequest` HTTP Status;
- If everything is fine, it will result in a `201 Created` HTTP Status containing the following body:

```
{
    "code": "xxxx",
    "type": "SpecialOffer"
}
```

The field `code` is the identifier of the special offer.

### Generate vouchers for a special offer
```
POST /api/v1.0/voucher
{
    "offerCode": "xxxx"
}
```
Creates a voucher for the special offer identified by the `offerCode` for every recipient who dont't own a voucher for this special offer. Always results in a `201 Created` HTTP Response containing the following body:

```
{
    "created": 8 // number of vouchers created,
    "type": "Voucher"
}
```

### Use a voucher
```
POST /api/v1.0/voucher/use
{
    "email": "myemail@example.com",
    "voucherCode": "4BcD3FgH1J" // Every code is 10 characters long
}
```
Uses the voucher of a special offer. This endpoint will make the voucher invalide after its first use. This endpoint can result in three HTTP status:

- `404 Not Found` if the voucher does not exist;
- `400 Bad Request` if the voucher was used before.
- `200 OK` if the voucher is valid and was used to obtain a discount.

The `400 Bad Request` response has the following content as its body:
```
{
    "errors": "error description"
}
```

The `200 OK` response has the following content as its body:
```
{
    "discount": 32.8 // amount of discount (32.8% in this case)
}
```

### Listing all valid vouchers for a recipient
```
POST /api/1.0/search/voucher/active
{
    "email": "myemail@example.com"
}
```
Returns a list of all valid vouchers for the recipient identified by the provided `email`. Always results in a `200 OK` HTTP response containing the following content as its body:
```
{
    [
        // Recipient owns two valid vouchers
        {
            "voucherCode": "1bCd3fGh1J",
            "specialOfferName": "Everything 20% off"
        },
        {
            "voucherCode": "a1b2c3d4e5",
            "specialOfferName": "Black friday"
        }
    ]
}
```