<?php 
require_once __DIR__."/controllers/tarih.php";
require_once __DIR__."/controllers/istisna.php";
?>

<?php require_once "gosterge.php"?>
 
<div class="row">
        <div class="border padding-small">
            <?php 
            if( isset($_GET["asama"]) ):
                require_once __DIR__."/asama/". $_GET["asama"]. ".php";
            else:
                require_once  __DIR__. "/default.php";
            endif;
            ?>
        </div>
</div>

<?php //debug($_SESSION); ?>