# Data Storage

In order to be able to answer the question of how many donations were initiated on each day, we need a data source to store donation attempts. This will be a separate donation table, one row for each attempt. The schema for this table can be found in `./schemas/donation.schema`, for ease of validation. I have also created a `./schemas/user.schema` for validating user data.

For the purposes of this exercise, we will be storing the user id, timestamp, amount, and which organization the user is donating to.

Although not a part of this assignment, knowing which station or network is receiving the donation is important information and will be also stored. I am also storing the amount of the donation.

MYSQL stores timestamps in YYYY-MM-DD hh:mm:ss format, in Coordinated Universal Time (UTC). I will be also storing my timestamps in that format and time zone.

```
donation {
    "id": "string",
    "user_id": "string",
    "timestamp" "string",
    "amount_USD" : "number",
    "org_id": string"
}
```

I will not be adding schema validation to this project, in order to keep the level of effort to about 60 minutes, but the schemas can be checked at https://www.jsonschemavalidator.net/. 

# Code Changes

Following the existing code, I've create a new model class called `Donation.php`, using the `User.php` class as a template. 

I've added a function to `Controller.php` called `createDonation` following the pattern of `createUser`. 

In `Provider.php`, I renamed `DATASTORE_FILE` to `USER_DATASTORE_FILE` and added `DONATION_DATASTORE_FILE` to accomodate the new data store for donations. 

I've added a new endpoint to `index.php` for handling POST calls to `/v1/donations`, including error handling. 

# Usage

The API accepts a POST call to `/v1/donations` with `user_id`, `amount_USD`, and `org_id`. It will automatically set the timestamp to the current time.

The controller validates that the user exists and that the donation amount and org id are valid. The API will return HTTP 201 on success, and HTTP 400 with an error message, if not.

# QA

Unit tests have been updated in `ControllerTest.php` to check that the controller code continues to work correctly, and that donations are correctly created and validated.

Using Postman, we can also validate that the code works and creates new entries in `donations.json`.

`POST http://127.0.0.1:8080/v1/donations`

```
{
    "user_id": "4",
    "amount_USD": 7.50,
    "org_id": "350"
}
```

# Answering the question

The question that needed to be answered is "How many users started a donation attempt in the past day?".

This data model should handle that easily. If this were a relational database, the SQL for this query would be:

```
SELECT COUNT(DISTINCT user_id) AS unique_users
FROM donations
WHERE timestamp >= NOW() - INTERVAL 1 DAY;
```