# Pharmacy Management System

This is a Pharmacy Management System project that allows for the management of medicines, sales, and billing through a web interface. The backend is built with PHP and SQLite, and the project includes a Python script for initializing the database.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Clone the Repository](#clone-the-repository)
- [Set Up the Environment](#set-up-the-environment)
- [Create the SQLite Database](#create-the-sqlite-database)
- [Run the Application](#run-the-application)

## Prerequisites

Before you begin, make sure you have the following software installed on your system:

1. **Python**: [Download and install Python](https://www.python.org/downloads/)
2. **SQLite3**: SQLite is usually included with Python, but if you need a separate installation, [download SQLite](https://www.sqlite.org/download.html).
3. **PHP**: [Download and install PHP](https://www.php.net/downloads)

## Clone the Repository

First, clone the repository to your local machine:

```sh
git clone https://github.com/paniraj93/Pharmacy.git
cd Pharmacy
```

## Set Up the Environment

1. **Install Python**: Ensure Python is installed and accessible from the command line.

2. **Install SQLite3**: Ensure SQLite3 is installed. You can check this by running:
    ```sh
    sqlite3 --version
    ```

3. **Install PHP**: Ensure PHP is installed. You can check this by running:
    ```sh
    php -v
    ```

## Create the SQLite Database

Navigate to the `src` directory and run the Python script to create the SQLite database:
```sh
cd src
python create_database.py
```

This script will create the necessary tables in the `pharmacy.db` SQLite database.

## Run the Application

Navigate to the `public` directory and start the PHP built-in server:

```sh
cd ../public
php -S localhost:8000 -c C:\php\php.ini
```
Replace C:\php\php.ini with the actual path to your PHP configuration file if it is different.

## Access the Application

Open your web browser and go to http://localhost:8000. You should see the Pharmacy Management System homepage.

## Admin Login
username = 'admin'
password = 'password'
Use the admin credentials to log in to the admin dashboard where you can manage medicines, view sales data, and more.
