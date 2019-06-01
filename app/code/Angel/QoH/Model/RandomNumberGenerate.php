<?php
namespace Angel\QoH\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

class RandomNumberGenerate
{
    /**
     * Return a random number in the specified range
     *
     * @param int $min
     * @param int $max
     * @return int  A random integer value between min (or 0) and max
     * @throws LocalizedException
     */
    public static function getWinningNumber($min = 0, $max = null)
    {
        if (null === $max) {
            $max = mt_getrandmax();
        }

        if ($max < $min) {
            throw new LocalizedException(new Phrase('Invalid range given.'));
        }

        return random_int($min, $max);
    }

    /**
     * @param array $drawnCard
     * @return int
     * @throws LocalizedException
     */
    public static function drawCard($drawnCard = []){
        $drawnCard = array_unique($drawnCard);
        if (count($drawnCard) >= 54){
            throw new LocalizedException(new Phrase('Invalid Drawn Card.'));
        }

        $cardNumber = self::getWinningNumber(1, 54);
        while (in_array($cardNumber, $drawnCard)){
            $cardNumber = self::getWinningNumber(1, 54);
        }
        return $cardNumber;
    }
}
