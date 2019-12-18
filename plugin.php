<?php
    class pluginHello extends Plugin {
		
		private $loadOnController = array(
			'new-content',
			'edit-content'
		);
		
		public function init()
		{
			//Create Documents Folder
			mkdir(PATH_UPLOADS.'documents'.DS, 0755);
			//define constants
			define('OGFM_PATH_DOCUMENTS_REL', HTML_PATH_UPLOADS.'documents'.DS);
			define('OGFM_PATH_DOCUMENTS_ABS', PATH_UPLOADS.'documents'.DS);
		}
		
		public function adminBodyEnd()
		{
			echo '<!-- OGFileManager Start -->';
			global $L;
			// Load the plugin only in the controllers setted in $this->loadOnController
			if (!in_array($GLOBALS['ADMIN_CONTROLLER'], $this->loadOnController)) {
				return false;
			}
			
			include($this->phpPath().'php/dialog.php');
			
			$html .= '<script>';
			$html .= '$("#jseditorToolbarRight").prepend(\'<button type="button" class="btn btn-light" id="jsDocumentManagerOpenModal" data-toggle="modal" data-target="#jsDocumentManagerModal"><span class="fa fa-file"></span>'.$L->g('FileManager').'</button>\');'.PHP_EOL;
			$html .= '$(document).ready(function() {'.PHP_EOL;
			$html .= '    if (typeof editorInsertDocument != "function") {'.PHP_EOL;
			$html .= '        window.editorInsertDocument = function(filename){'.PHP_EOL;
			$html .= '        var placeholder = "DOC{"+filename+";'.$L->g("Insert name of file").';'.$L->g("Insert description").'}";'.PHP_EOL;
			//No Editor-Plugin selected
			$html .= '            $("#jseditor").val($(\'#jseditor\').val()+placeholder);'.PHP_EOL;
			//TinyMCE as editor selected
			$html .= '            if (typeof tinymce !== \'undefined\') {'.PHP_EOL;
			$html .= '                tinymce.activeEditor.insertContent(placeholder);'.PHP_EOL;
			$html .= '            };'.PHP_EOL;
			//Easy MDE as plugin selected
			$html .= '            if (typeof easymde !== \'undefined\') {'.PHP_EOL;
			$html .= '                var text = easymde.value();'.PHP_EOL;
			$html .= '                easymde.value(text + placeholder + "\\n");'.PHP_EOL;
			$html .= '                easymde.codemirror.refresh();'.PHP_EOL;
			$html .= '            };'.PHP_EOL;
			$html .= '        };'.PHP_EOL;
			$html .= '    }'.PHP_EOL;
			$html .= '});'.PHP_EOL;
			$html .= '</script>'.PHP_EOL;
			$html .= '<!-- OGFileManager End -->'.PHP_EOL;
			return $html;
		}
		
        public function pageBegin() {
			global $page;
			//Replace Patterns with links
            $page->setField('content', $this->replaceLinks($page->content()));
        }
		
		/**
		* Replaces all placeholders with configured download button
		*/
		private function replaceLinks($contentOfPage)
		{
			//Pattern to find
			$regex = '/DOC{(?<FILENAME>[a-zA-Z0-9_.]+);(?<NAME>.*);(?<DESCRIPTION>.*)}/';
			preg_match_all($regex, $contentOfPage, $matches, PREG_OFFSET_CAPTURE);
			
			//Replace all matches
			for ($i = 0; $i <= count($matches[0]); $i++) {
				$total = $matches[0][$i][0];
				$filename = $matches["FILENAME"][$i][0];
				$name = $matches["NAME"][$i][0];
				$description = $matches["DESCRIPTION"][$i][0];
				$link  = '<form method="get" action="'.OGFM_PATH_DOCUMENTS_REL.$filename.'">';
				$link .= '<button type="submit" class="ogfm-button">Download</button> '.$name;
				$link .= '</form>';
				$contentOfPage = str_replace($total, $link, $contentOfPage);
			}
			return $contentOfPage;
		}
    }
?>