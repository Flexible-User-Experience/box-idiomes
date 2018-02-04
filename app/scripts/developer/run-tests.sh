#!/bin/bash

echo "Started at `date +"%T %d/%m/%Y"`"

if [ -z "$1" ]
  then
    ./vendor/phpunit/phpunit/phpunit -c app/
  else
    if [ "$1" = "cc" -o "$1" = "coverage" ]
          then
            if [ "$1" = "cc" ]
              then
                php app/console cache:clear --env=test && ./vendor/phpunit/phpunit/phpunit -c app/
              else
                ./vendor/phpunit/phpunit/phpunit -c app/ --coverage-text
            fi
          else
            echo "Argument error! Available argument options: 'cc' or 'coverage'"
        fi
fi

echo "Finished at `date +"%T %d/%m/%Y"`"
