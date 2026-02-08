<?php
final class UI{
    public static function progressSingleData(int $percent){
        return 
            '
              <div class="progressBarContainer">
                <div class="progressBarBack">
                    <div class="progressIndicator" data-value='.$percent.'></div>
                </div>
            </div>
            ';
    }
}

