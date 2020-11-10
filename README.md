# Online-Banking
Responsive Online Banking Website.

Online Banking application is an efficient banking system, which provides many facilities to the customer.<br />
User can create new Account, Deposit/Withdraw money, check balanced amount and account statement.<br />
User can transfer money using account no. or mobile no.<br />
User can view their bank details, update details, close account online.<br />

#### Instructions
1. Create a MySQL Database using the following command:
	```
	CREATE DATABASE mvmt_database;
	```

2. Create two tables in this database using following commands:
	```
	CREATE TABLE mvmt_records (
	    accountNo VARCHAR(255) NOT NULL,
	    firstName VARCHAR(255) NOT NULL,
	    lastName VARCHAR(255) NOT NULL,
	    pin VARCHAR(255) NOT NULL,
	    balancedAmount VARCHAR(255) NOT NULL,
	    aadhaar VARCHAR(255),
	    mobile VARCHAR(255) NOT NULL,
	    email VARCHAR(255),
	    PRIMARY KEY(accountNo)
	);
	```

	```
	CREATE TABLE mvmt_statements (
		accountNo VARCHAR(255) NOT NULL,
		info VARCHAR(255) NOT NULL,
		dateTime DATETIME NOT NULL,
		amount VARCHAR(255) NOT NULL
	);
	```
