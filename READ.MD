# MovieDB
* This application is developed with PHP by using MySQL.

* HostPass variable in the dbconfig.php file could be updated according to the password of localhost.

* A local web server can be started by calling the following command inside the project folder:
```
    php -S localhost:8000
```


* The application will be running on http://localhost:8000

### Additional steps if mysqli is not loaded:
1. If php.ini file does not exist then it should be created by copying the content of php.ini-development file.
2. Following line should be uncommented inside the php.ini file.
	```
	extension=mysqli
	```