Secret Santa
============

A Facebook Intergrated PHP run Secret Santa on your own server with a user inputted fall back.
* * *

Requirements
------------
A Simple LAMP Server

Installation
------------

Just Open `resources/config.php` and change the following constants:

1. `$db_Host` -- *Database Host URL. Defaults to localhost*
2. `$db_Name` -- *Database Name*
3. `$db_User` -- *Database Username*
4. `$db_Pass` -- *Database Password for User*
5. `$project_url` -- *Project URL*
6. `$event_start` -- *The time at which users can find their secret santa in seconds since the unix epoch (e.g. 1324346785) visit [here][http://www.epochconverter.com/]*

### Facebook Set Up

Got to [facebook developer apps][https://developers.facebook.com/apps] and create a new application and set the following variables

1. `$facebook_app_id` *The Applications Facebook ID*
2. `$facebook_app_secret` *The Facebooks Application Secret Key*

### Optional Variables

You can change these variable or you could not, it doesn't really matter.

1. `$project_name` *Defaults to Secret Santa*
2. `$twitter_message` *Insert message that will be tweeted by twitter users.*
* * *

Running the Game
----------------

When time has reached the `$event_start` run this file to assign every secret santa to his or her match.

* * *

About
-----

This is a modified version of the Horace Mann Senior Festivus Gifts website located [here][http://festivus.hmseniors.com] and was created by @aramael.