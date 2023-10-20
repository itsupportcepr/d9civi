# d9civi
Setting up local DDEV Project for Drupal9 and CIVICRM:
Please login to your github.com account, visit the following repository and fork it with the same name in your account: https://github.com/itsupportcepr/d9civi

Creating local environment:
-
Please make sure that you have DDEV installed locally.
-
Please ‘cd’ to the directory where you want to place the project folder.
-
And then run following commands (Please replace USERNAME with your own):
git clone https://github.com/USERNAME/d9civi.git
cd d9civi
ddev start
Import the Database: ddev import-db --src= d9civioriginal.sql
ddev drush cr Please visit your site and log in: https://d9civi.ddev.site
user: admin pass:1234
