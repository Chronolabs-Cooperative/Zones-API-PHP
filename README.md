## Chronolabs Cooperative presents

# Zones DNS Propogation REST API Services

## Version: 1.1.11 (pre-alpha)

### Author: Dr. Simon Antony Roberts <simon@snails.email>

#### Demo: http://zones.snails.email

So this REST API allows for Zones or DNS Propogation with PowerDNS and MySQL backend incorporated, you can add zones to your system on the fly securely and concisely with this api.

# Apache Mod Rewrite (SEO Friendly URLS)

The follow lines go in your API_ROOT_PATH/.htaccess

    php_value memory_limit 16M
    php_value upload_max_filesize 1M
    php_value post_max_size 1M
    php_value error_reporting 0
    php_value display_errors 0
    
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    RewriteRule ^v([0-9]{1,2})/(.*?)/(json|xml|serial|raw|html).api$ ./index.php?version=$1&whois=$2&output=$3 [L,NC,QSA]

## Scheduled Cron Job Details.,
    
There is one or more cron jobs that is scheduled task that need to be added to your system kernel when installing this API, the following command is before you install the chronological jobs with crontab in debain/ubuntu
    
    Execute:-
    $ sudo crontab -e

### CronTab Entry:
    
    
## Licensing

 * This is released under General Public License 3 - GPL3 - Only!

# Installation

Copy the contents of this archive/repository to the run time environment, configue apache2, ngix or iis to resolve the path of this repository and run the HTML Installer.
