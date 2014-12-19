<?php
/**
 * This file is part of the PrestaCMSContactBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class DefaultController extends Controller
{
    /**
     * Handle form submission
     *
     * @param  Request $request
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function submitAction(Request $request)
    {
        if ($request->getMethod() != 'POST') {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm('presta_cms_contact');
        $form->bind($request);

        if ($form->isValid()) {
            $settings = $this->getSettingsByBlockId($request->get('block_id'));

            /* Extract contact handling options from block settings */
            $options = array_intersect_key($settings, array_flip(['source', 'email_from', 'email_to']));

            /* Handle contact form */
            $this->container->get('presta_cms_contact.manager.contact')->handle($form, $options);

            $this->get('session')->getFlashBag()->add('flash_success', 'form.message.success');
        } else {
            $this->get('session')->getFlashBag()->add('flash_error', 'form.message.error');
        }

        return $this->render('PrestaCMSContactBundle:Default:submit.html.twig', array('form' => $form->createView()));
    }

    private function getSettingsByBlockId($id)
    {
        /* Retrieve the block */
        $dm = $this->get('sonata.admin.manager.doctrine_phpcr')->getDocumentManager();
        $block = $dm->find(null, $id);

        /* Construct a block context */
        $blockContextManager = $this->get('sonata.block.context_manager');
        $blockContext = $blockContextManager->get($block);

        /* Construct a block service */
        $blockServiceManager = $this->get('sonata.block.manager');
        $service = $blockServiceManager->get($block);
        $service->load($block);

        /* Retrieve settings of this block */
        return $service->getSettings($blockContext);
    }
}
