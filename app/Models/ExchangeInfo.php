<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 12 Jan 2018 02:21:00 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExchangeInfo
 * 
 * @property int $id
 * @property string $symbol
 * @property string $base_asset
 * @property string $quote_asset
 * @property float $min_price
 * @property float $tick_size
 * @property float $min_qty
 * @property float $step_size
 * @property float $min_notional
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class ExchangeInfo extends Eloquent
{
	protected $table = 'exchange_info';

	protected $casts = [
		'min_price' => 'float',
		'tick_size' => 'float',
		'min_qty' => 'float',
		'step_size' => 'float',
		'min_notional' => 'float'
	];

	protected $fillable = [
		'symbol',
		'base_asset',
		'quote_asset',
		'min_price',
		'tick_size',
		'min_qty',
		'step_size',
		'min_notional'
	];
}
