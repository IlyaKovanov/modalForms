<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<section id="modal-select-address" class="modal">
    <button class="modal__overlay" type="button"><span class="visually-hidden">Закрыть окно.</span></button>
    <div class="modal__block modal__block--big">
        <h2 class="modal__title">
            Подбор адресов отправки
        </h2>
        <form id="addressSelectForm" method="POST">
        <section class="site-form appeal appeal--pure">
            <div class="appeal__row">
                <div class="appeal__cell appeal__cell--grow">
                    <label class="site-form__label">
                        <input class="site-form__input site-form__ready" type="text"
                               placeholder="" name="SEARCH_CITY">
                        <span class="site-form__text">Название населенного пункта</span>
                    </label>
                </div>
                <div class="appeal__cell appeal__cell--grow">
                    <label class="site-form__label">
                        <input class="site-form__input site-form__ready" type="text"
                               placeholder="" name="SEARCH_STREET">
                        <span class="site-form__text">Улица</span>
                    </label>
                </div>
                <div class="appeal__cell appeal__cell--2grow">
                    <label class="site-form__label">
                        <input class="site-form__input site-form__ready" type="text"
                               placeholder="" name="SEARCH_HOUSE">
                        <span class="site-form__text">Номер дома</span>
                    </label>
                </div>
                <div class="appeal__cell">
                    <button class="btn btn--search btn--big" type="submit">Выбрать</button>
                </div>
            </div>
        </section>
        </form>
        <section class="table">
            <?if($arResult["HOUSE"]){?>
                <table class="table__one tableFiltered">
                    <thead>
                        <tr class="table__row table__row--header">
                            <th colspan="2" class="table__cell">
                                <span class="table__title">
                                    Результаты поиска:
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?foreach($arResult["HOUSE"] as $key=>$value){
                            if(is_array($_REQUEST["ADDRESS"])){
                                if(in_array($value["ID"], $_REQUEST["ADDRESS"])){
                                    $checked = 'checked';
                                } else {
                                    $checked = '';
                                }
                            }
                            ?>
                            <tr class="table__row">
                                <td style="width: 100%" class="table__cell">
                                    <p class="filter__item filter__item--checkbox">
                                        <label class="site-form__label">
                                            <input type="checkbox" class="site-form__toggle-input addresInput" name="ADDRESS_ID[]" value="<?=$value["ID"]?>" <?=$checked?> multiple>
                                            <span class="site-form__toggle-text site-form__toggle-text--checkbox"><?=$value["PROPERTY_ADDRESS_VALUE"]?></span>
                                        </label>
                                    </p>   
                                </td>
                                <td class="table__cell"></td>
                            </tr>
                        <?}?>
                    </tbody>
                </table>
            <?} else {?>
                <span class="none table__none">
                    По вашему запросу ничего не найдено
                </span>
            <?}?>
        </section>
        <div class="appeal__cell">
            <button id="addAddress" class="btn btn--green btn-min" type="button">Выбрать</button>
        </div>
        <button class="modal__closer" type="button">Закрыть окно</button>
    </div>
</section>