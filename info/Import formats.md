General requirements
====================

All import files are in CSV (comma separated values) format in UTF-8 encoding without BOM (bite order mark). Header is required; the order of columns does not matter.

Prices
======

> This is a format proposal current implementation is a little bit different.

We expect from DAX following CSV files for price import:

    prices/
        default.csv
        list.csv
        priority.csv

default.csv & list.csv
----------------------

* website — website code: base, uk or au;
* sku — product's ID;
* price — product's price in currency of website;
* currency — ISO 4217 currency code of price (for validation).

All fields are required.

priority.csv
------------

* website — website code: base, uk or au;
* catalog_code — catalog code;
* sku — product's ID;
* price — product's price in currency of website;
* shipping_price — flat rate for shipping in currency of website;
* currency — ISO 4217 currency code of price (for validation).

All fields are required.

Ad codes
========

> This is a format proposal current implementation is a little bit different.

For ad codes we expect from DAX file `ad_codes.csv` in the FTP server root with following columns.

* ad_code — ad code (required);
* catalog_code — catalog code of ad code (required);
* name — name;
* description — description;
* is_active — 1 if active 0 otherwise (required).

All fields are required.