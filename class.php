<?

class UserList extends \CBitrixComponent
{
    function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    function executeComponent() {
        if(empty($_REQUEST['IMPORT'])){
            if ($this->startResultCache(false, $_REQUEST['user_page'])) {
                $this->getUsers();
                $this->includeComponentTemplate();
            }
        }else{
            if ($this->startResultCache(false, $_REQUEST['user_page'],$_REQUEST['sessid'],$_REQUEST['IMPORT'])) {
                $this->getUsers();
                $this->import($_REQUEST['IMPORT'],$_REQUEST['sessid']);
                $this->includeComponentTemplate();
            }
        }
    }

    function import($import = false, $sessid = false){
        if($sessid != bitrix_sessid()) return false;
        if($import === 'XML') $this->importFileXML(self::getUsersToImport());
        if($import === 'CSV') $this->importFileCSV(self::getUsersToImport());
    }

    function importFileCSV($arUser = []){
        if(empty($arUser)) return false;
        require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/csv_data.php");
        $csvFile = new CCSVData();
        $csvFile->SetFieldsType('R');
        $csvFile->SetDelimiter(';');

        $arrHeaderCSV = ['USER_ID','SHORT_NAME','LOGIN'];

        $csvFile->SaveFile("users.csv", $arrHeaderCSV);
        foreach ($arUser as $user){
            $csvFile->SaveFile("users.csv", array_values($user));
        }
        $csvFile->CloseFile();
        $this->download_file('users.csv');

    }

    function importFileXML($arUser = []){
        if(empty($arUser)) return false;
        $export = new \Bitrix\Main\XmlWriter(array(
            'file' => 'users.xml',
            'create_file' => true,
            'charset' => SITE_CHARSET,
            'lowercase' => true
        ));

        $export->openFile();
        $export->writeBeginTag('users');
        foreach ($arUser as $user){
            $export->writeItem($user, 'user');
        }
        $export->writeEndTag('users');
        $export->closeFile();

        $this->download_file('users.xml');
    }

    function download_file($file) {
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            unlink($file);
            exit;
        }
    }

    function getUsersToImport(){
        $result = \Bitrix\Main\UserTable::getList(array(
            'select' => ['ID','SHORT_NAME','LOGIN'],
            'order' => ['ID'=>'ASC'],
        ));

        $arUsers = [];

        while ($arUser = $result->fetch()) {
            $arUsers[] = $arUser;
        }

        return $arUsers;
    }

    function getUsers(){
        $nav = new \Bitrix\Main\UI\PageNavigation("user_page");
        $nav->allowAllRecords(true)
            ->setPageSize($this->arParams['UL_COUNT_PAGE'])
            ->initFromUri();

        $result = \Bitrix\Main\UserTable::getList(array(
            'select' => ['ID','SHORT_NAME','LOGIN'],
            'order' => ['ID'=>'ASC'],
            "count_total" => true,
            "offset" => $nav->getOffset(),
            "limit" => $nav->getLimit(),
        ));

        if ($result->getCount() < 1) $this->AbortResultCache();

        $nav->setRecordCount($result->getCount());

        while ($arUser = $result->fetch()) {
            $this->arResult['USERS'][] = $arUser;
        }

        $this->arResult['NAV'] = $nav;
    }

}