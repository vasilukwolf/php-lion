# PHP-Lion

An example of client implementation for REST API with Guzzle.

## API Legend

You need to write integration to https://api.example.org which allows you to request
user data and update it in a third-party system. Initially, we request the
user's data, then we change some of their credentials (name and lock flag,
their rights) and send the result to the integrated system.

### Authorization

To work with the api, you must first log in to the address
https://api.example.org/auth

* GET method
* login and pass parameters
* where login=test, pass=12345

the result will be a json containing the token

answer:

```json
{
   "status":"OK",
   "token":"dsfd79843r32d1d3dx23d32d"
}
```

### Getting user data

The api also provides the ability to get user data at the address
https://api.example.org/get-user/{username}?token={token}

* GET method
* username = muffin
* token = token received during authorization

the result will be a json that contains the user's data

Answer:

```json
{
   "status":"OK",
   "active":"1",
   "blocked":false,
   "created_at":1587457590,
   "id":23,
   "name":"Ivanov Ivan",
   "permissions":[
      {
         "id":1,
         "permission":"comment"
      },
      {
         "id":2,
         "permission":"upload photo"
      },
      {
         "id":3,
         "permission":"add event"
      }
   ]
}
```

### Sending user data

And the api provides the ability to update user data at
https://api.example.org/user/{user-id}/update?token={token}

* POST method
* request body:

```json
{
   "active":"1",
   "blocked":true,
   "name":"Petr Petrovich",
   "permissions":[
      {
         "id":1,
         "permission":"comment"
      }
   ]
}
```

Answer:

```json
{
   "status":"OK"
}
```

For each request, in Addition to the standard HTTP response codes, there is an
additional parameter "status" that signals the success of the operation:

* OK - Successful
* Not found - User not found
* Error - Any error
