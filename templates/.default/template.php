<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
if(!empty($arResult['USERS'])):?>
    <div class="user_list_block">
        <form method="post">
            <?=bitrix_sessid_post()?>
            <select name="IMPORT" required>
                <option><?=GetMessage('UL_TYPE_IMPORT')?></option>
                <option value="XML"><?=GetMessage('UL_XML_IMPORT')?></option>
                <option value="CSV"><?=GetMessage('UL_CSV_IMPORT')?></option>
            </select>
            <input type="submit" value="<?=GetMessage('UL_GO_IMPORT')?>">
        </form>
        <div class="user_list_table">
            <?foreach ($arResult['USERS'] as $item):?>
                <div class="str">
                    <div class="tr"><?=$item['ID']?></div>
                    <div class="tr"><?=$item['LOGIN']?></div>
                    <div class="tr"><?=$item['SHORT_NAME']?></div>
                </div>
            <?endforeach;?>
        </div>
    </div>
<?endif;?>