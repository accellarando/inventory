# MBS Books Inventory Application
An inventory application for use in MBS campus bookstores.

Developed for the University of Utah by Ella Moss, started by Jimmy Glasscock.

## Prerequisites
* PHP, naturally
* A MySQL server, running, with mysql in your PATH
* A web server - this was deployed with Apache, YMMV with others

## Installation
1. Unzip repository into your webserver's web root (typically the htdocs directory).

2. Copy your organization's logos into images/UCSLogos.png, and the favicon to favicon.ico.

3. Navigate to webserver/inventory/install.php in your web browser.

4. Provide the database information that you're connecting to.

5. Run the script and cross your fingers!

6. You should now remove install.php and install.sql.

7. Set up or remove your reCAPTCHA keys in index.php.

8. The default username and password is "admin".

9. Go through and update email addresses as needed for your org.

10. Update departmentSelector.html and storeSelector.html (in the classes folder) to reflect how you do things.

11. See the wiki for instructions on importing your first validation file. (todo: write this)
 
## Usage
See the wiki for documentation and tutorials.
