# API POST
This project serves as a very slim backend designed to work with pure PHP and SQL like BD. In this project is possible create an user, login, create, likes and list all posts.

## Init Project
To install all dependences
```
composer install
```
## Running the API
To run server, execute this code
```
init apache service
```

## Create DB
To conect whit DB is necessary create four columns - 

users
```
id INT PK 
name varchar(45) 
login varchar(100) 
senha varchar(100)
```
tokens_auth
```
id INT PK 
token varchar(255) 
status ENUM('S', 'N')
```
posts
```
id INT PK 
idAuthor varchar(255) 
type varchar(50) 
title varchar(50) 
description varchar(500) 
createdAt varchar(100) 
typeMachine varchar(50) 
likes_count varchar(45)
```
posts
```
id INT PK 
createdBy varchar(255)
content varchar(255) 
createdAt varchar(255) 
likes_count varchar(45) 
postId varchar(45)
```

