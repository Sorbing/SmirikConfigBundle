<?php

namespace Smirik\ConfigBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Smirik\ConfigBundle\Model\Config;
use Smirik\ConfigBundle\Model\ConfigQuery;
use Smirik\ConfigBundle\Form\Type\ConfigType;

/**
 * @Route("/admin")
 */
class AdminConfigController extends Controller
{
  /**
   * @Route("/config", name="admin_config")
   * @Secure(roles="ROLE_USER")
   * @Template("SmirikConfigBundle:Config:config.html.twig")
  */
  public function adminAction()
  {
    $configs = ConfigQuery::create()->find();
    

    return array(
      'configs' => $configs,
    );
  }
}
