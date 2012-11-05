# BioFieldDescriptions MOD for Nova2

An extension for Anodyne Nova2 RPG System, meant for adding descriptions and instructions to bio form fields.

## Usage
The mod adds descriptions to bio fields. If there are no descriptions, nothing will happen.
If you want to add descriptions, go to your Bio Form editor and edit a field. There will be a new textarea field for "Field Description / Instructions".
Fill out your desired instructions, and save. 

The descriptions will pop-up when a member hovers over the input.

## Version
### Version 1.0
Working extension. 

## Install

Please be careful; this extension overrides two controllers **site.php** and **characters.php** as well as view files.

### 1. Run the SQL
Go into your sql server, and run the database.sql file.
*** WARNING! *** If your database is using a prefix that is different than nova_ for table names, you must edit the SQL file before you run it. **If you don't know what this means, do not install.**

### 2. Copy the Mod

#### 2.1 If the controllers were not changed
It's suggested you back up your views/_base_override/admin/ folder.
After you do that, you can simply upload the mod as-is.

If you want to be careful and manually copy the mod, jump to step 2.3.

#### 2.2 EXTREMELY IMPORTANT NOTICE
**Please read carefully: If your controllers were changed before, you must be careful and follow these instructions**

##### 2.2.1 If site.php controller already has mods:
Look into your edited **site.php** controller. If it does not have ```public function bioform()``` you can jump to step 2.3.
Otherwise, **DO NOT INSTALL THIS CONTROLLER** unless you know how to EDIT a previously-edited function. I added markers in the code if you want to manually add them to your existing changes. But you should really not mess with code if you don't know what this means.

##### 2.2.2 If characters.php controller already has mods:
Look into your edited **characters.php** controller. If it does not have ```public function bio($id = false)``` you can jump to step 2.3.
Otherwise, **DO NOT INSTALL THIS CONTROLLER** unless you know how to EDIT a previously-edited function. I added markers in the code if you want to manually add them to your existing changes. But you should really not mess with code if you don't know what this means.

#### 2.3 Manually Copy the Mod into the Controllers
##### 2.3.1 Controllers
This is mostly a matter of copy/paste. 

Go to the controller files in the mod. Copy everything between the tags below to the prospective controller:
```
	/***************************/
	/*  BIO FORM DESCRIPTIONS  */
	/***************************/
```
and
```
	/*******************************/
	/*  END BIO FORM DESCRIPTIONS  */
	/*******************************/
```

Do this to both characters.php controller and site.php controller.
##### 2.3.2 Copying over View files:
It is highly recommended you back up your views/_base_override/admin folder, as the view file in the mod might override an existing modified version in your nova install.

There are two files in the view folder:
* views/_base_override/admin/pages/site_bioform_one.php
* views/_base_override/admin/js/characters_bio_js.php

If either one of those already exist in your _base_override folder of your nova installation, you will have to manually insert the additions. 
Both view files have the mod-additions marked, so you can just copy/paste them over to your edited files as needed.

However, if these files are not in your nova install, simply upload them over from the mod version.



## Bug Reports
If you find any, or have any special requests, please use the 'issues' tab. Please take into account I'm a graduate student with very little time. I'll be doing my very very best to answer bugs and requests, but be patient with me.

## Credit
This is an extension for Anodyne Nova RPG system. It is absolutely free to use. I'd appreciate it if you keep my name in the credits, and notify me if you create any cool changes or additions so I can add them here.

## Author and Copyright
Moriel Schottlender
mooeypoo@gmail.com
Copyright: [GNU General Public License](http://www.gnu.org/licenses/gpl.txt), 2012



