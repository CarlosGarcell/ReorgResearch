#!/bin/bash

## -----------------------------------------
## Import laravel/homestead to local project
## -----------------------------------------
echo '---------------------------------------------------'
echo '*** Importing laravel/homestead to local project ***'
echo '---------------------------------------------------'
sudo `which composer` require laravel/homestead

echo '---------------------------------------------------'
echo '*** Initializing Homestead setup ***'
echo '---------------------------------------------------'
sudo which php | vendor/bin/homestead make

## -------------------------------------------------------
## Modify app name from homestead.app to reorgresearch.app
## -------------------------------------------------------
echo '----------------------------------------------------------------'
echo '*** Modifying app name from homestead.app to reorgresearch.app'
echo '----------------------------------------------------------------'
sudo sed -i.yaml 's/homestead.app/reorgresearch.app/g' Homestead.yaml

## -----------------------------------------------
## Modify DB name from homestead to reorgresearch.
## -----------------------------------------------
echo '-----------------------------------------------------------'
echo '*** Modifying DB name from homestead to reorgresearch ***'
echo '-----------------------------------------------------------'
sudo sed -i.yaml 's/- homestead/- reorgresearch/g' Homestead.yaml

## ------------------------------------
## Remove backup file generated by sed
## ------------------------------------
echo '-----------------------------------------------'
echo '*** Removing backup file generated by sed ***'
echo '-----------------------------------------------'
sudo rm Homestead.yaml.yaml

## -----------------------------------------
## Bootup Vagrant machine
## -----------------------------------------
echo '------------------------------------'
echo '*** Booting up Vagrant machine ***'
echo '------------------------------------'
vagrant up

## -----------------------------------------
## SSH into Vagrant machine
## -----------------------------------------
echo '----------------------------------'
echo '*** SSH into Vagrant machine ***'
echo '----------------------------------'
vagrant ssh