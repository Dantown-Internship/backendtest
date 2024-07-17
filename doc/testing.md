# Testing

To run the tests, you will need to have the application running. You can do this by running the following process in local-setup.md.

If you have SQLite installed, you just need to run the test command. Otherwise, you have to set up a test database and configure the database values in the phpunit.xml file

Run the test with this artisan command to start running the tests

```bash
$ php artisan test
```