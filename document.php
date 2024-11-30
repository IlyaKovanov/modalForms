<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<section id="modal-document-creating" class="modal">
    <button class="modal__overlay" type="button"><span class="visually-hidden">Закрыть окно.</span></button>
    <div class="modal__block">
        <section class="write-us">
            <form id="newDocument" class="site-form" method="POST" enctype="multipart/form-data">
                <h2 class="modal__title">
                    Добавление справочной информации
                </h2>
                <div class="form-grid">
                    <p class="form-grid__item">
                        <label class="site-form__label">
                            <input class="site-form__input site-form__ready" type="text"
                                   placeholder="" required name="NAME" value="<?=$arResult["ELEMENT"]["NAME"]?>">
                            <span class="site-form__text">Название</span>
                        </label>
                    </p>
                    <p class="form-grid__item">
                        <label class="site-form__label site-form__label--select">
                            <select id="document__select" class="js-select2 site-form__select" name="HOUSE" multiple required>
                                <option value="0"></option>
                                <?foreach($arResult["HOUSE"] as $key=>$house){?>
                                    <option value="<?=$house["ID"]?>" <?=$arResult["ELEMENT"]["PROP"]["HOUSE"]["VALUE"] && in_array($house["ID"], $arResult["ELEMENT"]["PROP"]["HOUSE"]["VALUE"])  ? 'selected' : ''?>><?=$house["PROPERTY_ADDRESS_VALUE"]?></option>
                                <?}?>
                            </select>
                            <span class="site-form__text">Выберите адрес</span>
                        </label>
                    </p>  
                    <section class="appeal__files">
                        <?if(is_array($arResult["ELEMENT"]["PROP"]["DOCUMENT"]["VALUE"])){?>
                            <ul class="appeal__files-list">
                                <?foreach($arResult["ELEMENT"]["PROP"]["DOCUMENT"]["VALUE"] as $document){
                                    $arFile = CFile::GetFileArray($document);?>
                                    <li class="appeal__files-item">
                                        <a href="<?=$arFile["SRC"]?>">
                                        <p class="doc doc--delete">
                                            <span class="doc__body">
                                                <span class="doc__name">
                                                    <?=$arFile["ORIGINAL_NAME"]?>
                                                    
                                                </span>
                                                <span class="doc__size"><?=CFile::FormatSize($arFile["FILE_SIZE"])?></span>
                                            </span>
                                            <button class="doc__delete">
                                                <span class="visually-hidden" type="button">Удалить документ.</span>
                                            </button>
                                        </p>
                                        </a>
                                    </li>
                                <?}?>
                            </ul>
                        <?}?>
                        <section class="appeal__add-files">
                            <label class="site-form__file-big-label">
                                <input type="file" class="site-form__file-input js-file" multiple="multiple"
                                    data-browse="Добавить файл" data-placeholder="" name="DOCS[]">
                            </label>
                        </section>
                    </section>
                    <ul class="form-grid__button-list">
                        <li class="form-grid__button-item">
                            <button class="btn" type="submit"><?=$arResult["ELEMENT"] ? 'Изменить' : 'Создать'?></button>
                        </li>
                    </ul> 
                </div>
                <input type="hidden" name="ID" value="<?=$arResult["ELEMENT"]["ID"]?>">
            </form>
        </section>
        <button class="modal__closer" type="button">Закрыть окно</button>
    </div>
</section>


