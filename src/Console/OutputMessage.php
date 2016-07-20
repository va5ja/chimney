<?php


namespace Plista\Chimney\Console;

/**
 *
 */
class OutputMessage
{
    const HR_DOUBLE = '====================';

    const HR_SINGLE = '--------------------';

    /**
     * @var string
     */
    private $text;

    /**
     * @return string
     */
    public function get() {
        return $this->text;
    }

    /**
     * Appends a text to the output message.
     * @param string $text
     */
    public function append($text) {
        $this->text .= $text;
    }

    /**
     * Appends a header to the output message.
     * @param string $text
     */
    public function appendHeader($text) {
        $this->append(PHP_EOL . '<info>' . self::HR_DOUBLE . PHP_EOL . $text . PHP_EOL . self::HR_DOUBLE . '</info>' . PHP_EOL);
    }

    /**
     * Appends a comment to the output message.
     * @param string $text
     */
    public function appendComment($text) {
        $this->append(PHP_EOL . '<comment>' . self::HR_SINGLE . PHP_EOL . $text . PHP_EOL . self::HR_DOUBLE . '</comment>' . PHP_EOL);
    }

    /**
     * Appends an error to the output message.
     * @param string $text
     */
    public function appendError($text) {
        $this->append(PHP_EOL . '<error>' . self::HR_SINGLE . PHP_EOL . $text . PHP_EOL . self::HR_DOUBLE . '</error>' . PHP_EOL);
    }

}
