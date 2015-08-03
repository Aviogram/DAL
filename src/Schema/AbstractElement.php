<?php
namespace Aviogram\DAL\Schema;

abstract class AbstractElement
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @param  string $name
     *
     * @return $this
     */
    protected function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * Normalize the name (lowercase)
     *
     * @param  string $name
     *
     * @return string
     */
    protected function normalizeName($name)
    {
        return strtolower($name);
    }

    /**
     * Get an unique hash for the given elements
     *
     * @param AbstractElement[] $elements
     *
     * @return string
     */
    protected function getHashForElements(array $elements)
    {
        $hash = array();

        foreach ($elements as $element) {
            $hash[] = $element->getName();
        }

        return md5(serialize($hash));
    }
}
