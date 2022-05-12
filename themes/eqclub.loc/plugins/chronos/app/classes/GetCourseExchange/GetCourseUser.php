<?php namespace Chronos\App\Classes\GetCourseExchange;

use October\Rain\Network\Http;

class GetCourseUser {

    const BAZE_PATH = 'https://online.skobeltsyn.biz/pl/api/account/users';
    const EXPORT_PATH = 'https://online.skobeltsyn.biz/pl/api/account/exports';
    const KEY = 'gqvh1sMl85gkVpszW2B5O9a4Wq4yWoMKu1YRuYhvvUT1hMyliU5spypJj13ntPdCtfs81NgW8ofJ04CsVruSnqN5cZeiKFIfyleOUL3RchBhYnn6oOOtCMnEecWstkXj';

    public static function all() {
        return self::where();
    }

    public static function where($params = array()) {
        if (empty($params))
            $params['status'] = 'active';
        
        $exportID = self::getExportId($params);

        return self::getByExportId($exportID);
    }

    // Получаем список пользователей
    // По ID файла импорта
    protected static function getByExportId($exportID) {

        $result= Http::post(self::EXPORT_PATH . '/' . $exportID, function($http){
            $http->data('key', self::KEY);
        });

        $result = json_decode($result->body);

        if (!$result->success) {
            if ($result->error_code == 909 || $result->error_code == 905) { //Если файл еще создается
                sleep(7);
                //  dd($result);
                self::getByExportId($exportID);
            } else {
                throw new \Exception($result->error_message);
            }
            
        }

        return $result;  
    }

    // Получаем ID файла экспорта
    // Необходим для выгрузки пользователей
    protected static function getExportId($params = array()) {

        $result = self::sendRequest($params);

        if ($result->success)
            return $result->info->export_id;     
        return $result;
    }


    // Отправка запроса к системе API
    protected static function sendRequest($params = array()) {

        $paramsUri = http_build_query($params);

        $result= Http::post(self::BAZE_PATH . '?' . $paramsUri, function($http){
            $http->data('key', self::KEY);
        });

        $result = json_decode($result->body);

        if (!$result->success)
            throw new \Exception($result->error_message);

        return $result;         
    }

}