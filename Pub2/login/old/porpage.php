<?php 
	session_start() 
?>
<html>

<!-- #BeginTemplate "Residents.dwt" -->

<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<!-- #BeginEditable "doctitle" -->
<title>Blue Mountain Airpark</title>
<!-- #EndEditable -->
<script language="JavaScript">
<!--
function FP_swapImg() {//v1.0
 var doc=document,args=arguments,elm,n; doc.$imgSwaps=new Array(); for(n=2; n<args.length;
 n+=2) { elm=FP_getObjectByID(args[n]); if(elm) { doc.$imgSwaps[doc.$imgSwaps.length]=elm;
 elm.$src=elm.src; elm.src=args[n+1]; } }
}

function FP_preloadImgs() {//v1.0
 var d=document,a=arguments; if(!d.FP_imgs) d.FP_imgs=new Array();
 for(var i=0; i<a.length; i++) { d.FP_imgs[i]=new Image; d.FP_imgs[i].src=a[i]; }
}

function FP_getObjectByID(id,o) {//v1.0
 var c,el,els,f,m,n; if(!o)o=document; if(o.getElementById) el=o.getElementById(id);
 else if(o.layers) c=o.layers; else if(o.all) el=o.all[id]; if(el) return el;
 if(o.id==id || o.name==id) return o; if(o.childNodes) c=o.childNodes; if(c)
 for(n=0; n<c.length; n++) { el=FP_getObjectByID(id,c[n]); if(el) return el; }
 f=o.forms; if(f) for(n=0; n<f.length; n++) { els=f[n].elements;
 for(m=0; m<els.length; m++){ el=FP_getObjectByID(id,els[n]); if(el) return el; } }
 return null;
}
// -->
</script>
</head>

<body onload="FP_preloadImgs(/*url*/'../../BMAButtons/buttonD.jpg', /*url*/'../../ResidentsBMAButtons/button17.jpg', /*url*/'../../ResidentsBMAButtons/button18.jpg', /*url*/'../../ResidentsBMAButtons/button1A.jpg', /*url*/'../../ResidentsBMAButtons/button1B.jpg', /*url*/'../../ResidentsBMAButtons/button1D.jpg', /*url*/'../../ResidentsBMAButtons/button1E.jpg', /*url*/'../../ResidentsBMAButtons/button20.jpg', /*url*/'../../ResidentsBMAButtons/button21.jpg', /*url*/'../../ResidentsBMAButtons/button23.jpg', /*url*/'../../ResidentsBMAButtons/button24.jpg', /*url*/'../../ResidentsBMAButtons/button13.jpg', /*url*/'../../ResidentsBMAButtons/button14.jpg', /*url*/'../../BMAButtons/buttonA.jpg', /*url*/'../../BMAButtons/buttonB.jpg')">

<table border="0" cellpadding="0" cellspacing="0" width="779" height="708">
	<!-- MSTableType="layout" -->
	<tr>
		<td valign="top">
		<!-- MSCellType="ContentHead" -->
		<!-- #BeginEditable "Header" -->
		<table border="0" width="100%" id="table1" style="border-collapse: collapse">
			<tr>
				<td width="98">
				<img border="0" src="http://www.bluemountainairpark.com/images/symbol50pct.jpg" width="98" height="103"></td>
				<td width="121">
				<img border="0" src="http://www.bluemountainairpark.com/images/aerial.jpg" width="120" height="92"></td>
				<td>
				<table border="0" width="100%" id="table2" style="border-collapse: collapse">
					<tr>
						<td><i><font size="5" face="ST MicroSquare Ex">Blue 
				Mountain Airpark </font><font face="ST MicroSquare Ex">(GE05)</font></i></td>
					</tr>
					<tr>
						<td style="font-size: 8pt; font-family: Arial; color: #FF0000; font-style: italic; font-weight: bold"><?=$_SESSION[loginstatemessage]?></td>
					</tr>
				</table>
&nbsp;</td>
			</tr>
		</table>
<!-- #EndEditable -->
		</td>
		<td height="105">&nbsp;</td>
		</tr>
	<tr>
		<td valign="top" height="50">
		<!-- MSCellType="ContentHead2" -->
		<!-- #BeginEditable "Menu" -->
		<table border="0" width="100%" style="border-collapse: collapse">
			<tr>
				<td>
				<img border="0" id="img7" src="../../ResidentsBMAButtons/button16.jpg" height="20" width="100" alt="Committees" onmouseover="FP_swapImg(1,0,/*id*/'img7',/*url*/'../../ResidentsBMAButtons/button17.jpg')" onmouseout="FP_swapImg(0,0,/*id*/'img7',/*url*/'../../ResidentsBMAButtons/button16.jpg')" onmousedown="FP_swapImg(1,0,/*id*/'img7',/*url*/'../../ResidentsBMAButtons/button18.jpg')" onmouseup="FP_swapImg(0,0,/*id*/'img7',/*url*/'../../ResidentsBMAButtons/button17.jpg')" fp-style="fp-btn: Simple Text 3" fp-title="Committees"> 
		|
		<img border="0" id="img8" src="../../ResidentsBMAButtons/button19.jpg" height="20" width="100" alt="Board Members" onmouseover="FP_swapImg(1,0,/*id*/'img8',/*url*/'../../ResidentsBMAButtons/button1A.jpg')" onmouseout="FP_swapImg(0,0,/*id*/'img8',/*url*/'../../ResidentsBMAButtons/button19.jpg')" onmousedown="FP_swapImg(1,0,/*id*/'img8',/*url*/'../../ResidentsBMAButtons/button1B.jpg')" onmouseup="FP_swapImg(0,0,/*id*/'img8',/*url*/'../../ResidentsBMAButtons/button1A.jpg')" fp-style="fp-btn: Simple Text 3" fp-title="Board Members">|<img border="0" id="img9" src="../../ResidentsBMAButtons/button1C.jpg" height="20" width="100" alt="Initiatives" onmouseover="FP_swapImg(1,0,/*id*/'img9',/*url*/'../../ResidentsBMAButtons/button1D.jpg')" onmouseout="FP_swapImg(0,0,/*id*/'img9',/*url*/'../../ResidentsBMAButtons/button1C.jpg')" onmousedown="FP_swapImg(1,0,/*id*/'img9',/*url*/'../../ResidentsBMAButtons/button1E.jpg')" onmouseup="FP_swapImg(0,0,/*id*/'img9',/*url*/'../../ResidentsBMAButtons/button1D.jpg')" fp-style="fp-btn: Simple Text 3" fp-title="Initiatives">|
				<img border="0" id="img13" src="../../BMAButtons/button9.jpg" height="20" width="100" alt="Documents" onmouseover="FP_swapImg(1,0,/*id*/'img13',/*url*/'../../BMAButtons/buttonA.jpg')" onmouseout="FP_swapImg(0,0,/*id*/'img13',/*url*/'../../BMAButtons/button9.jpg')" onmousedown="FP_swapImg(1,0,/*id*/'img13',/*url*/'../../BMAButtons/buttonB.jpg')" onmouseup="FP_swapImg(0,0,/*id*/'img13',/*url*/'../../BMAButtons/buttonA.jpg')" fp-style="fp-btn: Simple Text 3" fp-title="Documents">|<img border="0" id="img10" src="../../ResidentsBMAButtons/button1F.jpg" height="20" width="100" alt="Airpark Care" onmouseover="FP_swapImg(1,0,/*id*/'img10',/*url*/'../../ResidentsBMAButtons/button20.jpg')" onmouseout="FP_swapImg(0,0,/*id*/'img10',/*url*/'../../ResidentsBMAButtons/button1F.jpg')" onmousedown="FP_swapImg(1,0,/*id*/'img10',/*url*/'../../ResidentsBMAButtons/button21.jpg')" onmouseup="FP_swapImg(0,0,/*id*/'img10',/*url*/'../../ResidentsBMAButtons/button20.jpg')" fp-style="fp-btn: Simple Text 3" fp-title="Airpark Care">|<img border="0" id="img11" src="../../ResidentsBMAButtons/button22.jpg" height="20" width="100" alt="Website Admin" onmouseover="FP_swapImg(1,0,/*id*/'img11',/*url*/'../../ResidentsBMAButtons/button23.jpg')" onmouseout="FP_swapImg(0,0,/*id*/'img11',/*url*/'../../ResidentsBMAButtons/button22.jpg')" onmousedown="FP_swapImg(1,0,/*id*/'img11',/*url*/'../../ResidentsBMAButtons/button24.jpg')" onmouseup="FP_swapImg(0,0,/*id*/'img11',/*url*/'../../ResidentsBMAButtons/button23.jpg')" fp-style="fp-btn: Simple Text 3" fp-title="Website Admin">|<a href="../../logout.php"><img border="0" id="img12" src="../../ResidentsBMAButtons/button12.jpg" height="20" width="100" alt="Logout" fp-style="fp-btn: Simple Text 3" fp-title="Logout" onmouseover="FP_swapImg(1,0,/*id*/'img12',/*url*/'../../ResidentsBMAButtons/button13.jpg')" onmouseout="FP_swapImg(0,0,/*id*/'img12',/*url*/'../../ResidentsBMAButtons/button12.jpg')" onmousedown="FP_swapImg(1,0,/*id*/'img12',/*url*/'../../ResidentsBMAButtons/button14.jpg')" onmouseup="FP_swapImg(0,0,/*id*/'img12',/*url*/'../../ResidentsBMAButtons/button13.jpg')"></a>|</td>
				<td width="10">&nbsp;</td>
			</tr>
		</table>
		<!-- #EndEditable --></td>
		<td valign="top" rowspan="2" width="15">
		<!-- MSCellType="NavBody" -->
		<!-- #BeginEditable "RightSide" -->&nbsp;<!-- #EndEditable --></td>
	</tr>
	<tr>
		<td valign="top" height="553" width="764">
		<!-- MSCellType="ContentBody" -->
		<!-- #BeginEditable "Body" -->
		<table border="0" width="100%" id="table3" style="border-collapse: collapse">
			<tr>
				<td width="338"><font face="Arial" size="2">Welcome to the BMA 
				Property Owner section of our website. Here we have subject 
				areas that are interesting to our Property Owners and our 
				Airpark Residents.</font><p><font face="Arial" size="2">At this 
				website location, our community members can communicate 
					new ideas and gain support for initiatives believed to benefit 
					the future of our community. </font></p>
				<p><font face="Arial" size="2">Generally, it works like this. 
				Initiatives can be proposed here. Other members can make 
				comments including support, opposition and alternative ideas 
				Evolved initiatives with enough 
					support will attract leadership. A supported initiative 
				evolves into a committee with a committee leader and members 
				which manage actions and 
					milestones toward bringing the initiative to fruition. 
				</font></p>
				<p><font face="Arial" size="2">For example, the 
					first and most basic initiative which became a reality at 
					Blue Mountain was the list of&nbsp; Restrictive Covenants, 
					which establish a baseline standard for owner architectural 
				development and property maintenance within the airpark. The 
				Covenants are only a minimal standard and support the spirit, 
				communication, and collaborative approach for new endeavors 
				within our community.&nbsp; At this time, the Covenants are largely 
				managed by the Architectural Committee 
					and regulated by the Board of Directors. </font></p>
				<p><font face="Arial" size="2">The Airpark Care 
					Committee maintains and prioritizes the &quot;Airpark Maintenance 
					Task List&quot;, and lobbies for a fraction of the monthly airpark 
					dues for maintenance and improvements. </font></td>
				<td>&nbsp;</td>
			</tr>
		</table>
		<!-- #EndEditable --></td>
	</tr>
</table>
<!-- #BeginEditable "Footer" -->
<p>&nbsp;</p>
<!-- #EndEditable -->

</body>

<!-- #EndTemplate -->

</html>