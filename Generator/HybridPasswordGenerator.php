<?php

namespace Hackzilla\PasswordGenerator\Generator;

use Hackzilla\PasswordGenerator\Model\CharacterSet;
use Hackzilla\PasswordGenerator\Model\Option\Option;

class HybridPasswordGenerator extends ComputerPasswordGenerator
{
    const OPTION_COUNT = 'COUNT';

    const PARAMETER_SEPARATOR = 'SEPARATOR';

    public function __construct()
    {
        parent::__construct();

        $this
            ->removeOption(self::OPTION_LENGTH)
            ->setOption(self::OPTION_COUNT, array('type' => Option::TYPE_INTEGER, 'default' => 4))
            ->setOption(self::OPTION_LENGTH, array('type' => Option::TYPE_INTEGER, 'default' => 3))
            ->setParameter(self::PARAMETER_SEPARATOR, '-')
        ;
    }

    /**
     * Generate character list for us in generating passwords
     *
     * @return CharacterSet Character list
     * @throws \Exception
     */
    public function getCharacterList()
    {
        $characterList = parent::getCharacterList();
        $characterList = \str_replace($this->getSegmentSeparator(), '', $characterList);

        return new CharacterSet($characterList);
    }

    /**
     * Generate one password based on options
     *
     * @return string password
     */
    public function generatePassword()
    {
        $characterList = $this->getCharacterList()->getCharacters();
        $characters = \strlen($characterList);
        $password = '';

        $segmentCount = $this->getSegmentCount();
        $segmentLength = $this->getSegmentLength();

        for ($i = 0; $i < $segmentCount; $i++) {
            if ($password) {
                $password .= $this->getSegmentSeparator();
            }

            for ($j = 0; $j < $segmentLength; $j++) {
                $password .= $characterList[mt_rand(0, $characters - 1)];
            }
        }

        return $password;
    }

    /**
     * Get number of words in desired password
     *
     * @return integer
     */
    public function getLength()
    {
        return $this->getSegmentCount();
    }

    /**
     * Set length of desired password(s)
     *
     * @param integer $characterCount
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setLength($characterCount)
    {
        $this->setSegmentCount($characterCount);

        return $this;
    }

    /**
     * Get number of segments in desired password
     *
     * @return integer
     */
    public function getSegmentCount()
    {
        return $this->getOptionValue(self::OPTION_COUNT);
    }

    /**
     * Set number of segments in desired password(s)
     *
     * @param integer $segmentCount
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setSegmentCount($segmentCount)
    {
        if (!is_int($segmentCount) || $segmentCount < 1) {
            throw new \InvalidArgumentException('Expected positive integer');
        }

        $this->setOptionValue(self::OPTION_COUNT, $segmentCount);

        return $this;
    }

    /**
     * Get number of segments in desired password
     *
     * @return integer
     */
    public function getSegmentLength()
    {
        return $this->getOptionValue(self::OPTION_LENGTH);
    }

    /**
     * Set length of segment
     *
     * @param integer $segmentLength
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setSegmentLength($segmentLength)
    {
        if (!is_int($segmentLength) || $segmentLength < 1) {
            throw new \InvalidArgumentException('Expected positive integer');
        }

        $this->setOptionValue(self::OPTION_LENGTH, $segmentLength);

        return $this;
    }

    /**
     * Get Segment Separator
     *
     * @return string
     */
    public function getSegmentSeparator()
    {
        return $this->getParameter(self::PARAMETER_SEPARATOR);
    }

    /**
     * Set segment separator
     *
     * @param string $segmentSeparator
     *
     * @return \Hackzilla\PasswordGenerator\Generator\HybridPasswordGenerator
     * @throws \InvalidArgumentException
     */
    public function setSegmentSeparator($segmentSeparator)
    {
        if (!is_string($segmentSeparator)) {
            throw new \InvalidArgumentException('Expected string');
        }

        $this->setParameter(self::PARAMETER_SEPARATOR, $segmentSeparator);

        return $this;
    }
}
