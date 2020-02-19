<?php
    namespace App\Core;

    final class Field {
        private $pattern;
        private $editable;

        public function __construct(string $patt, bool $edit) {
            $this->pattern = $patt;
            $this->editable = $edit;
        }

        public function isValid(string $value) {
            return preg_match($this->pattern, $value);
        }

        public function isEditable() {
            return $this->editable;
        }

        public static function editableInteger(int $length): Field {
            return new Field('|^\-?[1-9][0-9]{0,' . ($length - 1) . '}$|', true);
        }

        public static function readonlyInteger(int $length): Field {
            return new Field('|^\-?[1-9][0-9]{0,' . ($length - 1) . '}$|', false);
        }

        public static function editableDecimal(int $length, int $decimals): Field {
            return new Field('|^\-?[1-9][0-9]{0,' . ($length - 1) . '}\.[0-9]{'. $decimals .'}$|', true);
        }

        public static function readonlyDecimal(int $length, int $decimals): Field {
            return new Field('|^\-?[1-9][0-9]{0,' . ($length - 1) . '}\.[0-9]{'. $decimals .'}$|', false);
        }

        public static function editableMaxDecimal(int $length, int $decimals): Field {
            return new Field('|^\-?[1-9][0-9]{0,' . ($length - 1) . '}\.[0-9]{0,'. $decimals .'}$|', true);
        }

        public static function readonlyMaxDecimal(int $length, int $decimals): Field {
            return new Field('|^\-?[1-9][0-9]{0,' . ($length - 1) . '}\.[0-9]{0,'. $decimals .'}$|', false);
        }

        public static function editableString(int $length): Field {
            return new Field('|^.{0,' . ($length - 1) . '}$|', true);
        }

        public static function readonlyString(int $length): Field {
            return new Field('|^.{0,' . ($length - 1) . '}$|', false);
        }

        public static function editableBit(): Field {
            return new Field('|^[01]$|', true);
        }

        public static function readonlyBit(): Field {
            return new Field('|^[01]$|', false);
        }
    }