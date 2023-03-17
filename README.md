# OGFileManager
FileManager plugin for bludit to provide downloadable content to the user

## Setup
After activating this plugin it will create a folder in /bl-content/uploads called "documents".
You will find a button "FileManager" when editing a page to upload files.

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
<div class="ogfm-wrapper">
  <a class="ogfm-link" href="http://itwerkstatt.omdriebigs-gspann.de/bl-content/uploads/documents/Instructions.pdf">Manual</a>
  <p class="ogfm-description">The Manual of this tool v1</p>
</div>
```

This link can be customized by a css-file called "ogfm-style.css" in your theme-folder.
If there is no file with this name, the plugin will use the default one in the plugin-folder.
