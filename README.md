# sampleBankAPI

# Objective

Build an internal API for a fake financial institution using PHP and Laravel

# Brief

While modern banks have evolved to serve a plethora of functions, at their core, banks must provide certain basic features. The task is to build the basic REST API for one of those banks. Imagine designing a backend API for bank employees. it could ultimately be consumed by multiple frontends (web, iOS, Android).

# Tasks

* Implement assignment using:
* * Language: PHP
* * Framework: Laravel
* There should be API routes that allow them to:
* * Authenticate users.
* * Create a new bank account for a customer, with an initial deposit amount. A single customer may have multiple bank accounts.
* * Transfer amounts between any two accounts, including those owned by different customers.
* * Retrieve balances for a given account.
* * Retrieve transfer history for a given account.
* All endpoints should only be accessible if an API key is passed as a header.
* All role-based endpoints should require authentication.
* Write tests for your business logic.
* Provide a documentation (published with Postman) that says what endpoints are available and the kind of parameters they expect.
* You are expected to design all required models and routes for your API.
