 = false;

        switch ($roundingMode) {
            case RoundingMode::UNNECESSARY:
                if ($hasDiscardedFraction) {
                    throw RoundingNecessaryException::roundingNecessary();
                }
                break;

            case RoundingMode::UP:
                $increment = $hasDiscardedFraction;
                break;

            case RoundingMode::DOWN:
                break;

            case RoundingMode::CEILING:
                $increment = $hasDiscardedFraction && $isPositiveOrZero;
                break;

            case RoundingMode::FLOOR:
                $increment = $hasDiscardedFraction && ! $isPositiveOrZero;
                break;

            case RoundingMode::HALF_UP:
                $increment = $discardedFractionSign() >= 0;
                break;

            case RoundingMode::HALF_DOWN:
                $increment = $discardedFractionSign() > 0;
                break;

            case RoundingMode::HALF_CEILING:
                $increment = $isPositiveOrZero ? $discardedFractionSign() >= 0 : $discardedFractionSign() > 0;
                break;

            case RoundingMode::HALF_FLOOR:
                $increment = $isPositiveOrZero ? $discardedFractionSign() > 0 : $discardedFractionSign() >= 0;
                break;

            case RoundingMode::HALF_EVEN:
                $lastDigit = (int) $quotient[-1];
                $lastDigitIsEven = ($lastDigit % 2 === 0);
                $increment = $lastDigitIsEven ? $discardedFractionSign() > 0 : $discardedFractionSign() >= 0;
                break;

            default:
                throw new \InvalidArgumentException('Invalid rounding mode.');
        }

        if ($increment) {
            return $this->add($quotient, $isPositiveOrZero ? '1' : '-1');
        }

        return $quotient;
    }

    /**
     * Calculates bitwise AND of two numbers.
     *
     * This method can be overridden by the concrete implementation if the underlying library
     * has built-in support for bitwise operations.
     *
     * @param string $a
     * @param string $b
     *
     * @return string
     */
    public function and(string $a, string $b) : string
    {
        return $this->bitwise('and', $a, $b);
    }

    /**
     * Calculates bitwise OR of two numbers.
     *
     * This method can be overridden by the concrete implementation if the underlying library
     * has built-in support for bitwise operations.
     *
     * @param string $a
     * @param string $b
     *
     * @return string
     */
    public function or(string $a, string $b) : string
    {
        return $this->bitwise('or', $a, $b);
    }

    /**
     * Calculates bitwise XOR of two numbers.
     *
     * This method can be overridden by the concrete implementation if the underlying library
     * has built-in support for bitwise operations.
     *
     * @param string $a
     * @param string $b
     *
     * @return string
     */
    public function xor(string $a, string $b) : string
    {
        return $this->bitwise('xor', $a, $b);
    }

    /**
     * Performs a bitwise operation on a decimal number.
     *
     * @param string $operator The operator to use, must be "and", "or" or "xor".
     * @param string $a        The left operand.
     * @param string $b        The right operand.
     *
     * @return string
     */
    private function bitwise(string $operator, string $a, string $b) : string
    {
        [$aNeg, $bNeg, $aDig, $bDig] = $this->init($a, $b);

        $aBin = $this->toBinary($aDig);
        $bBin = $this->toBinary($bDig);

        $aLen = \strlen($aBin);
        $bLen = \strlen($bBin);

        if ($aLen > $bLen) {
            $bBin = \str_repeat("\x00", $aLen - $bLen) . $bBin;
        } elseif ($bLen > $aLen) {
            $aBin = \str_repeat("\x00", $bLen - $aLen) . $aBin;
        }

        if ($aNeg) {
            $aBin = $this->twosComplement($aBin);
        }
        if ($bNeg) {
            $bBin = $this->twosComplement($bBin);
        }

        switch ($operator) {
            case 'and':
                $value = $aBin & $bBin;
                $negative = ($aNeg and $bNeg);
                break;

            case 'or':
                $value = $aBin | $bBin;
                $negative = ($aNeg or $bNeg);
                break;

            case 'xor':
                $value = $aBin ^ $bBin;
                $negative = ($aNeg xor $bNeg);
                break;

            // @codeCoverageIgnoreStart
            default:
                throw new \InvalidArgumentException('Invalid bitwise operator.');
            // @codeCoverageIgnoreEnd
        }

        if ($negative) {
            $value = $this->twosComplement($value);
        }

        $result = $this->toDecimal($value);

        return $negative ? $this->neg($result) : $result;
    }

    /**
     * @param string $number A positive, binary number.
     *
     * @return string
     */
    private function twosComplement(string $number) : string
    {
        $xor = \str_repeat("\xff", \strlen($number));

        $number = $number ^ $xor;

        for ($i = \strlen($number) - 1; $i >= 0; $i--) {
            $byte = \ord($number[$i]);

            if (++$byte !== 256) {
                $number[$i] = \chr($byte);
                break;
            }

            $number[$i] = "\x00";

            if ($i === 0) {
                $number = "\x01" . $number;
            }
        }

        return $number;
    }

    /**
     * Converts a decimal number to a binary string.
     *
     * @param string $number The number to convert, positive or zero, only digits.
     *
     * @return string
     */
    private function toBinary(string $number) : string
    {
        $result = '';

        while ($number !== '0') {
            [$number, $remainder] = $this->divQR($number, '256');
            $result .= \chr((int) $remainder);
        }

        return \strrev($result);
    }

    /**
     * Returns the positive decimal representation of a binary number.
     *
     * @param string $bytes The bytes representing the number.
     *
     * @return string
     */
    private function toDecimal(string $bytes) : string
    {
        $result = '0';
        $power = '1';

        for ($i = \strlen($bytes) - 1; $i >= 0; $i--) {
            $index = \ord($bytes[$i]);

            if ($index !== 0) {
                $result = $this->add($result, ($index === 1)
                    ? $power
                    : $this->mul($power, (string) $index)
                );
            }

            if ($i !== 0) {
                $power = $this->mul($power, '256');
            }
        }

        return $result;
    }
}
