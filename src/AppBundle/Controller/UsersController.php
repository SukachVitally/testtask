<?php

namespace AppBundle\Controller;


use AppBundle\Model\User;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AppBundle\Form\Type\CreateUserType;
use FOS\RestBundle\Controller\Annotations\Version;

/**
 * Rest controller for user
 *
 * @Version({"1.0"})
 * @Annotations\RouteResource("/users")
 */
class UsersController extends FOSRestController
{
    /**
     * Create new user.
     *
     * @ApiDoc(
     *   section = "Users",
     *   description = "Create new user.",
     *   input = "AppBundle\Form\Type\CreateUserType",
     *   output= "AppBundle\Doc\OutputUser",
     *   statusCodes = {
     *     200 = "Successful",
     *     400 = "Invalid request params",
     *     500 = "Internal server error"
     *   }
     * )
     *
     * @Annotations\Post("/users", name="api_post_users")
     *
     * @return array
     */
    public function postUsersAction()
    {
        $user = new User();
        $this->get('app.form.handler')->processForm(CreateUserType::class, $user);
        $user = $this->get('app.user_manager')->create($user);

        return $user;
    }
}
