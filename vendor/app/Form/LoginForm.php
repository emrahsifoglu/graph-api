<?php

namespace App\Vendor\Form;

use App\Entity\User\User;
use App\Vendor\Database\Entity\EntityInterface;
use App\Vendor\Http\Request\Request;

class LoginForm extends Form
{

    /**
     * @param Request $request
     * @param EntityInterface|User|null $entity
     * @return bool
     */
    public function handleRequest(Request $request, EntityInterface &$entity = null) {
        $this->request = $request;
        $params = $request->getParams();
        $email = $params['email'];
        $password = $params['password'];

        if (strlen($password) == 0) {
            $this->addError("The password is invalid.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addError("The email is invalid.");
        }

        if (count($this->getErrors()) > 0) {
            $this->isValid = false;
            return false;
        }

        $entity->setEmail($email);
        $entity->setPlainPassword($password);

        $this->isValid = true;
    }


}
