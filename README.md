# tutorial

Phalcon 3.4  
PHP 7.3.30  
Phalcon Devtools 3.4.11  
Development server is run using 'phalcon serve' in cmd and XAMPP is used as the web server host, database is phpMyAdmin.  
The database as of now has 3 tables:  
'Users' with the fields 'id', 'email', 'password' and 'username'.  
'Cart' with the fields 'id', 'contents' and 'user_id'.  
'Tickets' with the fields 'id', 'name', 'details', 'type', 'price' and 'quantity'. ('quantity' not in use so far - originally there with the idea of tickets having limited supply in mind.)

- - - - -

Changelog (10 October 2021)
- navbar: updated to add 'cart' link, also updated user dropdown to include a link to purchase tickets
- cart: added as a feature 
- cart: each user has 1 cart (created only when they access the page for cart the first time) and can add any number of different tickets to it
- cart: any items added can have their quantity changed or be removed entirely, also their entire total cost is calculated and displayed at the bottom of the page
- tickets: added as a feature
- tickets: predetermined in the database and can only have CRUD performed on it via API or direct database access
- tickets: if a certain ticket type is added to the cart, it will have its 'Add to Cart' button disabled (will be re-enabled when ticket is removed from the cart)
- added pages for cart management and purchase of tickets
- miscellaneous changes (most user redirection to "index" have been replaced with "" to simplify the URL, replaced instances of where the database is searched with findFirst() with findFirstById(), other etc. improvements)

- - - - -

Changelog (26 September 2021)
- navbar: updated to add user panel dropdown where they can select account management or logout
- added account management page
- user can now edit username
- user can now change password
- user can now delete their account
- added error controller for non-existent pages (404)
- added validation to ensure no usernames are duplicate
- miscellaneous changes (changed how users are redirected when accessing non-authorized pages, set fields to 'required' thus removing the need of an additional 'if' case, other etc. improvements)

- - - - -

Changelog (19 September 2021)
- basic navbar: current functional links on it are home and login buttons (login becomes logout if session is active)
- user registration
- user login
- user session
- user logout
- signup form validation, measures to ensure no duplicate users are being made
- users are redirected back to index if they are logged in and try to access certain pages by manually typing in the URL (implemented for login and signup)
- htmlspecialchars are used to display username to prevent shenanigans
- passwords are only stored in SHA-1
