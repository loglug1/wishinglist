# Wishinglist
This application allows the admin to create a wishlist and send it to people. When gift givers create accounts and claim items, the admin can see when an item is claimed but not by who. This way, the admin can still have a slight element of surprise when opening gifts. This could be for a birthday, holiday, wedding, baby shower, or you name it!

## Key Features
 - Item Claiming
 - Item Management
 - User Registration
 - User Management
 - Secure Authentication: Token Based, Salted+Hashed Passwords, and Brute Force Attack Protection

## Screenshots
<details>
  <summary>Expand to See Screenshots</summary>
  
  ![Home Page](https://raw.githubusercontent.com/loglug1/wishinglist/main/.github/screenshots/home.png)

  ![Claiming an Item](https://raw.githubusercontent.com/loglug1/wishinglist/main/.github/screenshots/claimedItem.png)

  ![Manage Items Page](https://raw.githubusercontent.com/loglug1/wishinglist/main/.github/screenshots/manageItems.png)

  ![Updating Item Info](https://raw.githubusercontent.com/loglug1/wishinglist/main/.github/screenshots/updateItem.png)

  ![Updating User Info](https://raw.githubusercontent.com/loglug1/wishinglist/main/.github/screenshots/updateUser.png)
  
</details>

## How to Run on Docker
The repository comes with a docker compose file that will take care of setting everything up by default. All that you need to do is clone the repository to a local folder, open the folder in your favorite terminal with docker installed and run:

    docker compose up -d

You should now be able to see your instance of the application running at [localhost](http://localhost) or whatever your docker host's address is. By default the application will create a default admin account with the password *password* You can change this within the application once you login under the account page or you also have the option to change it as an environmental variable in docker-compose.yml like shown:

    environment:
      - AZURE_MYSQL_HOST=mysql
      - DEFAULT_WEB_PASSWORD=<put your custom password here>

## How to Run in Custom Environment
The application is capable of being run on any environment with a web server, php with the mysql pdo driver enabled, and mysql.

### Configuring the Web Server
The web server needs to be configured to serve the public directory as the document root with the include directory as a sibling to the public directory.

### Configuring MySQL Server
The mysql server needs to have a database created for the application and a user needs to be given priviledges to manage records and create tables. The database name and username/password can be any values as they are passed as environment variables in the next step.

### Configuring Environment Variables
The application looks for the following environment variables in order to function properly:

    DEFAULT_WEB_PASSWORD - default password of admin user (default: password)
    AZURE_MYSQL_HOST - hostname of your mysql server (default: localhost)
    AZURE_MYSQL_DBNAME - name of your mysql database (default: project)
    AZURE_MYSQL_USERNAME - username for mysql user created earlier (default: root)
    AZURE_MYSQL_PASSWORD - password for mysql user created earlier (default: root)
    AZURE_MYSQL_PORT - port for your mysql server (default: 3306)
    MYSQL_ATTR_SSL_CA - absolute path to the ssl certificate for your mysql server (required if mysql server only accepts encrypted connections, else should be unset)

## About
This project was created by Camden McKay as a final project for his CS 234 class at SIUE.