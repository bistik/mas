Coding test for an API project that will create a user, create a loan, make repayments to a loan.

API routes:


POST /api/auth/register - create user


POST /api/auth/login - get access token


GET /api/auth/logout - revoke token


GET /api/auth/user - user info


POST /api/loans - create new loan (requires auth)


GET /api/loans - list users loans


POST /api/repayments - post a payment to users loan (requires auth)


GET /api/repayments/ - get users loan' repayments, amortization table



Tests

phpunit


some setup

cp .env .env.testing
php artisan migrate --env=local,testing
php artisan passport:install
php artisan optimize:clear
