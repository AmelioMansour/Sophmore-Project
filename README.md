## Start Apache server

#### Note: this guide assumes you are running Ubuntu, if you are on another system, see the install guide <a href='https://httpd.apache.org/docs/2.4/install.html'> here </a>

#### On Ubuntu, Start the server
```sh
sudo apt install apache2
sudo service apache2 start
```

#### Confirm it's running
```sh
sudo server apache2 status
```

#### Change directory to SunnyTime/var/ and copy the www/ directory to /var/www/
```sh
sudo rm -r /var/www/html
sudo cd var/www/html #Note: the /var inside this project
sudo cp -r * /var/www/
```

#### Allow write access to the contents of contacts/ images/ and bios/
#### In /var/www/
```sh
sudo chmod o+w contacts/
sudo chmod o+w iamges/
sudo chmod o+w bios/
```


#### Change the Apache config to execute php
```sh
sudo a2enmod php8.2

```

#### Install a dependency
```sh
sudo apt install php-mysqli
```

## Move the root directory of the Apache server
#### Edit the file /etc/apache2/sites-available/000-default.conf
#### Change DocumentRoot to the inner html file in this project

#### Edit the file /etc/apache2/apache2.conf
#### Change /var/www/html to the same directory, around line 170

#### Restart the apache server
```sh
sudo server apache2 restart
```

## Start SQL server
#### Install mysql (should be running automatically after install)
```sh
sudo apt install mysql-server
sudo service mysql status
```

## Configure the server
#### Open mysql cli
```sh
sudo mysql
```

#### Add user
```SQL
CREATE USER 'apache2'@'localhost' IDENTIFIED BY 'sunnytimepassword';
```

#### Grant permissions
```SQL
GRANT CREATE, ALTER, DROP, INSERT, UPDATE, DELETE, SELECT, REFERENCES, RELOAD on *.* TO 'apache2'@'localhost' WITH GRANT OPTION;

```

#### Confirm changes
```SQL
SHOW GRANTS FOR 'apache2'@'localhost';
```

#### Initialize the schema; Make sure you are in the directory containing serverinit.sql
```sh
mysql -h localhost -u apache2 -psunnytimepassword < serverinit.sql
```

## Finishing up
```sh
Navigate to http://localhost/views/login.php
```

#### Turn off sql and apache servers
```sh
sudo service apache2 stop
sudo service mysql stop
```
