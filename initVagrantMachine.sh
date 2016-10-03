#!/bin/bash

## --------------------------------------------
## Install sphinxsearch
## --------------------------------------------
echo '--------------------------------------------'
echo '*** Install Sphinx ***'
echo '--------------------------------------------'
sudo apt-get install sphinxsearch

## --------------------------------------------
## Create folder /etc/sphinxsearch
## --------------------------------------------
echo '--------------------------------------------'
echo '*** Creating folder /etc/sphinxsearch ***'
echo '--------------------------------------------'
sudo mkdir -p /etc/sphinxsearch ; sudo cp /home/vagrant/reorgresearch/config/sphinx.conf /etc/sphinxsearch

## ----------------------------------------------------
## Create folder /var/lib/sphinxsearch/reorgresearch
## ----------------------------------------------------
echo '----------------------------------------------------------'
echo '*** Creating folder /var/lib/sphinxsearch/reorgresearch ***'
echo '----------------------------------------------------------'
sudo mkdir -p /var/lib/sphinxsearch/reorgresearch

## --------------------------------------------
## Create folder /var/lib/sphinxsearch/old
## --------------------------------------------
echo '------------------------------------------------'
echo '*** Creating folder /var/lib/sphinxsearch/old ***'
echo '------------------------------------------------'
sudo mkdir -p /var/lib/sphinxsearch/old

## --------------------------------------------
## Create folder /var/run/sphinxsearch
## --------------------------------------------
echo '--------------------------------------------'
echo '*** Creating folder /var/run/sphinxsearch ***'
echo '--------------------------------------------'
sudo mkdir -p /var/run/sphinxsearch

## --------------------------------------------
## Run Artisan migrations
## --------------------------------------------
echo '---------------------------------------------------------------'
echo '*** Initializing DB tables via php artisan migrate command ***'
echo '---------------------------------------------------------------'
cd /home/vagrant/reorgresearch ; sudo php artisan migrate

## --------------------------------------------
## Initialize Sphinx indexer
## --------------------------------------------
echo '-------------------------------------------------------'
echo '*** Initialize Sphinx indexer ***'
echo '-------------------------------------------------------'
sudo indexer --all

## --------------------------------------------
## Initialize searchd process
## --------------------------------------------
echo '-------------------------------------------------------'
echo '*** Initialize searchd process ***'
echo '-------------------------------------------------------'
sudo searchd

## -------------------------------------------------
## Modifying permissions for /var/log/sphinxsearch
## -------------------------------------------------
echo '-------------------------------------------------------'
echo '*** Modifying permissions for /var/log/sphinxsearch ***'
echo '-------------------------------------------------------'
cd /var/log ; sudo chmod -R 777 sphinxsearch

## -------------------------------------------------
## Modifying permissions for /var/run/sphinxsearch
## -------------------------------------------------
echo '-------------------------------------------------------'
echo '*** Modifying permissions for /var/run/sphinxsearch ***'
echo '-------------------------------------------------------'
cd /var/run ; sudo chmod -R 777 sphinxsearch