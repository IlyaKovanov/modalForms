<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<section id="modal-spravochnik-creating" class="modal">
    <button class="modal__overlay" type="button"><span class="visually-hidden">Закрыть окно.</span></button>
    <div class="modal__block">
        <section class="write-us">
            <form id="newSpravochnik" class="site-form" method="POST">
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
                    <div id="contactsRow">
                        <?
                        if(is_array($arResult["ELEMENT"]["PROP"]["PHONE"]["VALUE"])) {
                            $countPhone = count($arResult["ELEMENT"]["PROP"]["PHONE"]["VALUE"]);
                        } 
                        if(is_array($arResult["ELEMENT"]["PROP"]["EMAIL"]["VALUE"]) ){
                            $countEmail = count($arResult["ELEMENT"]["PROP"]["EMAIL"]["VALUE"]);
                        }

                        $maxCount = max($countPhone, $countEmail);
                        $count = $maxCount ?? 1;
                        ?>
                        <?for($i=0; $i<=($count-1); $i++){?>
                            <ul class="custom__list">
                                <li>
                                    <label class="site-form__label">
                                        <input class="site-form__input site-form__ready" type="text"
                                            placeholder="" required name="PHONE[]" value="<?=$arResult["ELEMENT"]["PROP"]["PHONE"]["VALUE"][$i]?>">
                                        <span class="site-form__text">Телефон</span>
                                    </label>
                                </li>
                                <li>
                                    <label class="site-form__label">
                                        <input class="site-form__input site-form__ready" type="text"
                                            placeholder="" required name="EMAIL[]" value="<?=$arResult["ELEMENT"]["PROP"]["EMAIL"]["VALUE"][$i]?>">
                                        <span class="site-form__text">Email</span>
                                    </label>
                                </li>
                                <li class="custom__list">
                                    <a class="addContacts">+</a>
                                    <a class="removeContacts">-</a>
                                </li>
                            </ul>
                        <?}?>
                    </div>
                    <p class="form-grid__item">
                        <label class="site-form__label site-form__label--select">
                            <select id="spravochnik__select" class="js-select2 site-form__select" name="HOUSE" multiple required>
                                <option value="0"></option>
                                <?foreach($arResult["HOUSE"] as $key=>$house){?>
                                    <option value="<?=$house["ID"]?>" <?=$arResult["ELEMENT"]["PROP"]["HOUSE"]["VALUE"][$key] == $house["ID"] ? 'selected' : ''?>><?=$house["PROPERTY_ADDRESS_VALUE"]?></option>
                                <?}?>
                            </select>
                            <span class="site-form__text">Выберите адрес</span>
                        </label>
                    </p>
                    <p class="form-grid__item">
                        <?/*<label class="site-form__label">
                            <input class="site-form__input site-form__ready" type="text"
                                   placeholder="" required name="JSON_TAGS" value="<?=isset($arResult["ELEMENT"]["PROP"]["JSON_TAGS"]["VALUE"]["TEXT"]) ? $arResult["ELEMENT"]["PROP"]["JSON_TAGS"]["VALUE"]["TEXT"] : ''?>">
                                   <textarea id="tagWorker" name="JSON_TAGS" class="site-form__input site-form__input--textarea"><?=isset($arResult["ELEMENT"]["PROP"]["JSON_TAGS"]["VALUE"]["TEXT"]) ? $arResult["ELEMENT"]["PROP"]["JSON_TAGS"]["VALUE"]["TEXT"] : ''?></textarea>
                            <span class="site-form__text">Облако тегов</span>
                        </label>*/?>
                        <?
                        if(isset($arResult["ELEMENT"]["PROP"]["JSON_TAGS"]["VALUE"]["TEXT"])){
                            $stringTag = $arResult["ELEMENT"]["PROP"]["JSON_TAGS"]["VALUE"]["TEXT"];
                        }
                        
                        ?>
                        <label class="site-form__label site-form__textarea" id="tagWorker" name="JSON_TAGS">
                            <textarea class="site-form__input site-form__input--textarea"
                                      placeholder=""><?=$stringTag?></textarea>
                            <span class="site-form__text">Облако тегов</span>
                        </label>
                    </p>
                    <script>
                        $('#tagWorker').tagEditor({
                            initialTags:[<?=$stringTag;?>],
                            delimiter: " ",
                            placeholder: "Введите теги",
                            removeDuplicates:false,
                            onChange: function(field, editor, tags) {
                            console.log(field);
                            console.log(editor);
                            console.log(tags);
                            }
                        });
                    </script>
                    
                    <?
                    if(is_array($arResult["ELEMENT"]["PROP"]["SHEDULE"]["DESCRIPTION"])){
                        foreach($arResult["ELEMENT"]["PROP"]["SHEDULE"]["DESCRIPTION"] as $shedule){
                            $arr = unserialize(html_entity_decode($shedule)); 
                    ?>
                        <div id="sheduleItems">
                            <div class="sheduleRow">
                            <button class="shedule__remove" type="button">Закрыть окно</button>
                                <p class="form-grid__item form-grid__item--row">
                                    <span class="form-grid__toggle-item custom__list">
                                        <label class="site-form__label">С</label>
                                            <input class="site-form__input time" type="time" required name="TIME_START[]" value="<?=$arr["TIME_START"]?>">
                                    </span>
                                    <span class="form-grid__toggle-item custom__list">
                                        <label class="site-form__label">До</label>
                                            <input class="site-form__input time" type="time" required name="TIME_END[]" value="<?=$arr["TIME_END"]?>">
                                    </span>
                                </p>
                                <ul class="ul__list">
                                    <li>
                                        <label class="site-form__label">
                                            <input type="checkbox" class="site-form__toggle-input" name="SHEDULE[1][]" value="Пн" multiple="" <?=$arr["SHEDULE"][0] == "Пн" ? 'checked' : ''?>>
                                            <span class="site-form__toggle-text site-form__toggle-text--checkbox">Пн</span>
                                        </label> 
                                    </li>
                                    <li>
                                        <label class="site-form__label">
                                            <input type="checkbox" class="site-form__toggle-input" name="SHEDULE[1][]" value="Вт" multiple="" <?=$arr["SHEDULE"][1] == "Вт" ? 'checked' : ''?>>
                                            <span class="site-form__toggle-text site-form__toggle-text--checkbox">Вт</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="site-form__label">
                                            <input type="checkbox" class="site-form__toggle-input" name="SHEDULE[1][]" value="Ср" multiple="" <?=$arr["SHEDULE"][2] == "Ср" ? 'checked' : ''?>>
                                            <span class="site-form__toggle-text site-form__toggle-text--checkbox">Ср</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="site-form__label">
                                            <input type="checkbox" class="site-form__toggle-input" name="SHEDULE[1][]" value="Чт" multiple="" <?=$arr["SHEDULE"][3] == "Чт" ? 'checked' : ''?>>
                                            <span class="site-form__toggle-text site-form__toggle-text--checkbox">Чт</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="site-form__label">
                                            <input type="checkbox" class="site-form__toggle-input" name="SHEDULE[1][]" value="Пт" multiple="" <?=$arr["SHEDULE"][4] == "Пт" ? 'checked' : ''?>>
                                            <span class="site-form__toggle-text site-form__toggle-text--checkbox">Пт</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="site-form__label">
                                            <input type="checkbox" class="site-form__toggle-input" name="SHEDULE[1][]" value="Сб" multiple="" <?=$arr["SHEDULE"][5] == "Сб" ? 'checked' : ''?>>
                                            <span class="site-form__toggle-text site-form__toggle-text--checkbox">Сб</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="site-form__label">
                                            <input type="checkbox" class="site-form__toggle-input" name="SHEDULE[1][]" value="Вс" multiple="" <?=$arr["SHEDULE"][6] == "Вс" ? 'checked' : ''?>>
                                            <span class="site-form__toggle-text site-form__toggle-text--checkbox">Вс</span>
                                        </label>
                                    </li>
                                </ul>
                                
                            </div>
                        </div>
                    <?}
                    } else {?>
                        <div id="sheduleItems">
                            <div class="sheduleRow">
                            <button class="shedule__remove" type="button">Закрыть окно</button>
                                <p class="form-grid__item form-grid__item--row">
                                    <span class="form-grid__toggle-item custom__list">
                                        <label class="site-form__label">С</label>
                                            <input class="site-form__input time" type="time" required name="TIME_START[]">
                                    </span>
                                    <span class="form-grid__toggle-item custom__list">
                                        <label class="site-form__label">До</label>
                                            <input class="site-form__input time" type="time" required name="TIME_END[]">
                                    </span>
                                </p>
                                <ul class="ul__list">
                                    <li>
                                        <label class="site-form__label">
                                            <input type="checkbox" class="site-form__toggle-input" name="SHEDULE[1][]" value="Пн" multiple="">
                                            <span class="site-form__toggle-text site-form__toggle-text--checkbox">Пн</span>
                                        </label> 
                                    </li>
                                    <li>
                                        <label class="site-form__label">
                                            <input type="checkbox" class="site-form__toggle-input" name="SHEDULE[1][]" value="Вт" multiple="">
                                            <span class="site-form__toggle-text site-form__toggle-text--checkbox">Вт</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="site-form__label">
                                            <input type="checkbox" class="site-form__toggle-input" name="SHEDULE[1][]" value="Ср" multiple="">
                                            <span class="site-form__toggle-text site-form__toggle-text--checkbox">Ср</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="site-form__label">
                                            <input type="checkbox" class="site-form__toggle-input" name="SHEDULE[1][]" value="Чт" multiple="">
                                            <span class="site-form__toggle-text site-form__toggle-text--checkbox">Чт</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="site-form__label">
                                            <input type="checkbox" class="site-form__toggle-input" name="SHEDULE[1][]" value="Пт" multiple="">
                                            <span class="site-form__toggle-text site-form__toggle-text--checkbox">Пт</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="site-form__label">
                                            <input type="checkbox" class="site-form__toggle-input" name="SHEDULE[1][]" value="Сб" multiple="">
                                            <span class="site-form__toggle-text site-form__toggle-text--checkbox">Сб</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="site-form__label">
                                            <input type="checkbox" class="site-form__toggle-input" name="SHEDULE[1][]" value="Вс" multiple="" >
                                            <span class="site-form__toggle-text site-form__toggle-text--checkbox">Вс</span>
                                        </label>
                                    </li>
                                </ul>
                                
                            </div>
                        </div>
                    <?}?>
                    <div class="add__shedule--btn">
                        <a href="#" class="addShedule">Добавить рабочее время</a>
                    </div>
                    
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
