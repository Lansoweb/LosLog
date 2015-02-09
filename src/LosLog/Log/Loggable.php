<?php
/**
 * Trait for loggable objects
 *
 * @package   LosLog\Log
 * @author    Leandro Silva <leandro@leandrosilva.info>
 * @link      http://leandrosilva.info Development Blog
 * @link      http://github.com/LansoWeb/LosLog for the canonical source repository
 * @copyright Copyright (c) 2011-2013 Leandro Silva (http://leandrosilva.info)
 * @license   http://leandrosilva.info/licenca-bsd New BSD license
 */
namespace LosLog\Log;

/**
 * Trait for loggable objects
 *
 * @package   LosLog\Log
 * @author    Leandro Silva <leandro@leandrosilva.info>
 * @link      http://leandrosilva.info Development Blog
 * @link      http://github.com/LansoWeb/LosLog for the canonical source repository
 * @copyright Copyright (c) 2011-2013 Leandro Silva (http://leandrosilva.info)
 * @license   http://leandrosilva.info/licenca-bsd New BSD license
 */
trait Loggable
{
    /**
     * Function to collect properties values
     *
     * @return array Array contendo as propriedades do object e seus valores
     */
    public function losLogMe()
    {
        $ret = [];
        $ret[get_class($this)] = [];
        foreach (get_object_vars($this) as $name => $content) {
            if (!is_object($content)) {
                $ret[$name] = ['type' => gettype($content), 'content' => $content];
            } else {
                $ret[$name] = ['type' => gettype($content), 'class' => get_class($content)];
            }
        }

        return $ret;
    }
}
