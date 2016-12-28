<?php

namespace Core;

/**
 * Description of Helpers
 *
 * @author dushevadnqka
 */
class Helpers
{
    /**
     * Die Dump Helper
     *
     * @param type $param
     * @return type
     */
    public function dd($param)
    {
         return die(var_dump($param));
    }
}
