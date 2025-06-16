<?php

/**
 * This file is part of web3.php package.
 * @author Peter Lai
 * @license MIT
 */

namespace Tatum\Cryptography\Web3\Methods\Eth;
!defined("TATUM-SDK") && exit();

use InvalidArgumentException;
use Tatum\Cryptography\Web3\Methods\EthMethod;
use Tatum\Cryptography\Web3\Validators\BlockHashValidator;
use Tatum\Cryptography\Web3\Formatters\HexFormatter;
use Tatum\Cryptography\Web3\Formatters\BigNumberFormatter;

class GetBlockTransactionCountByHash extends EthMethod {
    /**
     * validators
     *
     * @var array
     */
    protected $validators = [BlockHashValidator::class];

    /**
     * inputFormatters
     *
     * @var array
     */
    protected $inputFormatters = [HexFormatter::class];

    /**
     * outputFormatters
     *
     * @var array
     */
    protected $outputFormatters = [BignumberFormatter::class];

    /**
     * defaultValues
     *
     * @var array
     */
    protected $defaultValues = [];

    /**
     * construct
     *
     * @param string $method
     * @param array $arguments
     * @return void
     */
    // public function __construct($method='', $arguments=[])
    // {
    //     parent::__construct($method, $arguments);
    // }
}
