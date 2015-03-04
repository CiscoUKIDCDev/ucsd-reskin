# ucsd-reskin
This project is a php-based approach to reskin UCS Director's Catalog GUI. It's built as a demonstration/proof of concept using the REST API.

Dependencies
------------
This application has been built on a Debian GNU/Linux system with Apache and PHP5. In addition, the following PHP libraries are used:
 * PHP cURL library (http requests)
 * PHP Smarty Library (template engine)

These can be installed in Debian via apt:
`apt-get install php5-curl smarty3`

Installation and Setup
----------------------
Clone this repository to a location on your webserver. If you do not have a local config file, move the existing `config.default.php` to `config.php`.

Edit the file with appropriate values for your local UCS Director installation.

Create a directory called `templates_c` and give it write permissions from the web server. In my case, the web server runs under the group www-data, so the following works:

`chown :www-data templates_c`

`chmod g+wr templates_c`

Assuming your config.php file is correct and your `templates_c` folder has the correct permissions you should be able to browse to the tool and view your advanced catalog items.

Copyright and License
---------------------
Copyright (c) 2015 Cisco

Licensed under the MIT license. See the [LICENSE file](LICENSE) for more information
