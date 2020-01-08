<?php
namespace LosMiddleware;

class LosZray
{
    public function storeLog($context, &$storage)
    {
        $msg = $context["functionArgs"][0];
        list($usec, $sec) = explode(" ", microtime());
        $date = date("Y-m-d H:i:s", $sec).substr($usec, 1);
        $storage['LosLog'][] = array('date' => $date, 'message' => $msg);
    }
}

$losStorage = new \LosMiddleware\LosZray();
$loslog = new \ZRayExtension("loslog");
$loslog->setMetadata(array(
    'logo' => __DIR__.DIRECTORY_SEPARATOR.'logo.png',
));
$loslog->setEnabledAfter('Laminas\Mvc\Application::init');
$loslog->traceFunction("LosMiddleware\\LosLog\\StaticLogger::save",  array($losStorage, 'storeLog'), function () {});
