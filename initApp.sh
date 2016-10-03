#!/bin/bash

## -----------------------------------------
## Import laravel/homestead to local project
## -----------------------------------------
`which composer` require laravel/homestead

## -----------------------------------------
## Initialize Homestead setup
## -----------------------------------------
which php | vendor/bin/homestead make

## -------------------------------------------------------
## Modify app name from homestead.app to reorgresearch.app
## -------------------------------------------------------
sed 's/homestead.app/reorgresearch.app/g' Homestead.yaml

## -----------------------------------------------
## Modify DB name from homestead to reorgresearch.
## -----------------------------------------------
sed 's/- homestead/- reorgresearch/g' Homestead.yaml 

## -----------------------------------------
## Bootup Vagrant machine
## -----------------------------------------
vagrant up

## -----------------------------------------
## SSH into Vagrant machine
## -----------------------------------------
vagrant ssh ; cd reorgresearch