# GSES2 BTC application

This service has ability:

    - find out the current bitcoin (BTC) exchange rate in hryvnia (UAH)

    - sign an email to receive information on the exchange rate change

    - a request that will send the current rate to all subscribed users.

# Run project locally
1. Copy `.env` to `.env.local`. You can use variables without any changes to work by default.
2. Install Docker and Docker Compose.
3. Run `docker-compose up -d`
4. Run `docker-compose exec php-fpm composer install` to install dependencies
4. Project will be available on http://localhost:4501 by default.
5. Api documentation will be available on http://localhost:4501/api/doc 
The swagger documentation was updated from 2 to 3 version and adopted with `NelmioApiDocBundle`
6. Also, in project was used `schickling/mailcatcher`. It is super simple SMTP server which catches 
any message sent to it to display in a web interface. UI is available on http://localhost:32770

# API short description
1. `/api/rate` - find out the current bitcoin (BTC) exchange rate in hryvnia (UAH). Project use https://developer.coingate.com/reference/get-rate api endpoint to get rate
2. `/api/subscribe` - sign an email. Emails are stored in simple txt file by path `system/emails.txt`. Project will create this file if it isn't created.
3. `/api/sendEmails` - send the current rate to all subscribed users. Project read subscribed emails from `system/emails.txt` and send emails to with current rate to them

# Tests
1. Copy `.env.test` to `.env.test.local`. You can use variables without any changes to work by default.
2. To run tests you can use command `docker-compose exec php-fpm bin/phpunit`