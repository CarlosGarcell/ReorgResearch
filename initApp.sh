#!/bin/bash

## -----------------------------------------
## Import laravel/homestead to local project
## -----------------------------------------
`which composer` require laravel/homestead

## -----------------------------------------
## Initialize Homestead setup
## -----------------------------------------
which php | vendor/bin/homestead make

## -----------------------------------------
## Bootup Vagrant machine
## -----------------------------------------
vagrant up