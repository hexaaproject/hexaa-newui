<?php

namespace AppBundle\Controller;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/service")
 */
class ServiceController extends Controller {

    /**
     * @Route("/index")
     */
    public function indexAction(Request $request) {

        // copy + paste from service.php
        try {
            $serviceId = $request->query->get('id');
            if ($request->query->has('menu')) {
                $menu = $request->query->get('menu');
            } else {
                $menu = 'main';
            }
            if ($serviceId) {
                $service = $this->get('service')->get($serviceId);
            } else {
                $service = null;
            }
            $organizations = $this->get('organization')->cget();

            $services = $this->get('service')->cget();
        } catch (ClientException $e) {
            return $this->render('error.html.twig', array('clientexception' => $e));
        } catch (ServerException $e) {
            return $this->render('error.html.twig', array('serverexception' => $e));
        }

        return $this->render('AppBundle:Service:index.html.twig', array(
                    'service' => $service,
                    'organizations' => $organizations,
                    'services' => $services,
                    'menu' => $menu
                        )
        );
    }
    
     /**
     * @Route("/addStepOne")
     * @Template()
     */
    public function addStepOneAction(Request $request)
    {
        return $this->render('AppBundle:Service:addStepOne.html.twig', array());
    }

    /**
     * @Route("/addStepTwo")
     * @Template()
     */
    public function addStepTwoAction(Request $request)
    {
        return $this->render('AppBundle:Service:addStepTwo.html.twig', array());
    }

    /**
     * @Route("/addStepThree")
     * @Template()
     */
    public function addStepThreeAction(Request $request)
    {
        return $this->render('AppBundle:Service:addStepThree.html.twig', array());
    }

    /**
     * @Route("/addStepFour")
     * @Template()
     */
    public function addStepFourAction(Request $request)
    {
        return $this->render('AppBundle:Service:addStepFour.html.twig', array());
    }

    /**
     * @Route("/addStepFive")
     * @Template()
     */
    public function addStepFiveAction(Request $request)
    {
        return $this->render('AppBundle:Service:addStepFive.html.twig', array());
    }

    /**
     * @Route("/show/{id}")
     */
    public function showAction($id) {
        return $this->render(
                        'AppBundle:Service:show.html.twig', array(
                    'organizations' => $this->getOrganizations(),
                    'services' => $this->getServices(),
                    'service' => $this->getService($id),
                    'servsubmenubox' => $this->getServSubmenuPoints()
                        )
        );
    }

    /**
     * @Route("/properties/{id}")
     * @Template()
     */
    public function propertiesAction($id) {
        return $this->render(
                        'AppBundle:Service:properties.html.twig', array(
                    'organizations' => $this->getOrganizations(),
                    'services' => $this->getServices(),
                    'service' => $this->getService($id),
                    'main' => $this->getService($id),
                    'propertiesbox' => $this->getPropertiesBox(),
                    'servsubmenubox' => $this->getServSubmenuPoints()
                        )
        );
    }

    private function getServSubmenuPoints() {
        $submenubox = array(
            "app_service_properties" => "Properties",
            "app_service_managers" => "Managers",
            "app_service_attributes" => "Attributes",
            "app_service_permissions" => "Permissions",
            "app_service_permissionssets" => "Permissions sets",
            "app_service_connectedorganizations" => "Connected organizations"
        );

        return $submenubox;
    }

    private function getPropertiesBox() {
        $propertiesbox = array(
            "Name" => "name",
            "Description" => "description",
            "Home page" => "url",
            "SAML SP Entity ID" => "entityid",
            "Created" => "created_at",
            "Last updated" => "updated_at"
        );

        return $propertiesbox;
    }

    /**
     * @Route("/managers/{id}")
     * @Template()
     */
    public function managersAction($id) {
        $service = $this->getService($id);
        $managers = $this->getManagers($service);
        $managers_buttons = array(
            "remove" => array(
                "class" => "btn-blue pull-left",
                "text" => "Remove"
            ),
            "invite" => array(
                "class" => "btn-red pull-right",
                "last" => true,
                "text" => '<i class="material-icons">add</i> Invite'
            ),
        );
        return $this->render(
                        'AppBundle:Service:managers.html.twig', array(
                    'organizations' => $this->getOrganizations(),
                    'services' => $this->getServices(),
                    'service' => $this->getService($id),
                    'servsubmenubox' => $this->getServSubmenuPoints(),
                    'managers' => $managers,
                    'managers_buttons' => $managers_buttons
                        )
        );
    }
    
    /**
     * @Route("/removemanagers/{id}")
     * @Template()
     */
    public function removeManagersAction($id, Request $request)
    {
        try {
            # do something
            $this->get('session')->getFlashBag()->add('success', 'Siker');
        } catch (\Exception $e) {
            $this->get('session')->getFlashBag()->add('error', 'Hiba a feldolgozás során');
            $this->get('logger')->error($e);
        }
        return $this->redirect($this->generateUrl('app_service_managers', array('id' => $id)));
    }

    /**
     * @Route("/attributes/{id}")
     * @Template()
     */
    public function attributesAction($id) {
        $service = $this->getService($id);
        $attributes = $this->getServiceAttributes($service);
        $attributes_buttons = array(
            "change_attributes" => array(
                "class" => "btn-blue pull-left",
                "text" => "Remove"
            ),
            "add" => array(
                "class" => "btn-red pull-right",
                "last" => true,
                "text" => '<i class="material-icons">add</i> Add'
            ),
        );
        return $this->render(
                        'AppBundle:Service:attributes.html.twig', array(
                    'organizations' => $this->getOrganizations(),
                    'services' => $this->getServices(),
                    'service' => $this->getService($id),
                    'servsubmenubox' => $this->getServSubmenuPoints(),
                    'attributes' => $attributes,
                    'attributes_buttons' => $attributes_buttons
                        )
        );
    }

    /**
     * @Route("/permissions/{id}")
     * @Template()
     */
    public function permissionsAction($id) {
        $verbose = "expanded";
        $permissions = $this->get('service')->getEntitlements($id, $verbose)['items'];
        return $this->render(
                        'AppBundle:Service:permissions.html.twig', array(
                    'organizations' => $this->getOrganizations(),
                    'servsubmenubox' => $this->getServSubmenuPoints(),
                    'services' => $this->getServices(),
                    'service' => $this->getService($id),
                    'permissions_accordion' => $this->permissionsToAccordion($permissions)
                        )
        );
    }

    /**
     * @Route("/permissionssets/{id}")
     * @Template()
     */
    public function permissionssetsAction($id) {
        $verbose = "expanded";
        $permissionsset = $this->get('service')->getEntitlementPacks($id, $verbose)['items'];
        return $this->render(
                        'AppBundle:Service:permissionssets.html.twig', array(
                    'organizations' => $this->getOrganizations(),
                    'services' => $this->getServices(),
                    'service' => $this->getService($id),
                    'servsubmenubox' => $this->getServSubmenuPoints(),
                    'permissions_accordion_set' => $this->permissionSetToAccordion($permissionsset)
                        )
        );
    }

    /**
     * @Route("/connectedorganizations/{id}")
     * @Template()
     */
    public function connectedOrganizationsAction($id) {
        return $this->render(
                        'AppBundle:Service:connectedorganizations.html.twig', array(
                    'organizations' => $this->getOrganizations(),
                    'services' => $this->getServices(),
                    'service' => $this->getService($id)
                        )
        );
    }

    private function permissionsToAccordion($permissions) {
        $permissions_accordion = array();
        foreach ($permissions as $permission) {
            $permissions_accordion[$permission['id']]['title'] = $permission['name'];
            $description = array();
            $uri = array();
            array_push($description, $permission['description']);
            array_push($uri, $permission['uri']);
            $permissions_accordion[$permission['id']]['contents'] = array(
                array(
                    'key' => 'Description',
                    'values' => $description
                ),
                array(
                    'key' => 'URI',
                    'values' => $uri
                )
            );
        }
        return $permissions_accordion;
    }

    private function permissionSetToAccordion($permissionSets) {
        $permissions_accordion_set = array();
        foreach ($permissionSets as $permissionSet) {
            $permissions_accordion_set[$permissionSet['id']]['title'] = $permissionSet['name'];
            $description = array();
            $type = array();
            $permissions = array();
            array_push($description, $permissionSet['description']);
            array_push($type, $permissionSet['type']);
            foreach ($permissionSet['entitlements'] as $entitlement) {
                $permissions[] = $entitlement['name'];
            }
            $permissions_accordion_set[$permissionSet['id']]['contents'] = array(
                array(
                    'key' => 'Description',
                    'values' => $description
                ),
                array(
                    'key' => 'Type',
                    'values' => $type
                ),
                array(
                    'key' => 'Permissions',
                    'values' => $permissions
                )
            );
        }
        return $permissions_accordion_set;
    }

    private function getManagers($service) {
        return $this->get('service')->getManagers($service['id'])['items'];
    }

    private function getServiceAttributes($service) {
        $verbose = "expanded";
        $serviceattributespecs = $this->get('service')->getAttributeSpecs($service['id'])['items'];
        $attributestonames = array();
        foreach ($this->get('attribute_spec')->cget($verbose)['items'] as $attributespec) {
            foreach ($serviceattributespecs as $serviceattributespec) {
                if ($attributespec['id'] == $serviceattributespec['attribute_spec_id']) {
                    array_push($attributestonames, $attributespec);
                }
            }
        }
        return $attributestonames;
    }

    private function getOrganizations() {
        $organization = $this->get('organization')->cget();
        return $organization;
    }

    private function getServices() {
        $services = $this->get('service')->cget();
        return $services;
    }

    private function getService($id) {
        $service = $this->get('service')->get($id);
        return $service;
    }

}
