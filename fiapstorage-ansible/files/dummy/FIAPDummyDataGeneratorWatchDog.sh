#!/bin/sh

if [ "`/bin/ps auxwww | /bin/grep FIAPDummyDataGenerator | /bin/grep php`" = "" ]; then
   /usr/bin/php /usr/local/dummy/FIAPDummyDataGenerator.php > /dev/null 2> /dev/null < /dev/null & 
fi
