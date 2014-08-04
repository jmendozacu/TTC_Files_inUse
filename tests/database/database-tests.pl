#!/usr/bin/perl -w
use strict;
use warnings;
use DBI;
use DBD::mysql;
use POSIX;

# Testing dependencies
use Test::More;
use Test::Builder;
use Test::DatabaseRow;

# Turn on autoflush for progress
local $| = 1;

# Define configurations here
my $sourceDatabase = 'magento';
my $sourceHost = 'localhost';
my $sourcePort = 3306;
my $sourceUser = 'root';
my $sourcePass = 'password123';
# and then use the configurations

my $dsn       = "dbi:mysql:$sourceDatabase:$sourceHost:$sourcePort";
our $sourceConn     = DBI->connect($dsn, $sourceUser, $sourcePass) || die $DBI::errstr;
isnt( $sourceConn, undef, "Source database connection is present." );

local $Test::DatabaseRow::dbh = $sourceConn;

# Simple counts
row_ok(sql => "SELECT COUNT(*) AS count FROM catalog_product_entity",
  tests => { '>' => { count => 8800 } },
  label => 'There are more than 8,800 product entity records.');

row_ok(sql => "SELECT COUNT(*) AS count FROM catalog_category_entity",
  tests => { '>=' => { count => 78 } },
  label => 'There are 78 or more category entity records.');

row_ok(sql => "SELECT COUNT(*) AS count FROM admin_user",
  tests => { '>=' => { count => 69 } },
  label => 'There are 69 or more admin users.');

row_ok(sql => "SELECT COUNT(*) AS count FROM cms_block",
  tests => { '>=' => { count => 102 } },
  label => 'There are 102 or more cms blocks.');

row_ok(sql => "SELECT COUNT(*) AS count FROM cms_page",
  tests => { '>=' => { count => 83 } },
  label => 'There are 83 or more cms pages.');

row_ok(sql => "SELECT COUNT(*) AS count FROM catalogrule WHERE is_active = 1",
  tests => { '==' => { count => 0 } },
  label => 'No catalog rules are active.');

# No courses without professors

# No courses without lectures

# Finalize
done_testing();

$sourceConn->disconnect;
