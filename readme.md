# ReorgResearch Case Study

On this file you will be able to find the instructions as to how to setup the application.

We're assuming you have already installed Composer, Vagrant and VirtualBox, so we're skipping straight to setting up the app.

In case you haven't you won't be able to proceed the installation!!

Please refer to the following sites on how to install each one of teh techonologies mentioned above.

- Composer: https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx
- Vagrant: https://www.vagrantup.com/downloads.html
- VirtualBox: https://www.virtualbox.org/wiki/Downloads

1. Clone the repository into your local machine via 'git clone https://github.com/CarlosGarcell/ReorgResearch.git'
2. Once the repository has been cloned, 'cd' into the folder (it should be ReorgResearch) and execute 'sh initApp.sh' to begin the bootstrapping process.
3. Once the previous script is done, you'll find yourself inside the virtual machine, 'cd' into reorgresearch (cd reorgresearch/) and run 'sh initVagrantMachine.sh'. When prompted whether you want to install Sphinx, type 'y' and press enter.
4. Done! Go to your browser, type localhost:8000 and you should be able to see the app initial screen.

NOTE: If you do not wish to use the app via localhost:8000, follow through the following steps:

a. Go into the cloned git repository and open the Homestead.yaml file. Write down the IP Address at the very top of the file.
b. Edit you /etc/hosts file and add the following: IP_FROM_HOMESTEAD_FILE reorgresearch.app. Save it!
c. Provision your vagrant machine by issuing the 'vagrant provision' command from within the repo folder. Once done, go to your browser, type reorgresearch.app and you should be able to see the intial screen.
d. If the previous step didn't work, stop your vagrant machine (by issuing the 'vagran halt' commmand) and restart it again (by issuing the 'vagrant up' command).