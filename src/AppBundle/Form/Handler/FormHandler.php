<?php

namespace AppBundle\Form\Handler;

use AppBundle\Exception\ValidationException;
use AppBundle\Exception\BadRequestException;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class FormHandler
 *
 * @package AppBundle\Form\Handler
 */
class FormHandler
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @param FormFactory  $factory
     * @param RequestStack $requestStack
     */
    public function __construct(FormFactory $factory, RequestStack $requestStack)
    {
        $this->formFactory = $factory;
        $this->requestStack = $requestStack;
    }

    /**
     * @param string $formType
     * @param mixed  $data
     *
     * @return mixed
     * @throws ValidationException
     * @throws BadRequestException
     */
    public function processForm($formType, $data)
    {
        $request = $this->requestStack->getCurrentRequest();
        $form = $this->formFactory->create($formType, $data);

        if (count($request->files) === 0 && count(array_intersect_key($request->request->all(), $form->all())) <= 0) {
            throw new BadRequestException('You must send all the field!');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $errors = $form->isSubmitted() ? $this->collectErrorsToArray($form) : array('Need specify the parameters');
            throw new ValidationException($errors);
        }

        return $data;
    }

    /**
     * @param mixed $data
     *
     * @return array
     */
    private function collectErrorsToArray($data)
    {
        $form = $errors = [];
        foreach ($data->getErrors(true, true) as $error) {
            $errors[] = $error->getMessageTemplate();
        }

        if ($errors) {
            $form = $errors;
        }

        $children = [];
        foreach ($data->all() as $child) {
            $res = $this->collectErrorsToArray($child);
            if ($res !== []) {
                $children[] = $res;
            }
        }
        if ($children) {
            $form += $children;
        }

        return $form;
    }
}
