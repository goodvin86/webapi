<?php

namespace App\Service;

class ValidationService
{
    public function parametersValidate($parameters)
    {
        if(empty($parameters)) {
            return $error = 'Error! Empty parameters!';
        }

        if(empty($parameters['name'])) {
            return $error = 'Error! Empty parameter name!';
        }

        if(empty($parameters['developer_id'])) {
            return $error = 'Error! Empty parameter developer_id!';
        }

        if(empty($parameters['genre_id'])) {
            return $error = 'Error! Empty parameter genre_id!';
        }
    }
}