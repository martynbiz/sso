<?php
namespace App\Controller;

use App\Model\User;
use App\Validator;

class UsersController extends BaseController
{
    public function register($request, $response, $args)
    {
        // if errors found from post, this will contain data
        $params = $request->getParams();

        return $this->render('users/register', [
            'params' => $params,
        ]);
    }

    public function post($request, $response, $args)
    {
        $params = $request->getParams();
        $container = $this->getContainer();

        // validate form data

        // our simple custom validator for the form
        $validator = new Validator();
        $validator->setData($params);
        $i18n = $container->get('i18n');

        // first_name
        $validator->check('first_name')
            ->isNotEmpty( $i18n->translate('first_name_missing') );

        // last_name
        $validator->check('last_name')
            ->isNotEmpty( $i18n->translate('last_name_missing') );

        // email
        $validator->check('email')
            ->isNotEmpty( $i18n->translate('email_missing') )
            ->isEmail( $i18n->translate('email_invalid') )
            ->isUniqueEmail( $i18n->translate('email_not_unique'), $container->get('model.user') );

        // password
        $message = $i18n->translate('password_must_contain');
        $validator->check('password')
            ->isNotEmpty($message)
            ->hasLowerCase($message)
            ->hasUpperCase($message)
            ->isMinimumLength($message, 8);

        // agreement
        $validator->check('agreement');

        // more_info
        // more info is a invisible field (not type=hidden, use css)
        // that humans won't see however, when bots turn up they
        // don't know that and fill it in. so, if it's filled in,
        // we know this is a bot
        if ($validator->has('more_info')) {
            $validator->check('more_info')
                ->isEmpty( $i18n->translate('email_not_unique') ); // misleading msg ;)
        }

        // if valid, create user

        if ($validator->isValid()) {

            if ($user = $container->get('model.user')->create($params)) {

                // set meta entries (if given)
                if (isset($params['source'])) $user->setMeta('source', $params['source']);

                // // set session attributes w/ backend (method of signin)
                // $container->get('auth')->setAttributes( array_merge($user->toArray(), array(
                //     'backend' => User::BACKEND_JAPANTRAVEL,
                // )) );

                // send welcome email
                $container->get('mail_manager')->sendWelcomeEmail($user);

                // redirect
                isset($params['returnTo']) or $params['returnTo'] = '/';
                return $this->returnTo($params['returnTo']);

            } else {
                $errors = $user->errors();
            }

        } else {
            $errors = $validator->getErrors();
        }

        $container->get('flash')->addMessage('errors', $errors);
        return $this->forward('register', func_get_args());
    }
}
