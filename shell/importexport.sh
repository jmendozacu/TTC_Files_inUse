#!/bin/bash

DIR_WITH_IMPORTFILES=$1
ENTITY=$2

for x in `find $DIR_WITH_IMPORTFILES -type f -not -name "*processed.csv" -not -name ".DS*"`
do
	echo "Executing php importexport.php "$x' '$ENTITY
	#exit
	php importexport.php $x $ENTITY
done
