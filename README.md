# Dolphins_Final_Project
Program for ICS-325 internat Application Development


## Troubleshooting

if image upload isn't working, it could be your permissions for write access to the images directory. To find out, create a file called `test_permissions.php` and add this block of code to it:

```php
<?php
$test_file = 'images/test_file.txt';

if (file_put_contents($test_file, 'This is a test.') !== false) {
    echo "Write permissions are OK.";
    unlink($test_file); // Clean up by removing the test file
} else {
    echo "No write permissions.";
}
?>
```

next go to browser and open the `test_permissions.php` file and see what is printed on the screen