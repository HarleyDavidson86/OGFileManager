# OGFileManager
FileManager plugin for bludit to provide downloadable content to the user

## Setup
After activating this plugin it will create a folder in /bl-content/uploads called "documents".
In this folder you can put documents via FTP (at this time).
There is an upload-feature planned in admin-panel.

## Set a link in content
This plugin is searching the following pattern in your pages content and replace it with a clickable link:
```
DOC{<FILENAME>;<NAME>;<DESCRIPTION>}
```
So after activating there is a new button next to the images-button in the admin-panel.
Click on it and you will see all uploaded files in the documents-directory.
After clicking on "+Insert" you will see a new line in your editor:

DOC{Instructions.pdf;Manual;This is the manual for some really cool machine.}

This plugin will replace it with:
```
<a href="http://itwerkstatt.omdriebigs-gspann.de/bl-content/uploads/documents/Instructions.pdf">Manual</a>
```
At this time, the description is not used.

The following features are planned:
- Uploadtool so that you can manage uploaded files over the admin-panel
- Customizable template for a nice presentation of link (download-button for example)