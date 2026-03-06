<?php require_once "gosterge.php"?>
<?php 

// Oturum yoksa login giriş ekranı çağğır
 if (!$auth->check()):
    $auth->goLoginPage();
 else:
    // die();
    require_once __DIR__."/controllers/c_tarih.php";
    require_once __DIR__."/controllers/c_istisna.php";
    // debug('SESSION',$_SESSION);
    ?>

    
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

<?php endif; ?>