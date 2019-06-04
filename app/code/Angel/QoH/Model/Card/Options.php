<?php
/**
 * Angel Queen of Hearts
 * Copyright (C) 2018 Mark Wolf
 *
 * This file included in Angel/QoH is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Angel\QoH\Model\Card;

class Options extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    const TYPES = ['clubs', 'diamonds', 'hearts', 'spades'];
    const NUMBERS = ['ace', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'jack', 'queen', 'king'];
    const IMG_PATH= 'pub/media/Angel/QoH/img/cards/';

    const NO_CARD = 0;
    const ACE_CLUBS = 1;
    const TWO_CLUBS = 2;
    const THREE_CLUBS = 3;
    const FOUR_CLUBS = 4;
    const FIVE_CLUBS = 5;
    const SIX_CLUBS = 6;
    const SEVEN_CLUBS = 7;
    const EIGHT_CLUBS = 8;
    const NINE_CLUBS = 9;
    const TEN_CLUBS = 10;
    const JACK_CLUBS = 11;
    const QUEEN_CLUBS = 12;
    const KING_CLUBS = 13;
    const ACE_DIAMONDS = 14;
    const TWO_DIAMONDS = 15;
    const THREE_DIAMONDS = 16;
    const FOUR_DIAMONDS = 17;
    const FIVE_DIAMONDS = 18;
    const SIX_DIAMONDS = 19;
    const SEVEN_DIAMONDS = 20;
    const EIGHT_DIAMONDS = 21;
    const NINE_DIAMONDS = 22;
    const TEN_DIAMONDS = 23;
    const JACK_DIAMONDS = 24;
    const QUEEN_DIAMONDS = 25;
    const KING_DIAMONDS = 26;
    const ACE_HEARTS = 27;
    const TWO_HEARTS = 28;
    const THREE_HEARTS = 29;
    const FOUR_HEARTS = 30;
    const FIVE_HEARTS = 31;
    const SIX_HEARTS = 32;
    const SEVEN_HEARTS = 33;
    const EIGHT_HEARTS = 34;
    const NINE_HEARTS = 35;
    const TEN_HEARTS = 36;
    const JACK_HEARTS = 37;
    const QUEEN_HEARTS = 38;
    const KING_HEARTS = 39;
    const ACE_SPADES = 40;
    const TWO_SPADES = 41;
    const THREE_SPADES = 42;
    const FOUR_SPADES = 43;
    const FIVE_SPADES = 44;
    const SIX_SPADES = 45;
    const SEVEN_SPADES = 46;
    const EIGHT_SPADES = 47;
    const NINE_SPADES = 48;
    const TEN_SPADES = 49;
    const JACK_SPADES = 50;
    const QUEEN_SPADES = 51;
    const KING_SPADES = 52;
    const JOCKER_A = 53;
    const JOCKER_B = 54;

    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [
            ['value' => self::NO_CARD, 'label' => __('No Card')],
            ['value' => self::ACE_CLUBS, 'label' => __('Ace Clubs')],
            ['value' => self::TWO_CLUBS, 'label' => __('Two Clubs')],
            ['value' => self::THREE_CLUBS, 'label' => __('Three Clubs')],
            ['value' => self::FOUR_CLUBS, 'label' => __('Four Clubs')],
            ['value' => self::FIVE_CLUBS, 'label' => __('Five Clubs')],
            ['value' => self::SIX_CLUBS, 'label' => __('Six Clubs')],
            ['value' => self::SEVEN_CLUBS, 'label' => __('Seven Clubs')],
            ['value' => self::EIGHT_CLUBS, 'label' => __('Eight Clubs')],
            ['value' => self::NINE_CLUBS, 'label' => __('Nine Clubs')],
            ['value' => self::TEN_CLUBS, 'label' => __('Ten Clubs')],
            ['value' => self::JACK_CLUBS, 'label' => __('Jack Clubs')],
            ['value' => self::QUEEN_CLUBS, 'label' => __('Queen Clubs')],
            ['value' => self::KING_CLUBS, 'label' => __('King Clubs')],

            ['value' => self::ACE_DIAMONDS, 'label' => __('Ace Diamonds')],
            ['value' => self::TWO_DIAMONDS, 'label' => __('Two Diamonds')],
            ['value' => self::THREE_DIAMONDS, 'label' => __('Three Diamonds')],
            ['value' => self::FOUR_DIAMONDS, 'label' => __('Four Diamonds')],
            ['value' => self::FIVE_DIAMONDS, 'label' => __('Five Diamonds')],
            ['value' => self::SIX_DIAMONDS, 'label' => __('Six Diamonds')],
            ['value' => self::SEVEN_DIAMONDS, 'label' => __('Seven Diamonds')],
            ['value' => self::EIGHT_DIAMONDS, 'label' => __('Eight Diamonds')],
            ['value' => self::NINE_DIAMONDS, 'label' => __('Nine Diamonds')],
            ['value' => self::TEN_DIAMONDS, 'label' => __('Ten Diamonds')],
            ['value' => self::JACK_DIAMONDS, 'label' => __('Jack Diamonds')],
            ['value' => self::QUEEN_DIAMONDS, 'label' => __('Queen Diamonds')],
            ['value' => self::KING_DIAMONDS, 'label' => __('King Diamonds')],

            ['value' => self::ACE_HEARTS, 'label' => __('Ace Hearts')],
            ['value' => self::TWO_HEARTS, 'label' => __('Two Hearts')],
            ['value' => self::THREE_HEARTS, 'label' => __('Three Hearts')],
            ['value' => self::FOUR_HEARTS, 'label' => __('Four Hearts')],
            ['value' => self::FIVE_HEARTS, 'label' => __('Five Hearts')],
            ['value' => self::SIX_HEARTS, 'label' => __('Six Hearts')],
            ['value' => self::SEVEN_HEARTS, 'label' => __('Seven Hearts')],
            ['value' => self::EIGHT_HEARTS, 'label' => __('Eight Hearts')],
            ['value' => self::NINE_HEARTS, 'label' => __('Nine Hearts')],
            ['value' => self::TEN_HEARTS, 'label' => __('Ten Hearts')],
            ['value' => self::JACK_HEARTS, 'label' => __('Jack Hearts')],
            ['value' => self::QUEEN_HEARTS, 'label' => __('Queen Hearts')],
            ['value' => self::KING_HEARTS, 'label' => __('King Hearts')],

            ['value' => self::ACE_SPADES, 'label' => __('Ace Spades')],
            ['value' => self::TWO_SPADES, 'label' => __('Two Spades')],
            ['value' => self::THREE_SPADES, 'label' => __('Three Spades')],
            ['value' => self::FOUR_SPADES, 'label' => __('Four Spades')],
            ['value' => self::FIVE_SPADES, 'label' => __('Five Spades')],
            ['value' => self::SIX_SPADES, 'label' => __('Six Spades')],
            ['value' => self::SEVEN_SPADES, 'label' => __('Seven Spades')],
            ['value' => self::EIGHT_SPADES, 'label' => __('Eight Spades')],
            ['value' => self::NINE_SPADES, 'label' => __('Nine Spades')],
            ['value' => self::TEN_SPADES, 'label' => __('Ten Spades')],
            ['value' => self::JACK_SPADES, 'label' => __('Jack Spades')],
            ['value' => self::QUEEN_SPADES, 'label' => __('Queen Spades')],
            ['value' => self::KING_SPADES, 'label' => __('King Spades')],
            ['value' => self::JOCKER_A, 'label' => __('Joker')],
            ['value' => self::JOCKER_B, 'label' => __('Joker')]
        ];
        return $this->_options;
    }

    /**
     * get model option as array
     *
     * @return array
     */
    static public function getOptionArray()
    {
        return array(
            self::NO_CARD => __('No Card'),
            self::ACE_CLUBS => __('Ace Clubs'),
            self::TWO_CLUBS => __('Two Clubs'),
            self::THREE_CLUBS => __('Three Clubs'),
            self::FOUR_CLUBS => __('Four Clubs'),
            self::FIVE_CLUBS => __('Five Clubs'),
            self::SIX_CLUBS => __('Six Clubs'),
            self::SEVEN_CLUBS => __('Seven Clubs'),
            self::EIGHT_CLUBS => __('Eight Clubs'),
            self::NINE_CLUBS => __('Nine Clubs'),
            self::TEN_CLUBS => __('Ten Clubs'),
            self::JACK_CLUBS => __('Jack Clubs'),
            self::QUEEN_CLUBS => __('Queen Clubs'),
            self::KING_CLUBS => __('King Clubs'),
            self::ACE_DIAMONDS => __('Ace Diamonds'),
            self::TWO_DIAMONDS => __('Two Diamonds'),
            self::THREE_DIAMONDS => __('Three Diamonds'),
            self::FOUR_DIAMONDS => __('Four Diamonds'),
            self::FIVE_DIAMONDS => __('Five Diamonds'),
            self::SIX_DIAMONDS => __('Six Diamonds'),
            self::SEVEN_DIAMONDS => __('Seven Diamonds'),
            self::EIGHT_DIAMONDS => __('Eight Diamonds'),
            self::NINE_DIAMONDS => __('Nine Diamonds'),
            self::TEN_DIAMONDS => __('Ten Diamonds'),
            self::JACK_DIAMONDS => __('Jack Diamonds'),
            self::QUEEN_DIAMONDS => __('Queen Diamonds'),
            self::KING_DIAMONDS => __('King Diamonds'),
            self::ACE_HEARTS => __('Ace Hearts'),
            self::TWO_HEARTS => __('Two Hearts'),
            self::THREE_HEARTS => __('Three Hearts'),
            self::FOUR_HEARTS => __('Four Hearts'),
            self::FIVE_HEARTS => __('Five Hearts'),
            self::SIX_HEARTS => __('Six Hearts'),
            self::SEVEN_HEARTS => __('Seven Hearts'),
            self::EIGHT_HEARTS => __('Eight Hearts'),
            self::NINE_HEARTS => __('Nine Hearts'),
            self::TEN_HEARTS => __('Ten Hearts'),
            self::JACK_HEARTS => __('Jack Hearts'),
            self::QUEEN_HEARTS => __('Queen Hearts'),
            self::KING_HEARTS => __('King Hearts'),
            self::ACE_SPADES => __('Ace Spades'),
            self::TWO_SPADES => __('Two Spades'),
            self::THREE_SPADES => __('Three Spades'),
            self::FOUR_SPADES => __('Four Spades'),
            self::FIVE_SPADES => __('Five Spades'),
            self::SIX_SPADES => __('Six Spades'),
            self::SEVEN_SPADES => __('Seven Spades'),
            self::EIGHT_SPADES => __('Eight Spades'),
            self::NINE_SPADES => __('Nine Spades'),
            self::TEN_SPADES => __('Ten Spades'),
            self::JACK_SPADES => __('Jack Spades'),
            self::QUEEN_SPADES => __('Queen Spades'),
            self::KING_SPADES => __('King Spades'),
            self::JOCKER_A => __('Joker Red'),
            self::JOCKER_B => __('Joker Black')
        );
    }

    public static function isQoH($card){
        return $card == self::QUEEN_HEARTS;
    }

    public static function isJocker($card){
        return in_array($card, [self::JOCKER_A, self::JOCKER_B]);
    }

    public static function isFaceCard($card){
        return in_array($card, [
            self::JACK_CLUBS, self::JACK_DIAMONDS, self::JACK_HEARTS, self::JACK_SPADES,
            self::QUEEN_CLUBS, self::QUEEN_DIAMONDS, self::QUEEN_SPADES,
            self::KING_CLUBS, self::KING_DIAMONDS, self::KING_HEARTS, self::KING_SPADES
        ]);
    }

    public static function isACard($card){
        return in_array($card, [
            self::ACE_CLUBS, self::ACE_DIAMONDS, self::ACE_HEARTS, self::ACE_SPADES
        ]);
    }

    public static function isNumberCard($card){
        return in_array($card, [
            self::TWO_CLUBS, self::TWO_DIAMONDS, self::TWO_HEARTS, self::TWO_SPADES,
            self::THREE_CLUBS, self::THREE_DIAMONDS, self::THREE_HEARTS, self::THREE_SPADES,
            self::FOUR_CLUBS, self::FOUR_DIAMONDS, self::FOUR_HEARTS, self::FOUR_SPADES,
            self::FIVE_CLUBS, self::FIVE_DIAMONDS, self::FIVE_HEARTS, self::FIVE_SPADES,
            self::SIX_CLUBS, self::SIX_DIAMONDS, self::SIX_HEARTS, self::SIX_SPADES,
            self::SEVEN_CLUBS, self::SEVEN_DIAMONDS, self::SEVEN_HEARTS, self::SEVEN_SPADES,
            self::EIGHT_CLUBS, self::EIGHT_DIAMONDS, self::EIGHT_HEARTS, self::EIGHT_SPADES,
            self::NINE_CLUBS, self::NINE_DIAMONDS, self::NINE_HEARTS, self::NINE_SPADES,
            self::TEN_CLUBS, self::TEN_DIAMONDS, self::TEN_HEARTS, self::TEN_SPADES,
        ]);
    }

    /**
     * @return array
     */
    static public function getCardSrc(){
        $cardSrc = [];
        foreach (self::TYPES as $type){
            foreach (self::NUMBERS as $number){
                $cardSrc[] = $number.'_'.$type.'.png';
            }
        }
        $cardSrc[] = 'jocker_a.png';
        $cardSrc[] = 'jocker_b.png';
        return $cardSrc;
    }

    /**
     * get model option hash as array
     *
     * @return array
     */
    static public function getOptions()
    {
        $options = array();
        foreach (self::getOptionArray() as $value => $label) {
            $options[] = array(
                'value' => $value,
                'label' => $label
            );
        }
        return $options;
    }

    public function toOptionArray()
    {
        return self::getOptions();
    }
}
