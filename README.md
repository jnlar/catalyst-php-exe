## parse-csv

Simple PHP-CLI script that:
- Creates/rebuilds database tables
- Iterates through CSV rows and inserts each record into a dedicated MySQL database
- Prevents insertion of invalid email addresses into the MySQL database
- Reformats/cleans CSV data (E.g capitalising first/last names)
- Runs email validation tests on parsed CSV data


### Dependencies
- [vanilla/garden-cli ^3.1](https://github.com/vanilla/garden-cli)
- [league/csv ^9.7](https://github.com/thephpleague/csv)

Run `composer install` (or the equivalent install command for your PHP package manager) to install the dependencies.

### Usage

```
OPTIONS
  --create_table   This will cause the MySQL users table to be built (no further
                   action will be taken)
  --database, -d   MySQL database
  --dry_run        To be used with the --file directive, this option will parse
                   the CSV file but not insert into the database
  --file           [csv file name] - This is the name of the CSV file to be
                   parsed
  --help, -?       Display this help.
  --host, -h       MySQL host
  --password, -p   MySQL password
  --user, -u       MySQL username
```

### Examples

Creating a table: 

> php user_upload.php -h localhost -u user -p password -d example_database --create_table

Inserting parsed CSV data into table: 

> php user_upload.php -h localhost -u user -p password -d example_database --file example_csv.csv

Parse CSV with `--dry_run`: 

> php user_upload.php --file example_csv.csv --dry_run
