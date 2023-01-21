<?php

namespace PlicniTeplice\Recipes\Api\Actions;

use PlicniTeplice\Recipes\Api\Core\Crypto;
use Psr\Http\Message\ResponseInterface as Response;
use Ramsey\Uuid\Uuid;

class TestAction extends Action
{

    protected function action(): Response {
//        $this->getLogger()->alert("AHOJKY");
//        $plain = "AHOJ VLADO JAK JE? JE FODBRE A CO TY?";
//        $passwd = "123456";
//        $encrypted = Crypto::encrypt($plain);
//        print_r($encrypted);
//        $decrypted = Crypto::decrypt($encrypted);
//        print_r($decrypted);
//        $ids = [];
//        $errors = 0;
//        for($i = 0; $i < 100000; $i++){
//            $id = Uuid::uuid4()->toString();
//            if(in_array($id, $ids))
//                $errors++;
//            else
//                $ids[$i] = $id;
//        }
//        print_r($errors);
        return $this->respond();
    }
}