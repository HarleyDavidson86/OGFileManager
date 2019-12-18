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
		}
		
		public function adminBodyEnd()
		{
			global $L;
			// Load the plugin only in the controllers setted in $this->loadOnController
			if (!in_array($GLOBALS['ADMIN_CONTROLLER'], $this->loadOnController)) {
				return false;
			}
			
			include($this->phpPath().'php/dialog.php');
			
			$html  = '<script>';
			$html .= '$("#jseditorToolbarRight").prepend(\'<button type="button" class="btn btn-light" id="jsDocumentManagerOpenModal" data-toggle="modal" data-target="#jsDocumentManagerModal"><span class="fa fa-file"></span>'.$L->g('FileManager').'</button>\')';
			$html .= '</script>';
			return $html;
		}
		
        public function pageBegin() {
			global $page;
			//Replace Patterns with links
            $page->setField('content', $this->replaceLinks($page->content()));
        }
		
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
				$link = '<a href="'.HTML_PATH_UPLOADS.'documents'.DS.$filename.'">'.$name.'</a>';
				$contentOfPage = str_replace($total, $link, $contentOfPage);
			}
			return $contentOfPage;
		}
    }
?>