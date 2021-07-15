# PHP-Helpers
Some classes to help me in my further projects.

---

## MySQL Static Class

### Configuration
It takes normal `mysqli` properties to config. Here's properties;

```PHP
string $Hostname = null;
string $Username = null;
string $Password = null;
string $Database = null;
int $Port = null;
string $Socket = null;
```
For setting up just access to the properties and set. Example: `MySQL::$Hostname = "localhost";`
If you not configured mysqli ever, here's the [documantation](https://www.php.net/manual/en/mysqli.construct.php).


### `Query()` function without `$Data`
Query function runs a query with `string $Query` and optionaly `array $Data`. Without `$Data` parameter, i suggest you to use general queries with no user input. Example;

```PHP
//It will return array of result.
MySQL::Query("SELECT * FROM Users");

//It will return true. Not suggested method to add records.
MySQL::Query("INSERT INTO Users(FirstName, LastName) VALUES('John', 'Doe')");
```

### `Query()` function with `$Data`
As you know we got something called `SQL injection`, really pain in the ass. So without `$Data` parameter we are runnig a regular query. To prevent SQL injection we got to use `$Data` parameter and some question marks in our query. Here's the example;

```PHP
MySQL::Query("SELECT * FROM Users WHERE Id = ?", [23]);

MySQL::Query("INSERT INTO Users(FirstName, LastName) VALUES(?, ?)", ["John", "Doe"]);
```

### `SingleRow()` function
It's same as `Query()` function. Just makes your life easier, returns first row on selecting* queries. For other queries it will return bool. Here's Example;

```PHP
//It will return first key of array.
MySQL::SingleRow("SELECT * FROM Users WHERE Id = ?", [23]);
```

### `InsertId()` function
I don't know why i added this function instead of making `$InsertId` public, but simply returns last inserted id from a query. Example;

```PHP
//Adding new record.
MySQL::Query("INSERT INTO Users(FirstName, LastName) VALUES(?, ?)", ["Lee", "Doe"]);

//Getting id.
MySQL::InsertId(); //Returns 24.
```

### `Count()` function
This function can be used to count tables. It takes one parameter; `string $TableName`. Give it a table name and do what you wanna do with integer. Example;

```PHP
MySQL::Count("Users"); //It will return 24.
```

Yeah. I get bored with writing. See you next day.