# tutorial

Phalcon 3.4  
PHP 7.3.30  
Phalcon Devtools 3.4.11  
Development server is run using 'phalcon serve' in cmd and XAMPP is used as the web server host, database is phpMyAdmin.  
The database for now only has the table 'Users' with the fields 'id', 'email', 'password' and 'username'.

- - - - -

Changelog (26 September 2021)
- navbar: updated to add user panel dropdown where they can select account management or logout
- added account management page
- user can now edit username
- user can now change password
- user can now delete their account
- added error controller for non-existent pages (404)
- miscellaneous changes (changed how users are redirected when accessing non-authorized pages, set fields to 'required' thus removing the need of an additional 'if' case, etc.)

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
