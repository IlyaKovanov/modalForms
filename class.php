<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

class ModalFormComponent extends CBitrixComponent implements Controllerable
{
    protected $request;

    function getPost(){
        $request = Application::getInstance()->getContext()->getRequest();
        $post = $request->getPostList()->toArray();
        return $post;
    }

    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    public function executeComponent()
    {
        try {
            // подключаем метод проверки подключения модуля «Информационные блоки»
            $this->checkModules();
            // подключаем метод подготовки массива $arResult
            $this->getResult();
        } catch (SystemException $e) {
            ShowError($e->getMessage());
        }
        
    }

    protected function checkModules()
    {
        // если модуль не подключен
        if (!Loader::includeModule('iblock'))
            // выводим сообщение в catch
            throw new SystemException(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
    }


    public function configureActions()
    {
        return [
            'newSpravochnik' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_POST]),
                ],
                'postfilters' => []
            ],
        ];
    }

    public function newSpravochnikAction()
    {

        $post = $this->getPost();
        $fields = [
            'IBLOCK_ID' => IBLOCK_SPRAVOCHNIK,
            'ACTIVE' => 'Y',
            'NAME' => $post["NAME"],
            'PROPERTY_VALUES' => [
                'EMAIL' => $post["EMAIL"],
                'PHONE' => $post["PHONE"],
                'HOUSE' => $post["HOUSE"],
                'JSON_TAGS' => Array("VALUE" => Array ("TEXT" => json_encode($post["JSON_TAGS"]), "TYPE" => "text"))
            ]
        ];

        if($post["ID"]){
            if ($itemId = $this->updateElement($post["ID"], $fields)) {
                $result = ['status' => 'success', 'message' => Loc::getMessage('MESSAGE_SUCCESS')];
            } else {
                $result = ['status' => 'error', 'message' => Loc::getMessage('MESSAGE_ERROR')];
            }
        } else {
            if ($itemId = $this->saveElement($fields)) {
                $result = ['status' => 'success', 'message' => Loc::getMessage('MESSAGE_SUCCESS')];
            } else {
                $result = ['status' => 'error', 'message' => Loc::getMessage('MESSAGE_ERROR')];
            }
        }
        

        return $result;
    }

    public function newDocumentAction()
    {
        $post = $this->getPost();

        if(is_array($_FILES["DOCS"]["name"])){
            foreach($_FILES["DOCS"]["name"] as $key=>$value){
                $files[$key]["name"] = $value;
                $files[$key]["full_path"] = $_FILES["DOCS"]["full_path"][$key];
                $files[$key]["type"] = $_FILES["DOCS"]["type"][$key];
                $files[$key]["tmp_name"] = $_FILES["DOCS"]["tmp_name"][$key];
                $files[$key]["error"] = $_FILES["DOCS"]["error"][$key];
                $files[$key]["size"] = $_FILES["DOCS"]["size"][$key];
            }
        }
        
        $fields = [
            'IBLOCK_ID' => IBLOCK_SPRAVOCHNIK,
            'ACTIVE' => 'Y',
            'NAME' => $post["NAME"],
            'PROPERTY_VALUES' => [
                'DOCUMENT' => $files,
                'HOUSE' => $post["HOUSE"]
            ]
        ];

        if($post["ID"]){
            if ($itemId = $this->updateElement($post["ID"], $fields)) {
                $result = ['status' => 'success', 'message' => Loc::getMessage('MESSAGE_SUCCESS')];
            } else {
                $result = ['status' => 'error', 'message' => Loc::getMessage('MESSAGE_ERROR')];
            }
        } else {
            if ($itemId = $this->saveElement($fields)) {
                $result = ['status' => 'success', 'message' => Loc::getMessage('MESSAGE_SUCCESS')];
            } else {
                $result = ['status' => 'error', 'message' => Loc::getMessage('MESSAGE_ERROR')];
            }
        }
        
        return $result;
        
    }

    public function addressSelectAction()
    {
        $this->checkModules();

        $post = $this->getPost();


        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_ADDRESS");
        $arFilter = Array("IBLOCK_ID"=>IntVal(IBLOCK_HOUSE), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
        if($post){
            $arFilter["PROPERTY_ADDRESS"] = "%".$post["SEARCH_CITY"]."%".$post["SEARCH_STREET"]."%".$post["SEARCH_HOUSE"];
        }
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
        while($ob = $res->GetNextElement()){ 
            $arFields[] = $ob->GetFields();  
        }


        if($arFields){

            foreach($arFields as $item){
                $html .='<tr class="table__row">
                            <td style="width: 100%" class="table__cell">
                                <p class="filter__item filter__item--checkbox">
                                    <label class="site-form__label">
                                        <input type="checkbox" class="site-form__toggle-input addresInput" name="ADDRESS_ID[]" value="'.$item["ID"].'" multiple>
                                        <span class="site-form__toggle-text site-form__toggle-text--checkbox">'.$item["PROPERTY_ADDRESS_VALUE"].'</span>
                                    </label>
                                </p>   
                            </td>
                            <td class="table__cell"></td>
                        </tr>'; 
            }
            
            return $html;

        } else {
            $result = ['status' => 'error', 'message' => Loc::getMessage('MESSAGE_ERROR')];
        }
        
    }


    public function saveElement($fields)
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        $element = new CIBlockElement;
        return $element->Add($fields);
    }

    public function updateElement($id, $fields)
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        $element = new CIBlockElement;
        return $element->Update($id, $fields);
    }
    
    protected function getResult()
    {

        $post = $this->getPost();
        $formTemplate = $post["FORM"] ?? '';

        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_ADDRESS");
        $arFilter = Array("IBLOCK_ID"=>IntVal(IBLOCK_HOUSE), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
        while($ob = $res->GetNextElement()){ 
            $arFields[] = $ob->GetFields();  
        }

        $this->arResult["HOUSE"] = $arFields;

        if($post["ID"]){
            $arSelectElement = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");
            $arFilterElement = Array("IBLOCK_ID"=>IntVal(IBLOCK_SPRAVOCHNIK), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>$post["ID"]);
            $resElement = CIBlockElement::GetList(Array(), $arFilterElement, false, Array("nPageSize"=>1), $arSelectElement);
            if($obElement = $resElement->GetNextElement()){ 
                $arFieldsElement = $obElement->GetFields();  
                $arFieldsElement["PROP"] = $obElement->GetProperties();
                
            }

        }

        $this->arResult["ELEMENT"] = $arFieldsElement;

        $this->includeComponentTemplate($formTemplate);
        
    }
    

}