# Exercise summary

 - Ansible and Ansistrano configuration files were added to deploy the application in production servers. These files install and start the necessary services and install application dependencies with Composer.
 - MySQL and Redis services are deployed in a separated instance
 - Application styles (defined and compiled using LESS) have been optimized with [clean-css](https://github.com/jakubpawlowicz/clean-css).
 - Home page rankings are retrieved from a Redis sorted set, where each visit to an article is registered.
 - The registration form offers the option to upload a profile image, which will be stored in Amazon S3 and loaded in the home and article pages.
 - Cache headers with a maximum age have been specified for each page.
 - Configuration parameters are specified in `resources/config/parameters.php`, which can be initialized copying the `parameters_tpl.php` file.

Diary documents, AB and Blackfire screenshots and an Excel document with all data can be found in the `journals` folder.
