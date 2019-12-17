# OGFileManager
FileManager plugin for bludit to provide downloadable content to the user

## Setup
First of all you have to create a folder in /bl-content/uploads called "documents".
In this folder you can put documents via FTP (at this time).
There is an upload-feature planned in admin-panel.

Then you can put this plugin in the plugins-folder and activate it in adminpanel.

## Set a link in content
This plugin is searching the following pattern in your pages content and replace it with a clickable link:
```
DOC{<FILENAME>;<NAME>;<DESCRIPTION>}
```
So after activating just place a content matching this pattern above, for example:

DOC{Instructions.pdf;Manual;This is the manual for some really cool machine.}

This plugin will replace it with:
```
<a href="http://itwerkstatt.omdriebigs-gspann.de/bl-content/uploads/documents/Instructions.pdf">Manual</a>
```
At this time, the description is not used.

The following features are planned:
- Uploadtool so that you can manage uploaded files over the admin-panel
- Handy function to create a new link
- Customizable template for a nice presentation of link (download-button for example)