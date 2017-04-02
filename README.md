# Beat the Haze! #

From time to time, Malaysia suffers from haze, caused by man-made forest and peat fires in Indonesia that go completely out of control. This causes great concern with the public at large over the quality of the air in their area or state.

To inform the public about this, the Malaysian government provides information about the so-called Air Pollution Index or API on the website of the DoE, the Department of the Environment. 

You can find these APIs nicely displayed on a map here:

[http://apims.doe.gov.my/v2/](http://apims.doe.gov.my/v2/)

This information is also made available in table format, via the following link:

[http://apims.doe.gov.my/v2/table.html](http://apims.doe.gov.my/v2/table.html)

If you visit this link, you will actually see the latest available API data in table format, neatly organized by state, area and time.

Your task is to write a command line interface using PHP that takes as input a state or an area and outputs the latest API readings for that state or area, like so:



```
php show_api.php “state=<name of state>”
```



This must show the latest API reading for all areas in a state.

Example: 
```
php show_api.php “state=johor”
```

This must show the latest API reading for all areas in Johor


Or:

```
php show_api.php “area=<name of area>”
```

This must show the latest API reading for a specific area only

Example: 

```
php show_api.php “area=Bandaraya Melaka”
```

This must show the latest API reading for Bandaraya Melaka only


## Additional specifications: ##

1. The input to show_api.php is case insensitive and should be trimmed for spaces. In other words:  “state=Johor” is exactly the same as “  STaTE  = JOHOr  ”

2. **Make sure to include proper Unit Tests in your code** (See [https://phpunit.de/](https://phpunit.de/))

3. **Error handling is very important**. You need to properly respond to and report all errors on the command line.

4. You can use a tool like curl to scrape the required information from the DoE’s website.

5. You must figure out how to generate the link that actually loads the correct web page with the latest API data, in other words, how to get from: http://apims.doe.gov.my/v2/table.html to: http://apims.doe.gov.my/v2/hourX_YYYY-MM-DD.html

6. Clean code and proper structure are important

7. We value simplicity!

**Please commit your solution into this BitBucket repository and notify us when you have done so.**

Should you have any questions, don't hesitate to contact us.

Happy Coding!