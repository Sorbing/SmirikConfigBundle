<?php

namespace Smirik\ConfigBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Smirik\ConfigBundle\Model\Config;
use Smirik\ConfigBundle\Model\ConfigQuery;
use Smirik\ConfigBundle\Form\Type\ConfigType;
use Symfony\Component\HttpFoundation\Request;

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
  public function adminAction(Request $request)
  {
    $test = '';
    $configs = ConfigQuery::create()->find();
    if ($request->getMethod() == 'POST')
    {
      $configsData = $this->get('request')->request->get('config');
      foreach ($configsData as $id => $value)
      {
        $config = ConfigQuery::create()->findPk($id);
        if (!$config) {
          throw $this->createNotFoundException('ConfigBundle: поле не найдено: '.$id);
        }
        $config->setValue($value);
        $config->save();
      }
    }

    return array(
      'configs' => $configs,
    );
  }
}
