#! /bin/bash

if ! type "sass" > /dev/null;
then
  echo "SASS is not installed. Installing..."
  sudo gem install sass
fi
