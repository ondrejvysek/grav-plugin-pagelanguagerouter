<?php
namespace Grav\Plugin;
use Grav\Common\Plugin;
use Grav\Common\Page\Page;
use Grav\Common\Grav;
use Grav\Common\Language\Language;
use RocketTheme\Toolbox\Event\Event;

class PageLangRouterPlugin extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'onPageInitialized' => ['onPageInitialized', 0]
        ];
    }

	public function onPageInitialized(Event $event)
    {
		$page = $this->grav['page'];
		// ignore admin
        if ($this->isAdmin()) {
            return;
        }
		// get config
		$routemode = $this->config->get('plugins.pagelangrouter.routemode'); 
		$reroutecode = $this->config->get('plugins.pagelangrouter.reroutecode'); 
		if ($page->routable()) { 
			try {
				$this->grav['debugger']->addMessage($routemode);
				if ($page->Language() != $page->parent()->Language()){
					$this->grav['debugger']->addMessage('Page language different from parent, rerouting');
					if ($routemode){
						$this->grav->redirect('', 404);
					} else {
						$this->grav->redirect('/'.$page->parent()->Language().$page->parent()->route(),$reroutecode);
					}
				}
			} catch (Exception $e){
			}
		}
    }
}
?>