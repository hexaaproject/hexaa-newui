<?php

namespace AppBundle\Controller;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ServicePropertiesType;
use AppBundle\Form\ServiceOwnerType;
use AppBundle\Form\ServicePrivacyType;

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
    public function addStepOneAction(Request $request) {
        return $this->render('AppBundle:Service:addStepOne.html.twig', array());
    }

    /**
     * @Route("/addStepTwo")
     * @Template()
     */
    public function addStepTwoAction(Request $request) {
        $verbose = "expanded";
        $attributespecs = $this->get('attribute_spec')->cget($verbose);
        return $this->render('AppBundle:Service:addStepTwo.html.twig', array(
                    'attributes' => $attributespecs,
        ));
    }

    /**
     * @Route("/addStepThree")
     * @Template()
     */
    public function addStepThreeAction(Request $request) {
        $verbose = "expanded";
        $permissionsset = $this->get('entitlement_pack')->getPublic($verbose)['items'];
        return $this->render('AppBundle:Service:addStepThree.html.twig', array(
                        //'permissions_accordion_set' => $this->permissionsetToAccordion($permissionsset)
        ));
    }

    /**
     * @Route("/addStepFour")
     * @Template()
     */
    public function addStepFourAction(Request $request) {
        return $this->render('AppBundle:Service:addStepFour.html.twig', array());
    }

    /**
     * @Route("/addStepFive")
     * @Template()
     */
    public function addStepFiveAction(Request $request) {
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
    public function propertiesAction($id, Request $request) {
        
        $entityids = $this->get('service')->getEntityIds();
        $entityidskeys = array_keys($entityids);
        $choicearray = array();
        foreach ($entityidskeys as $entityID) {
           $choicearray[$entityID] = $entityID; 
        }

        dump($choicearray);
    
        $propertiesDatas = array();
        $service = $this->getService($id);
        $propertiesDatas['serviceName'] = $service['name'];
        $propertiesDatas['serviceDescription'] = $service['description'];
        $propertiesDatas['serviceURL'] = $service['url'];
        $propertiesDatas['serviceSAML'] = $service['entityid'];
        $propertiesDatas['serviceOwnerName'] = $service['org_name'];
        $propertiesDatas['serviceOwnerDescription'] = $service['org_description'];
        $propertiesDatas['serviceOwnerURL'] = $service['org_url'];
        $propertiesDatas['serviceOwnerShortName'] = $service['org_short_name'];
        $propertiesDatas['servicePrivacyURL'] = $service['priv_url'];
        $propertiesDatas['servicePrivacyDescription'] = $service['priv_description'];
        $propertiesDatas['serviceEntityIDs'] = $choicearray;

        $formproperties = $this->createForm(ServicePropertiesType::class, array('properties' => $propertiesDatas));

        $formproperties->handleRequest($request);

        if ($formproperties->isSubmitted() && $formproperties->isValid()) {

            $data = $request->request->all();
            $modified = array('name' => $data['service_properties']['serviceName'], 'entityid' => $data['service_properties']['serviceSAML'], 'description' => $data['service_properties']['serviceDescription'], 'url' => $data['service_properties']['serviceURL']);
            dump($modified);
            $this->get('service')->patch($id, $modified);
            return $this->redirect($request->getUri());
        }

        $formowner = $this->createForm(ServiceOwnerType::class, array('properties' => $propertiesDatas));

        $formowner->handleRequest($request);

        if ($formowner->isSubmitted() && $formowner->isValid()) {

            $data = $request->request->all();
            dump($data);
            $modified = array('org_name' => $data['service_owner']['serviceOwnerName'], 'org_short_name' => $data['service_owner']['serviceOwnerShortName'], 'org_description' => $data['service_owner']['serviceOwnerDescription'], 'org_url' => $data['service_owner']['serviceOwnerURL']);
            $this->get('service')->patch($id, $modified);
            return $this->redirect($request->getUri());
        }

        $formprivacy = $this->createForm(ServicePrivacyType::class, array('properties' => $propertiesDatas));

        $formprivacy->handleRequest($request);

        if ($formprivacy->isSubmitted() && $formprivacy->isValid()) {

            $data = $request->request->all();
            dump($data);
            $modified = array('priv_url' => $data['service_privacy']['servicePrivacyURL'], 'priv_description' => $data['service_privacy']['servicePrivacyDescription']);
            $this->get('service')->patch($id, $modified);
            return $this->redirect($request->getUri());
        }

        return $this->render(
                        'AppBundle:Service:properties.html.twig', array(
                    'organizations' => $this->getOrganizations(),
                    'services' => $this->getServices(),
                    'service' => $this->getService($id),
                    'main' => $this->getService($id),
                    'propertiesbox' => $this->getPropertiesBox(),
                    'privacybox' => $this->getPrivacyBox(),
                    'ownerbox' => $this->getOwnerBox(),
                    'servsubmenubox' => $this->getservsubmenupoints(),
                    'propertiesform' => $formproperties->createView(),
                    'ownerform' => $formowner->createView(),
                    'privacyform' => $formprivacy->createView()
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
        );

        return $propertiesbox;
    }

    private function getPrivacyBox() {
        $propertiesbox = array(
            "URL" => "priv_url",
            "Description" => "priv_description",
        );
        return $propertiesbox;
    }

    private function getOwnerBox() {
        $propertiesbox = array(
            "Name" => "org_name",
            "Short name" => "org_short_name",
            "Description" => "org_description",
            "Home page" => "org_url"
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
    public function removemanagersAction($id, Request $request) {
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
