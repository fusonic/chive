Announcement (July 2014): Chive is no longer actively developed nor maintained
=====
When chive was build back in 2009, we were very dissatisfied with the existing tools available.
One of the most important features of chive was the ability to inline-edit mysqldata and to quickly search through the list of tables in a nice and fast gui. Another goal was to provide a database tool which is build on a well architectured codebase. This was 2009 and chive was a really good replacement for phpmyadmin.

Since 2009 a lot has changed in the web-community. Lots of new javascript and css frameworks were born (e.x. AngularJS, Boostrap, ...) and Symphony has become the leading php framework. Chive was build on Yii-Framework 1.4 + Jquery with custom CSS. These stack does not fullfill the requirements for a modern state of the art, maintaineable codebase you would expect in 2014.

Rebuilding chive on top of these state of-the-art technolgies available today, would require a vast ammount of work. Unfortunately we can no longer fund these further developemnt and we can no longer fund the support of the existing codebase.

The source code of chive will still be available public, but we will no longer respond to pull requests nor issues/questions.

I want to thank all of our users which used chive and or supported it by bringin in pull-requests, translations and other utilities around it.


David

--------------


Original Readme:
=====

Chive is a modern Open-Source MySQL Data Management tool. With it's fast and elaborate user interface it is getting very popular especially by web engineers. Chive was created because of an disaffection with existing tools. They usually were hard to handle and very time-consuming while the daily use of an web engineer.

The first version of chive was shipped in Oktober 2009. Since that, the project is moving forward in various directions:

* Improving features to provide even better user experience
* Implementing missing functionality
* Fixing Bugs and improving stability

Installation on Linux
=====
If you are using Linux, there is a very simple way to install Chive. 
Just run the following command in your terminal window to download and extract Chive:

    wget -O - http://www.chive-project.com/Download/Redirect|tar -xzp

This command will download the latest version of Chive and extract it into a directory named "chive". 
All relevant file/directory privileges are stored in the tarball.


Author
=====
Chive is sponsored by Fusonic (http://www.fusonic.net)


Licence
=====
Chive is released under the terms of the GNU General Public License v3.
