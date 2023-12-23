Mutant Backend Test

Welcome to the Mutant Backend Test repository! 
This backend serves as the server-side implementation for Backend Test, providing essential functionalities for user and admin interactions.

Features

User Functionalities

1. Account Management
Create an account (Register).
Sign in.
View personal information.
Modify personal information.
Change password.

2. Shopping Cart
Add a product to the cart.
Modify the cart (increment, decrement, and remove products).

3. Purchase
Make a purchase using the Stripe payment gateway (developer test mode).

Admin Functionalities

1. User Management
View information of all users.
Modify user information.
Delete a user from the site.
Promote a user as an administrator (admin).

2. Self-Promotion
If only one user exists in the database, the user has the option to promote itself to admin.

3. Product Management
Create a product (product name, price, description).

Getting Started

1. Clone the repository: git clone https://github.com/gmllanes-IT/Mutant-Backend_test.git
2. Create Database
3. Copy Environment Variables
4. Run Migrations
5. Run Seeders:
   php artisan db:seed --class=UsersTableSeeder
   php artisan db:seed --class=ProductSeeder
6. Run the Application:
   php artisan serve
   npm install && npm run dev
7. Default Admin Credentials
   Email: admin@admin.com
   Password: password
   
