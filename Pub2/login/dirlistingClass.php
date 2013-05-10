<?
/*
Directory Listing Script - Version 2
====================================
Script Author: Ash Young <ash@evoluted.net>. www.evoluted.net
Layout: Manny <manny@tenka.co.uk>. www.tenka.co.uk

REQUIREMENTS
============
This script requires PHP and GD2 if you wish to use the 
thumbnail functionality.

INSTRUCTIONS
============
1) Unzip all files 
2) Edit this file, making sure everything is setup as required.
3) Upload to server
4) ??????
5) Profit!

CONFIGURATION
=============
Edit the variables in this section to make the script work as
you require.

*/

Class dirlisting {
	var $htmlpage;
	var $startdir;
	var $showthumbnails;
	var $thisthumbnailtext;
	var $showdirs;
	var $forcedownloads;
	var $hide;
	var $displayindex;
	var $allowuploads;
	var $overwrite;
	var $indexfiles;
	var $filetypes;
	var $lf;
	var $mescript;
	var $dirname;
	var $leadon;
	var $baseurl;
	var $fileurl;
	var $sizeurl;
	var $dateurl;
	var $listlines;

	function dirlisting($htmlpage,$lf) {
	
		//include_once($_SERVER['DOCUMENT_ROOT'].'/logFileClass.php');
		$this->lf = $lf;

		$this->lf->logMessage("dirlisting class instantiated\n");
	
		$this->htmlpage = $htmlpage;
		$this->setStartDir();
		$this->setShowThumbnails();
		$this->setShowDirs();
		$this->setForceDownloads();
		$this->setHideFiles();
		$this->showIndexFiles();
		$this->setAllowUploads();
		$this->overwriteFiles();
		$this->indexFilesList();
		$this->fileIconsList();
		$this->setDirName();
		$this->renderDirectoryScreenText();
		
	}
	
	function setBaseURL($baseurl) {
		$this->baseurl = $baseurl;
	}
	
	function setFileURL($fileurl) {
		$this->fileurl = $fileurl;
	}
	
	function setSizeURL($sizeurl) {
		$this->sizeurl = $sizeurl;
	}
	
	function setDateURL($dateurl) {
		$this->dateurl = $dateurl;
	}
		
	function setDirName($leadon='') {
		$this->leadon = $leadon;
		$this->dirname = dirname($_SERVER['PHP_SELF']).'/'.$leadon;
	}

	function setListingHdrText($listinghdrtext) {
		$this->listinghdrtext .= $listinghdrtext;
	}
	
	function setListLines($listlines) {
		$this->listlines = $listlines;
	}


	function setStartDir($startdir=".") {
	/*
		Start Directory - To list the files contained within the current 
		directory enter '.', otherwise enter the path to the directory 
		you wish to list. The path must be relative to the current 
		directory.
	*/
		$this->startdir = $startdir;
	}

	function setShowThumbnails($showthumbnails=false) {
		/*
		Show Thumbnails? - Set to true if you wish to use the 
		scripts auto-thumbnail generation capabilities.
		This requires that GD2 is installed.
		*/
		$this->showthumbnails = $showthumnails; 
		if ($showthumnails) {
			$this->thumbnailtext = <<<END
		
			<script language="javascript" type="text/javascript">
			<!--
			function o(n, i) {
				document.images['thumb'+n].src = 'dlf/i.php?f='+i;
			
			}
	
			function f(n) {
				document.images['thumb'+n].src = 'dlf/trans.gif';
			}
			//-->
			</script>
END;
		}

	}

	function setShowDirs($showdirs=true) {
		/*
		Show Directories - Do you want to make subdirectories available?
		If not set this to false
		*/
		$this->showdirs = $showdirs;
	}

	function setForceDownloads($forcedownloads=false) {
	
		/* 
		Force downloads - Do you want to force people to download the files
		rather than viewing them in their browser?
		*/
		$this->forcedownloads = $forcedownloads;
	}
	
	function getForceDownloads() {
		return $this->forcedownloads;
	}

	function setHideFiles($hide = array(
				'dlf',
				'index.php',
				'Thumbs',
				'.htaccess',
				'.htpasswd'
			) ) {

			/*
			Hide Files - If you wish to hide certain files or directories 
			then enter their details here. The values entered are matched
			against the file/directory names. If any part of the name 
			matches what is entered below then it is now shown.
			*/
		$this->hide = $hide;
	}
	
	function showIndexFiles($displayindex=false) {
			 
			/* 
			Show index files - if an index file is found in a directory
			to you want to display that rather than the listing output 
			from this script?
			*/			
			$this->displayindex = $displayindex;
	}
	
	function setAllowUploads($allowUploads=false) {

			/*
			Allow uploads? - If enabled users will be able to upload 
			files to any viewable directory. You should really only enable
			this if the area this script is in is already password protected.
			*/
			$this->allowuploads = $allowUploads;
	}
	
	function getAllowUploads() {
		return $this->allowuploads;
	}
	
	function overwriteFiles($overwrite=fales) {

			/*
			Overwrite files - If a user uploads a file with the same
			name as an existing file do you want the existing file
			to be overwritten?
			*/
			$this->overwrite = $overwrite;
	}
	
	function indexFilesList($indexfiles = array (
						'index.html',
						'index.htm',
						'default.htm',
						'default.html'
					) ) {							
			/*
			Index files - The array contains all the index files
			that will be used if showIndexFiles() (above) is set to true.
			Feel free to add, delete or alter these
			*/
			$this->indexfiles = $indexfiles;
	}

	function fileIconsList($filetypes = array (
							'png' => 'jpg.gif',
							'jpeg' => 'jpg.gif',
							'bmp' => 'jpg.gif',
							'jpg' => 'jpg.gif', 
							'gif' => 'gif.gif',
							'zip' => 'archive.png',
							'rar' => 'archive.png',
							'exe' => 'exe.gif',
							'setup' => 'setup.gif',
							'txt' => 'text.png',
							'htm' => 'html.gif',
							'html' => 'html.gif',
							'fla' => 'fla.gif',
							'swf' => 'swf.gif',
							'xls' => 'xls.gif',
							'doc' => 'doc.gif',
							'sig' => 'sig.gif',
							'fh10' => 'fh10.gif',
							'pdf' => 'pdf.gif',
							'psd' => 'psd.gif',
							'rm' => 'real.gif',
							'mpg' => 'video.gif',
							'mpeg' => 'video.gif',
							'mov' => 'video2.gif',
							'avi' => 'video.gif',
							'eps' => 'eps.gif',
							'gz' => 'archive.png',
							'asc' => 'sig.gif',
						) ) {

				/*
				File Icons - If you want to add your own special file icons use 
				this section below. Each entry relates to the extension of the 
				given file, in the form <extension> => <filename>. 
				These files must be located within the dlf directory.
				*/

				$this->filetypes = $filetypes;
	}
							

	function renderDirectoryScreenText() {

		$this->lf->logMessage("dirlisting: Rendering Screen as text\n");	
	
		ob_start();
		
		
		include($this->htmlpage);
		$buffer = ob_get_contents();
		ob_end_clean();
		return ($buffer);
	}
}

?>
