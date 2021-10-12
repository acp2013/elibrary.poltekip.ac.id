        if ($pos == 0) {
                    $code = array_search("START B", $this->_charSets['B']);
                } else {
                    $code = array_search("Code B", $this->_charSets[$currentCharset]);
                }
                $result[] = $code;
                $currentCharset = 'B';
            } else if (array_key_exists($char, $this->_charSets['A']) && $currentCharset != 'A'
                  && !(array_key_exists($char, $this->_charSets['B']) && $currentCharset == 'B')) {
                /**
                 * Switch to C as C contains the char and C is not the current charset.
                 */
                if ($pos == 0) {
                    $code = array_search("START A", $this->_charSets['A']);
                } else {
                    $code =array_search("Code A", $this->_charSets[$currentCharset]);
                }
                $result[] = $code;
                $currentCharset = 'A';
            }

            if ($currentCharset == 'C') {
                $code = array_search(substr($string, $pos, 2), $this->_charSets['C']);
                $pos++; //Two chars from input
            } else {
                $code = array_search($string[$pos], $this->_charSets[$currentCharset]);
            }
            $result[] = $code;
        }

        $this->_convertedText[md5($string)] = $result;
        return $result;
    }

    /**
     * Set text to encode
     * @param string $value
     * @return Zend_Barcode_Object
     */
    public function setText($value)
    {
        $this->_text = $value;
        return $this;
    }

    /**
     * Retrieve text to encode
     * @return string
     */
    public function getText()
    {
        return $this->_text;
    }

    /**
     * Get barcode checksum
     *
     * @param  string $text
     * @return int
     */
    public function getChecksum($text)
    {
        $tableOfChars = $this->_convertToBarcodeChars($text);

        $sum = $tableOfChars[0];
        unset($tableOfChars[0]);

        $k = 1;
        foreach ($tableOfChars as $char) {
            $sum += ($k++) * $char;
        }

        $checksum = $sum % 103;

        return $checksum;
    }

    /**
     * Standard validation for most of barcode objects
     * @param string $value
     * @param array  $options
     */
    protected function _validateText($value, $options = array())
    {
        // @TODO: add code128 validator
        return true;
    }
}
