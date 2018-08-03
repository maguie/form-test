Simple Form test
==================================================

This is a simple ZF3 project that includes:
 * Creation of form model:
   ** First name (required)
   ** Last name
   ** Email address (required)
 * Use the form model in a controller.
 * Render the form with special form view helpers.
 * Validate inputs with inputFilter, for email DNS validation is in place.
 * Use form security elements (CSRF).
 * Email input validated in real time while typing using JQuery.
 * Bootstrap as frontend style.
 * Unit testings for
    ** testIndexActionCanBeAccessed (default) - Routing and access to index
    ** testIndexActionViewModelTemplateRenderedWithinLayout (default) - Model Template using layout container
    ** testInvalidRouteDoesNotCrash (default) - default test for invalid routes
    ** testInputFiltersAreSetCorrectly (custom test performed) - I've added this test to check that all inputs have inputFilter assigned for validation.

## Installation

You need to have Apache 2.4 HTTP server (or containers with nginx), PHP v.5.6 or later plus `gd` and `mbstring` PHP extensions.

Clone the repository in your apache directory (sample /var/www/html):
```
git clone 
```

Note: I've included the vendors in case you have problem with the composer commands below, they are optionals.

Run Composer as follow (optional), this command will install the dependencies (zf3):
```
php composer.phar install
```

Enable read-write permissions to the cache assigning your Apache user as owner of this folder:
```
sudo chown -R www-data:www-data data/cache
```

You should be able to see the Simple Form Test website by visiting the link "http://localhost/form-test/public"

Another option is to see it in the public environment I've setup in GCP, application is running in a Compute Engine VM, in the following link:
http://104.155.134.252/form-test/public 

## Testing the Simple Form
Follow the next steps...
 * Go to the home page and click on "Simple Form" link, this will redirect you to a Simple form with three inputs. First Name and E-mail are required.
 * When you type email that are not completely valid, you will see the message "Email invalid! Use the basic format local-part@hostname.", this will hide once the complete text is valid.
 * Click submit to validate the data, if there is any invalid information, you will see the error message in red color below each input. If the information is valid, you will be redirected to a Thank you page saying Hello <<firstName lastName>>!!!

## Testing phpUnit tests
First, enable unit test running the command in the route path of your project:
```
composer require --dev zendframework/zend-test
```

For the Unit Testings available, you will see the result executing the following command in the route path of this project form-test
```
./vendor/bin/phpunit
```

In order to force a failure in the unit Testings, you could edit the custom function "testInputFiltersAreSetCorrectly", change the following line from $this->assertTrue($inputFilter->has('email')); to:
```
$this->assertTrue($inputFilter->has('email2'));
```
And run again the command
```
./vendor/bin/phpunit
```
You will see one test case failure!


## Questions?

If you have any question, please contact me via email. Thanks in advance for this great opportunity!

