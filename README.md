# tutorial

Phalcon 3.4  
PHP 7.3.30  
Phalcon Devtools 3.4.11  
Development server is run using 'phalcon serve' in cmd and XAMPP is used as the web server host, database is phpMyAdmin.  

The database as of now has 6 tables:  
'Users' with the fields 'id', 'email', 'password' and 'username'.  
'Cart' with the fields 'id' and 'user_id'.  
'Tickets' with the fields 'id', 'name', 'details', 'type', 'price' and 'quantity'.  
'CartContents' with the fields 'id', 'cart_id', 'item_id', 'item_type' and 'quantity'.  
'Merchandise' with the fields 'id', 'name', 'details', 'price' and 'image'.  
'Orders' with the fields 'id', 'user_id', 'purchase_date', 'book_date', 'total_cost' and 'details'.  

- - - - -

Changelog (31 October 2021)
- Booking: User was able to pick a date prior to the current day, which should not be possible. This is now fixed.
- Booking: Added a way to handle when user did not book a date at all. Updated relevant pages to display this information correctly.
- Added PHPMailer to the project.
- Cart: After a successful checkout process, an email will be sent.
- User: After a successful sign up, an email will be sent.
- User: After a successful password change, an email will be sent.
- The above emails are kept simple for now, they are just notification emails.
- Minor frontend adjustments.

- - - - -

Changelog (24 October 2021)
- Merchandise: added as a feature, works very similar to Tickets
- Merchandise: added a page for it, changed how Cart and CartContents works to accomodate changes
- Merchandise: predetermined in the database and can only have CRUD performed on it via API or direct database access
- Order History: added as a feature
- Order History: can be accessed via Manage Account page
- Cart: added Order Summary page before checkout, added checkout procedure
- Bookings: changed how it works, its database table is no longer needed and has been dropped, its Model and Controller removed
- navbar: updated user dropdown to include a link to purchase Merchandise
- fix: User could not delete their account, solved by adding Relation::ACTION_CASCADE to the initialize() function in Users model
- chore: various code changes or updates to reflect all new additions (or minor improvements)

- - - - -

Changelog (17 October 2021)
- CartContents: changed how Cart and Tickets work, new table CartContents added
- feature: Bookings added (for user to book a ticket pick up date), can be accessed via the user's Cart page
- chore: dropped 'contents' field from Cart table
- chore: adjusted certain code to accomodate the new changes
- removed some redundant code and comments
- removed unused files

- - - - -

Changelog (10 October 2021)
- navbar: updated to add 'cart' link, also updated user dropdown to include a link to purchase Tickets
- Cart: added as a feature 
- Cart: each user has 1 Cart (created only when they access the page for cart the first time) and can add any number of different Tickets to it
- Cart: any items added can have their quantity changed or be removed entirely, also their entire total cost is calculated and displayed at the bottom of the page
- Tickets: added as a feature
- Tickets: predetermined in the database and can only have CRUD performed on it via API or direct database access
- Tickets: if a certain Ticket is added to the cart, it will have its 'Add to Cart' button disabled (will be re-enabled when it is removed from the Cart)
- added pages for Cart management and purchase of Tickets
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
- user registration, login, session, logout
- signup form validation, measures to ensure no duplicate users are being made
- users are redirected back to index if they are logged in and try to access certain pages by manually typing in the URL (implemented for login and signup)
- htmlspecialchars are used to display username to prevent shenanigans
- passwords are only stored in SHA-1
