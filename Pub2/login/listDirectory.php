<?php 	
	include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	include_once($rootlocation.'logFileClass.php');
	include_once($rootlocation."PresenterClass.php");
	include_once($rootlocation."login/dirlistingClass.php");
	
	
	$lf = new logFile();
	$minimumpriv = $upriv['webadmin'];
	//$lf->logMessage("admin.php: before session page minimumpriv: ".$minimumpriv."\n");
	$sp = new sessionPageBrowser($regionandheaderfull,$minimumpriv,$homelandingurlfull[$utype['resident']]);	
	//$lf->logMessage("admin.php: after session page mypriv: ".$sp->getUserPriv()." upriv-webadmin: ".$upriv['webadmin']."\n");

	
	
	
	
	$dl = new dirlisting($rootlocation.$staticcontentloc."dirlisting.htm", $lf);
	
	//error_reporting(0);
	
	if(!function_exists('imagecreatetruecolor')) $dl->setShowThumbnails(false);
	
	$leadon = $startdir;
	if($leadon=='.') $leadon = '';
	if((substr($leadon, -1, 1)!='/') && $leadon!='') $leadon = $leadon . '/';
	$startdir = $leadon;
	
	if($_GET['dir']) {
		//check this is okay.
		
		if(substr($_GET['dir'], -1, 1)!='/') {
			$_GET['dir'] = $_GET['dir'] . '/';
		}
		
		$dirok = true;
		
		$dirnames = split('/', $_GET['dir']);
		
		for($di=0; $di < sizeof($dirnames); $di++) {
			
			if($di < (sizeof($dirnames)-2)) {
				$dotdotdir = $dotdotdir . $dirnames[$di] . '/';
			}
			
			if($dirnames[$di] == '..') {
				$dirok = false;
			}
		}
		
		if(substr($_GET['dir'], 0, 1)=='/') {
			$dirok = false;
		}
		
		if($dirok) {
			 $leadon = $leadon . $_GET['dir'];
		}
	}
	
	echo "<p>******listDirectory.php: made it this far1!</p>";

	$lf->logMessage("listDirectory: finished get dir\n");
	
	if($_GET['download'] && $dl->getForceDownloads()) {
		$file = str_replace('/', '', $_GET['download']);
		$file = str_replace('..', '', $file);
	
		if(file_exists($leadon . $file)) {
			header("Content-type: application/x-download");
			header("Content-Length: ".filesize($leadon . $file)); 
			header('Content-Disposition: attachment; filename="'.$file.'"');
			readfile($leadon . $file);
			die();
		}
	}
	
	$lf->logMessage("listDirectory: finished get download\n");
	
	if($dl->getAllowUploads() && $_FILES['file']) {
		$upload = true;
		if(!$overwrite) {
			if(file_exists($leadon.$_FILES['file']['name'])) {
				$upload = false;
			}
		}
		
		if($upload) {
			move_uploaded_file($_FILES['file']['tmp_name'], $leadon . $_FILES['file']['name']);
		}
	}
	
	$lf->logMessage("listDirectory: finished allow uploads\n");
	
	$opendir = $leadon;
	if(!$leadon) $opendir = '.';
	if(!file_exists($opendir)) {
		$opendir = '.';
		$leadon = $startdir;
	}
	
	clearstatcache();
	
	$lf->logMessage("listDirectory: finished clearstatcache\n");
	
	if ($handle = opendir($opendir)) {
		while (false !== ($file = readdir($handle))) { 
			//first see if this file is required in the listing
			if ($file == "." || $file == "..")  continue;
			$discard = false;
			for($hi=0;$hi<sizeof($hide);$hi++) {
				if(strpos($file, $hide[$hi])!==false) {
					$discard = true;
				}
			}
			
			if($discard) continue;
			if (@filetype($leadon.$file) == "dir") {
				if(!$showdirs) continue;
			
				$n++;
				if($_GET['sort']=="date") {
					$key = @filemtime($leadon.$file) . ".$n";
				}
				else {
					$key = $n;
				}
				$dirs[$key] = $file . "/";
			}
			else {
				$n++;
				if($_GET['sort']=="date") {
					$key = @filemtime($leadon.$file) . ".$n";
				}
				elseif($_GET['sort']=="size") {
					$key = @filesize($leadon.$file) . ".$n";
				}
				else {
					$key = $n;
				}
				$files[$key] = $file;
				
				if($displayindex) {
					if(in_array(strtolower($file), $indexfiles)) {
						header("Location: $file");
						die();
					}
				}
			}
		}
		closedir($handle); 
	}
	
	$lf->logMessage("listDirectory: finished opendir\n");
	
	//sort our files
	if($_GET['sort']=="date") {
		@ksort($dirs, SORT_NUMERIC);
		@ksort($files, SORT_NUMERIC);
	}
	elseif($_GET['sort']=="size") {
		@natcasesort($dirs); 
		@ksort($files, SORT_NUMERIC);
	}
	else {
		@natcasesort($dirs); 
		@natcasesort($files);
	}
	
	$lf->logMessage("listDirectory: finished sort\n");
	
	//order correctly
	if($_GET['order']=="desc" && $_GET['sort']!="size") {$dirs = @array_reverse($dirs);}
	if($_GET['order']=="desc") {$files = @array_reverse($files);}
	$dirs = @array_values($dirs); $files = @array_values($files);
		
	$breadcrumbs = split('/', $leadon);
	if(($bsize = sizeof($breadcrumbs))>0) {
		$sofar = '';
		for($bi=0;$bi<($bsize-1);$bi++) {
			$sofar = $sofar . $breadcrumbs[$bi] . '/';
			$dl->setListingHdrText(' &gt; <a href="'.$_SERVER['PHP_SELF'].'?dir='.urlencode($sofar).'">'.$breadcrumbs[$bi].'</a>');
		}
	}
	
	
	$dl->setBaseURL($_SERVER['PHP_SELF'] . '?dir='.$_GET['dir'] . '&amp;');
	$dl->setFileURL('sort=name&amp;order=asc');
	$dl->setSizeURL('sort=size&amp;order=asc');
	$dl->setDateURL('sort=date&amp;order=asc');
	
	$lf->logMessage("listDirectory: finished set URLs\n");
	
	switch ($_GET['sort']) {
		case 'name':
			if($_GET['order']=='asc') $dl->setBaseURL('sort=name&amp;order=desc');
			break;
		case 'size':
			if($_GET['order']=='asc') $dl->setSizeURL('sort=size&amp;order=desc');
			break;
			
		case 'date':
			if($_GET['order']=='asc') $dl->setDateURL('sort=date&amp;order=desc');
			break;  
		default:
			$dl->setFileURL('sort=name&amp;order=desc');
			break;
	}


	$class = 'b';
	if($dirok) {
	
		$dirup = '<div><a href="'.$_SERVER['PHP_SELF'].'?dir='.urlencode($dotdotdir);?>" class="<?=$class;?>"><img src="dlf/dirup.png" alt="Folder" /><strong>..</strong> <em>-</em>'.date ("M d Y h:i:s A", filemtime($dotdotdir)).'</a></div>';

		if($class=='b') $class='w';
		else $class = 'b';
	}
	
	$arsize = sizeof($dirs);
	for($i=0;$i<$arsize;$i++) {
	$folder .= '<div><a href="'.$_SERVER['PHP_SELF'].'?dir='.urlencode($leadon.$dirs[$i]).'" class="'.$class.'"><img src="dlf/folder.png" alt="'.$dirs[$i].'" /><strong>'.$dirs[$i].'</strong> <em>';
		-</em> <?=date ("M d Y h:i:s A", filemtime($leadon.$dirs[$i]));?></a></div>
	<?
		if($class=='b') $class='w';
		else $class = 'b';	
	}

	
	$arsize = sizeof($files);
	for($i=0;$i<$arsize;$i++) {
		$icon = 'unknown.png';
		$ext = strtolower(substr($files[$i], strrpos($files[$i], '.')+1));
		$supportedimages = array('gif', 'png', 'jpeg', 'jpg');
		$thumb = '';
		
		if($showthumbnails && in_array($ext, $supportedimages)) {
			$thumb = '<span><img src="dlf/trans.gif" alt="'.$files[$i].'" name="thumb'.$i.'" /></span>';
			$thumb2 = ' onmouseover="o('.$i.', \''.urlencode($leadon . $files[$i]).'\');" onmouseout="f('.$i.');"';
			
		}
		
		if($filetypes[$ext]) {
			$icon = $filetypes[$ext];
		}
		
		$filename = $files[$i];
		if(strlen($filename)>43) {
			$filename = substr($files[$i], 0, 40) . '...';
		}
		
		$fileurl = $leadon . $files[$i];
		
		if($forcedownloads) {
			$fileurl = $_SESSION['PHP_SELF'] . '?dir=' . urlencode($leadon) . '&download=' . urlencode($files[$i]);
		}
		/*
		$listlines .= '<div><a href="'.$fileurl.'" class="'.$class.'"'.$thumb2.'><img src="dlf/'.$icon.'" alt="'.$files[$i].'" /><strong>'.$filename.'</strong> <em>'.round(filesize($leadon.$files[$i])/1024).'KB</em> '.date ("M d Y h:i:s A", filemtime($leadon.$files[$i])).$thumb'</a></div>';
		*/
		if($class=='b') $class='w';
		else $class = 'b';	
	}	
	?></div>
	<?
	if($allowuploads) {
		$phpallowuploads = (bool) ini_get('file_uploads');		
		$phpmaxsize = ini_get('upload_max_filesize');
		$phpmaxsize = trim($phpmaxsize);
		$last = strtolower($phpmaxsize{strlen($phpmaxsize)-1});
		switch($last) {
			case 'g':
				$phpmaxsize *= 1024;
			case 'm':
				$phpmaxsize *= 1024;
		}
	
?>
	<div id="upload">
		<div id="uploadtitle"><strong>File Upload</strong> (Max Filesize: <?=$phpmaxsize;?>KB)</div>
		<div id="uploadcontent">
			<?
			if($phpallowuploads) {
			?>
			<form method="post" action="<?=$_SERVER['PHP_SELF'];?>?dir=<?=urlencode($leadon);?>" enctype="multipart/form-data">
			<input type="file" name="file" /> <input type="submit" value="Upload" />
			</form>
			<?
			}
			else {
			?>
			File uploads are disabled in your php.ini file. Please enable them.
			<?
			}
			?>
		</div>
		
	</div>
<?
	}
	$lf->logMessage("listDirectory: preparing to render directory page\n");
	$text = $dl->renderDirectoryScreenText();	
	echo "<p>******listDirectory.php: made it this far!</p>";
	$sp->addBodyContent($text);
	$sp->renderPage();
?>
