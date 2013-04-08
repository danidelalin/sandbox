<?php

namespace Undf\ReloadUserOptimizationBundle\Extractor;

class ReloadRoute
{

    private $tokens;
    private $defaults;
    private $requirements;

    public function __construct(array $tokens, array $defaults, array $requirements)
    {
        $this->tokens = $tokens;
        $this->defaults = $defaults;
        $this->requirements = $requirements;
    }

    public function getTokens()
    {
        return $this->tokens;
    }

    public function getDefaults()
    {
        return $this->defaults;
    }

    public function getRequirements()
    {
        return $this->requirements;
    }

}
