Backend response format
=======================

Ci-tron backend must be homogeneous in their response. That's why it's time
to write how responses must be build.

[I. Http must be respected](#i-http-must-be-respected)

[II. Json responses need json http headers](#ii-json-responses-need-json-http-headers)

[III. Queries are http form-data queries](#iii-queries-are-http-form-data-queries)

[IV. Format of common json responses in ci-tron](#iv-format-of-common-json-responses-in-ci-tron)

I. Http must be respected
-------------------------

### Queries

Every route of the backend app must take meaning by it's query method.

For instance, a GET method will only return information and never alter
data.

You can learn more on
[the website of the W3C](https://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html).

### Responses

For us the most important in the request is the response code. If there
is an error you **can't** reply with a `200` response code.

You can find your perfect error code on
[wikipedia](https://www.wikiwand.com/en/List_of_HTTP_status_codes).

II. Json responses need json http headers
-----------------------------------------

As we follow http recommandations for code status, we also follow http on
response content type specification.

So when you render a response in the context of ci-tron. It must be a json
one.

This means that you almost never have to use the `Response` class of
Symfony but you should prefere the `JsonResponse` class of ci-tron.

Finally, here is the header that we use:

```
Content-Type: application/json
```


III. Queries are http form-data queries
---------------------------------------

Many modern APIs use json in query to specify parameters, and even files
are encoded in base64. Their queries looks like this:

```
POST /path/for/query HTTP/1.0
User-Agent: Firefox, whatever
Content-Type: application/json
Content-Length: 56

{"param1": "foo","param2": 42, "param_file": "bGFsYQo="}
```

**This is *not* what we do at ci-tron !**

As we follow http everywhere, we also follow the standardized way to
transfert data: `form-urlencoded`. And if you transfert a file you can use
`multipart/form-data` that are handle by any browser natively.

So a ci-tron request looks more like this:

```
POST /path/for/query HTTP/1.0
User-Agent: Firefox, whatever
Content-Type: application/x-www-form-urlencoded
Content-Length: 23

param1=foo&param2=42
```

IV. Format of common json responses in ci-tron
----------------------------------------------

For now we do not follow [HATEOAS](https://www.wikiwand.com/en/HATEOAS)
because we like the simplicity of our response. But it's maybe something
to consider for the future. Nevermind we follow our rules and here they are.


### 1. On success but nothing more needed

* Response code: 200
* Example of response content:

```json
{
    "message": "It worked, that's awesome !"
}
```

### 2. On error

* Response code could be many depending on the error: 500, 400, 403...
* Example of response content:

```json
// When there is only one error
{
    "error": "The error is that..."
}
// When there is many errors
{
    "errors": {
        "input_errored": "Error message"
    }
}
```

### 3. Getting data

* Response code: 200
* The content will be an object representing the resource you asked:

```json
{
    "id": 42,
    "name": "Maxime"
}
```

### 4. Getting many data


* Response code: 200
* The content will be an array of object representing a resource:

```json
[
    {
        "id": 42,
        "name": "Maxime"
    },
    {
        "id": 43,
        "name": "Valentin"
    }
]
```

> A resource can contains another one. Checkout the doc (or the feature file) of the query you're insterested in.
