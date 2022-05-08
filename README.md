# multisite

Multi-site CMS for personal websites

## Motivation

I wanted to host several personal websites using different domains on the same server, without code or database duplication. Here's what I did.

## CMS features (GUI)

* Change layout of your website by changing the site template/design
* Upload individual images (.jpg) or documents (.pdf), to be displayed or hyperlinked on a page
* Upload a set of images (.jpg), to be published as album on a page
* Add and edit individual pages using a rich text editor
* Secure individual pages with an URL password

## Programming language and DBMS

* [PHP 7.4](https://www.php.net)
* [MySQL Community 5.7](https://dev.mysql.com/downloads/mysql/)

## Third-party software and artwork

* [Fat-Free Framework 3.7](https://fatfreeframework.com/3.7/)
* [TinyMCE 3.5.12](https://www.tiny.cloud/docs-3x/reference/for-dummies/)
* [Green Bitcons Icons](https://www.softicons.com/toolbar-icons/green-bitcons-icons-by-some-random-dude/)

## Demo

* [Demo frontend](http://demo.agnosis.de)
* [Demo backend](http://bcknd.agnosis.de/login.php)

## Local installation

Assuming that you have a webserver, PHP and MySQL installed (see above), and downloaded or cloned this repository:

1. Follow instructions in `app/thirdparty/composer/EXTERNAL.md` and
2. Follow instructions in `www/bcknd/static/js/EXTERNAL.md`
3. Connect to your MySQL Server and run all scripts in `dbdump/` starting with `01.sql`
4. Let a domain or subdomain point to `www/multisite` (shared frontend files for all websites)
5. Let a domain or subdomain point to `www/bcknd` (shared backend for all websites)
6. Let a domain or subdomain point to `www/demo` (demo website)
7. Copy `app/myconf.ini.skel` to `app/myconf.ini`
8. Set configuration values in `app/myconf.ini`
9. On your MySQL server, run ```UPDATE multisite.tblSite SET URL = '(demo domain)' WHERE ID = 1```, where demo domain is the domain configured in step 6
10. Open the domain configured in step 6 in your browser to access the demo website
11. Open the domain configured in step 5 in your browser to edit your website using user name `demo` and password `showme`

