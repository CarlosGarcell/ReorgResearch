# ReorgResearch Case Study

On this file you will be able to find the instructions as to how to setup the application.

We're assuming you have already installed Composer, Vagrant and VirtualBox, so we're skipping straight to setting up the app.

In case you haven't you won't be able to proceed the installation!!

Minimum Requirements:
- PHP v5.6.4 (anything lower than this version will not allow Laravel 5.3 to be installed)
- Vagrant v1.8.6 (Latest released version)
- VirtualBox v4.3.36
- Composer 1.2.1

Please refer to the following sites on how to install each one of teh techonologies mentioned above.
- Update PHP to v7.0: curl -s http://php-osx.liip.ch/install.sh | bash -s 7.0
- Vagrant: https://www.vagrantup.com/downloads.html
- VirtualBox: https://www.virtualbox.org/wiki/Downloads
- Composer: https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx

1. Clone the repository into your local machine via 'git clone https://github.com/CarlosGarcell/ReorgResearch.git'
2. Once the repository has been cloned, 'cd' into the folder (it should be ReorgResearch) and execute 'sh initApp.sh' to begin the bootstrapping process.
3. Once the previous script is done, you'll find yourself inside the virtual machine, 'cd' into reorgresearch (cd reorgresearch/) and run 'sh initVagrantMachine.sh'. When prompted whether you want to install Sphinx, type 'y' and press enter.
4. Done! Go to your browser, type localhost:8000 and you should be able to see the app initial screen.

*** WARNING ***: Both *.sh files are meant to be ran only once, the very first time you're bootstrapping the app. Running them repeatedly may result in Terminal errors.

NOTE: If you do not wish to use the app via localhost:8000, follow through the following steps:

1. Go into the cloned git repository and open the Homestead.yaml file. Write down the IP Address at the very top of the file.
2. Edit you /etc/hosts file and add the following: IP_FROM_HOMESTEAD_FILE reorgresearch.app. Save it!
3. Provision your vagrant machine by issuing the 'vagrant provision' command from within the repo folder. Once done, go to your browser, type reorgresearch.app and you should be able to see the intial screen.
4. If the previous step didn't work, stop your vagrant machine (by issuing the 'vagran halt' commmand) and restart it again (by issuing the 'vagrant up' command).

App Functionality:

The very first time you boot the app, you will come across the following: 2 buttons (Import Data, Search), an input box in which to put your search keywords and a label with the number of records in the DB.
Since there will be no records inside the DB, if you try to search, you will get an error letting you know that "No records were found".

The 'Import Data' button, as its name suggest, will import data into the database (in this case, from the Open Payments System API). 
In this case, we're hard setting the app to download 3000 records per request.

The 'Search' button will perform a search agains the database and return up to a thousand (1000) records that match the words you've searched for.
An internal algorithm will sort and rank them to return the ones that match your search criteria the closest.

Once a search request has matched at least one record, an 'Export Excel' button will be enabled for you to export the results.
These results will be exported to the storage/excel/exports directory inside your app. They will be exported in XLS format. (Everytime you search, the dataset to export will change)