## Movie History Documentation

### Requirements
PHP 8.2
MongoDB 7.x

### Auth

#### Register

[POST] /api/auth/
This endpoint makes the user registration.

Headers:

| Header       | Value            |
| ------------ | ---------------- |
| Accept       | application/json |
| Content-type | application/json |

Body: JSON

| Property | Description   | Required | Condition | Type   |
| -------- | ------------- | -------- | --------- | ------ |
| name     | User name     | yes      | ----      | String |
| email    | User email    | yes      | ----      | String |
| password | User password | yes      | ----      | String |

Responses:

**_ HTTP Code 201 _**

```
    {
        "success": true
    }
```

**_ HTTP Code 400 _**

```
    {
        "success": false
    }
```

**_ HTTP Code 422 _**

```
    {
        "success": false,
        "errors": ...
    }
```

#### Login

[POST] /api/auth/login
This endpoint makes user login and generante a bearer token

Headers:

| Header       | Value            |
| ------------ | ---------------- |
| Accept       | application/json |
| Content-type | application/json |

Body:

| Property | Description   | Required | Condition | Type   |
| -------- | ------------- | -------- | --------- | ------ |
| email    | User email    | yes      | ----      | String |
| password | User password | yes      | ----      | String |

Responses:

**_ HTTP Code 201 _**

```
    {
        "success": true
        "token": ...
    }
```

**_ HTTP Code 400 _**

```
    {
        "success": false
    }
```

**_ HTTP Code 422 _**

```
    {
        "success": false,
        "errors": ...
    }
```

### User

#### Update

[PATCH] /api/user/
This endpoint is used to update user`s personal data.

Headers:

| Header        | Value            |
| ------------- | ---------------- |
| Accept        | application/json |
| Content-type  | application/json |
| Authorization | Bearer ...       |

Body: JSON

| Property | Description | Required | Condition | Type   |
| -------- | ----------- | -------- | --------- | ------ |
| name     | User name   | no       | ----      | String |
| email    | User email  | no       | ----      | String |

Responses:

**_ HTTP Code 200 _**

```
    {
        "success": true
    }
```

**_ HTTP Code 400 _**

```
    {
        "success": false
    }
```

**_ HTTP Code 422 _**

```
    {
        "success": false,
        "errors": ...
    }
```

#### Update Password

[PATCH] /api/user/password
This endpoint is to update user password

Headers:

| Header        | Value            |
| ------------- | ---------------- |
| Accept        | application/json |
| Content-type  | application/json |
| Authorization | Bearer ...       |

Body: JSON

| Property     | Description       | Required | Condition | Type   |
| ------------ | ----------------- | -------- | --------- | ------ |
| old_password | User old password | yes      | ----      | String |
| new_password | User new password | yes      | ----      | String |

Responses:

**_ HTTP Code 200 _**

```
    {
        "success": true
    }
```

**_ HTTP Code 400 _**

```
    {
        "success": false
    }
```

**_ HTTP Code 422 _**

```
    {
        "success": false,
        "errors": ...
    }
```

#### Delete User

[DELETE] /api/user
This endpoint is used to delete user data.

Headers:

| Header        | Value            |
| ------------- | ---------------- |
| Accept        | application/json |
| Content-type  | application/json |
| Authorization | Bearer ...       |

Responses:

**_ HTTP Code 200 _**

```
    {
        "success": true
    }
```

**_ HTTP Code 400 _**

```
    {
        "success": false
    }
```

**_ HTTP Code 422 _**

```
    {
        "success": false,
        "errors": ...
    }
```

### Movies

#### Get Movie Data

[GET] /api/movie
This endpoint is used to get an movie data.

Headers:

| Header        | Value            |
| ------------- | ---------------- |
| Accept        | application/json |
| Content-type  | application/json |
| Authorization | Bearer ...       |

Query Params:

| Property | Description   | Required | Condition | Type   |
| -------- | ------------- | -------- | --------- | ------ |
| name     | Movie name    | no       | ----      | String |
| imdb     | Movie IMDB ID | no       | ----      | String |

Responses:

**_ HTTP Code 200 _**

```
    {
        "success": true,
        "data": {
            "Title": "Guardians of the Galaxy",
            "Year": "2014",
            "Rated": "PG-13",
            "Released": "01 Aug 2014",
            "Runtime": "121 min",
            "Genre": "Action, Adventure, Comedy",
            "Director": "James Gunn",
            "Writer": "James Gunn, Nicole Perlman, Dan Abnett",
            "Actors": "Chris Pratt, Vin Diesel, Bradley Cooper",
            "Plot": "A group of intergalactic criminals must pull together to stop a fanatical warrior with plans to purge the universe.",
            "Language": "English",
            "Country": "United States, United Kingdom",
            "Awards": "Nominated for 2 Oscars. 52 wins & 103 nominations total",
            "Ratings": [
                {
                    "Source": "Internet Movie Database",
                    "Value": "8.0/10"
                },
                {
                    "Source": "Rotten Tomatoes",
                    "Value": "92%"
                },
                {
                    "Source": "Metacritic",
                    "Value": "76/100"
                }
            ],
            "Metascore": "76",
            "imdbRating": "8.0",
            "imdbVotes": "1,284,993",
            "imdbID": "tt2015381",
            "Type": "movie",
            "DVD": "N/A",
            "BoxOffice": "$333,718,600",
            "Production": "N/A",
            "Website": "N/A",
            "Response": "True"
        }
    }
```

**_ HTTP Code 400 _**

```
    {
        "success": false
    }
```

**_ HTTP Code 422 _**

```
    {
        "success": false,
        "errors": ...
    }
```

#### Add to user`s history

[POST] /api/movie
This endpoint is used to add a movie to user's history.

Headers:

| Header        | Value            |
| ------------- | ---------------- |
| Accept        | application/json |
| Content-type  | application/json |
| Authorization | Bearer ...       |

Body:

| Property | Description   | Required | Condition | Type   |
| -------- | ------------- | -------- | --------- | ------ |
| imdb     | Movie IMDB ID | no       | ----      | String |

Responses:

**_ HTTP Code 201 _**

```
    {
        "success": true
    }
```

**_ HTTP Code 400 _**

```
    {
        "success": false
    }
```

**_ HTTP Code 422 _**

```
    {
        "success": false,
        "errors": ...
    }
```

#### Remove from user`s history

[DELETE] /api/movie
This endpoint is used to remove a movie from user's history.

Headers:

| Header        | Value            |
| ------------- | ---------------- |
| Accept        | application/json |
| Content-type  | application/json |
| Authorization | Bearer ...       |

Body:

| Property | Description   | Required | Condition | Type   |
| -------- | ------------- | -------- | --------- | ------ |
| imdb     | Movie IMDB ID | no       | ----      | String |

Responses:

**_ HTTP Code 200 _**

```
    {
        "success": true
    }
```

**_ HTTP Code 400 _**

```
    {
        "success": false
    }
```

**_ HTTP Code 422 _**

```
    {
        "success": false,
        "errors": ...
    }
```
