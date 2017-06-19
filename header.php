<?php 
include 'content/header_images.php';

function buildMenuArray(){
	$myArray = array();
	intiateDatabaseConnection();
	$SQL = "SELECT * FROM `mcms_menu` ORDER BY `mcms_menu`.`menu_id` ASC";
	$result = mysql_query($SQL);
	$myCount = 0;
	if( mysql_num_rows($result) > 0){
		while($row = mysql_fetch_assoc($result)) {
			if($row["page_id"] > 0){
				$myArray[$myCount] = getPageMenuforID($row["page_id"]);
				$myArray[$myCount + 1] = getPageNameforID($row["page_id"]);
				$myArray[$myCount + 2] = getPageNameforID($row["page_id"]);
			}
			else{
				$myArray[$myCount] = $row["display_text"];
				$myArray[$myCount + 1] = $row["URL"];
				$myArray[$myCount + 2] = $row["URL"];
			}
			$myCount+=3; 
		}
	}
	closeDatabaseConnection();
	return $myArray;
}

$MenuItemArray = buildMenuArray();
if($loggedin == true){
		echo'<div class="admin-menu-bar">
		<div class="admin-desktop-menu">
        	<ul>
			<li><a href="http://' . $baseURL .'Admin/Dashboard"><button>Dashboard</button></a></li>
			<li><a href="http://' . $baseURL .'Admin/Dashboard"><button>Page Editor</button></a></li>
			<li><a href="http://' . $baseURL .'Admin/Dashboard/?dt=1"><button>Post Editor</button></a></li>
			<li><FORM NAME ="logout" METHOD ="POST" ACTION = "">
			<a><button TYPE = "Submit" Name = "logout">Log Out</button></a>
			</FORM></li>
			</ul>
		</div></div>';
}
?>
<div class="navigation-bar">

	<div class="desktop-menu-container">
    	
        <div class="split">
        <div class="split-1-2-S split-top mobile-66">
        
    	<?php
		$MenuItemArray[1] = "";
		$myMenuURL = "http://" . $baseURL . $MenuItemArray[1];
		echo '<a href="' . $myMenuURL . '"><h1 class="website-name-title">' . $SiteName . '</h1></a>';
		?>
        
        </div>
        <div class="split-1-2-S split-top mobile-33">
    
    	<div class="desktop-menu">
        	<ul>
            <?php
			for($i = 0; $i < count ($MenuItemArray); $i += 3){
				if (!strpos($MenuItemArray[$i + 1], '://')) {
					$myMenuURL = "http://" . $baseURL . str_replace(" ", "-",$MenuItemArray[$i + 1]);
				}
				else{
					$myMenuURL = $MenuItemArray[$i + 1];
				}
				if($pageTitle != $MenuItemArray[$i + 2]){
					echo '<a href="' . $myMenuURL . '"><li class="desktop-menu-item">' . $MenuItemArray[$i] . '</li></a>';
				}
				else{
					echo '<a href="' . $myMenuURL . '"><li class="desktop-menu-item desktop-current-menu-item">' . $MenuItemArray[$i] . '</li></a>';
				}
			}
			?>
            </ul>
        </div>
        
        <div onclick="toggleMobileMenu()" class="mobile-button">
        	<i class="fa fa-bars" aria-hidden="true"></i>
        </div>
        
        </div>
        </div>
        
        
        <div id="mobile-menu" class="mobile-menu" style="height:0px;">
        	<hr class="thicker-line" />
        	<ul>
            <?php
			for($i = 0; $i < count ($MenuItemArray); $i += 3){
				$myMenuURL = "http://" . $baseURL . $MenuItemArray[$i + 1];
				if($pageTitle != $MenuItemArray[$i + 2]){
					echo '<a href="' . $myMenuURL . '"><li class="desktop-menu-item">' . $MenuItemArray[$i] . '</li></a>';
				}
				else{
					echo '<a href="' . $myMenuURL . '"><li class="desktop-menu-item desktop-current-menu-item">' . $MenuItemArray[$i] . '</li></a>';
				}
				if($i < count ($MenuItemArray) - 3){
					echo '<hr>';
				}
			}
			?>
            </ul>
        </div>
        
        
    </div>
    
</div>

<?php
include 'slidergenerator.php';
intiateDatabaseConnection();
$pageID = getPageIDforName($pageTitle);
$headerstate = getPageHeaderStateforID($pageID);
//0 no header, 1 single image, 2 slider 

if($headerstate == 0){
	echo'<div class="header-container">';
	echo '<div class="header-line">';
	echo'</div>';
}
else if($headerstate == 1){
	$headerSRC = "http://" . $baseURL . "images/" . getPageHeaderforID($pageID);
	?>
	<div class="header-main">
	<div class="header-container">
	<img src="<?php echo $headerSRC; ?>"/>
	</div>
	</div>
    <?php
}
else if($headerstate == 2){
	echo generateSlider($baseURL, getPageHeaderforID($pageID));
}

closeDatabaseConnection();
?>

