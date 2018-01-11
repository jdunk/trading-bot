<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 12 Jan 2018 02:21:00 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Candlesticks1m
 * 
 * @property int $id
 * @property string $symbol
 * @property \Carbon\Carbon $datetime
 * @property float $open
 * @property float $high
 * @property float $low
 * @property float $close
 * @property float $volume
 * @property float $perc_change_vs_1
 * @property float $perc_change_vs_2
 * @property float $perc_change_vs_3
 * @property float $perc_change_vs_4
 * @property float $ma4
 * @property float $ma9
 * @property float $ma20
 * @property float $ema4
 * @property float $ema5
 * @property float $ema9
 * @property float $wma4
 * @property float $wma9
 * @property float $wma20
 * @property float $wema4
 * @property float $wema5
 * @property float $wema9
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class Candlesticks1m extends Eloquent
{
	protected $table = 'candlesticks_1m';

	protected $casts = [
		'open' => 'float',
		'high' => 'float',
		'low' => 'float',
		'close' => 'float',
		'volume' => 'float',
		'perc_change_vs_1' => 'float',
		'perc_change_vs_2' => 'float',
		'perc_change_vs_3' => 'float',
		'perc_change_vs_4' => 'float',
		'ma4' => 'float',
		'ma9' => 'float',
		'ma20' => 'float',
		'ema4' => 'float',
		'ema5' => 'float',
		'ema9' => 'float',
		'wma4' => 'float',
		'wma9' => 'float',
		'wma20' => 'float',
		'wema4' => 'float',
		'wema5' => 'float',
		'wema9' => 'float'
	];

	protected $dates = [
		'datetime'
	];

	protected $fillable = [
		'symbol',
		'datetime',
		'open',
		'high',
		'low',
		'close',
		'volume',
		'perc_change_vs_1',
		'perc_change_vs_2',
		'perc_change_vs_3',
		'perc_change_vs_4',
		'ma4',
		'ma9',
		'ma20',
		'ema4',
		'ema5',
		'ema9',
		'wma4',
		'wma9',
		'wma20',
		'wema4',
		'wema5',
		'wema9'
	];
}
