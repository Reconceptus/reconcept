<?php
/**
 * Created by PhpStorm.
 * User: borod
 * Date: 21.08.2018
 * Time: 10:22
 */

/* @var $catalog \modules\shop\models\Catalog */
?>

<div class="catalog-filters--section show-more-parent">
    <div class="catalog-filters--head">
        <h3 class="form-title"><?=$catalog->name?></h3>
        <span class="show-more"></span>
    </div>
    <div class="catalog-filters--main show-more-hidden" style="display: none;">
        <div class="form-row-element">
            <div class="range">
                <div id="keypress" class="range-field"></div>
                <div class="range-inputs">
                    от
                    <input type="text" id="input-with-keypress-0">
                    до
                    <input type="text" id="input-with-keypress-1">
                </div>
            </div>
        </div>
    </div>
</div>
