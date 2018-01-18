<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 18 Jan 2018 05:03:33 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class CoinPerformance
 * 
 * @property int $id
 * @property string $symbol
 * @property float $perc_change_5m
 * @property float $perc_change_15m
 * @property float $perc_change_30m
 * @property float $perc_change_2h
 * @property float $perc_change_6h
 * @property float $perc_change_12h
 * @property float $volume_5m
 * @property float $volume_15m
 * @property float $volume_30m
 * @property float $volume_2h
 * @property float $volume_6h
 * @property float $volume_12h
 * @property float $hot_score1
 * @property float $hot_score2
 * @property float $hot_score3
 * @property float $hot_score4
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class CoinPerformance extends Eloquent
{
	protected $table = 'coin_performance';

	protected $casts = [
		'perc_change_5m' => 'float',
		'perc_change_15m' => 'float',
		'perc_change_30m' => 'float',
		'perc_change_2h' => 'float',
		'perc_change_6h' => 'float',
		'perc_change_12h' => 'float',
		'volume_5m' => 'float',
		'volume_15m' => 'float',
		'volume_30m' => 'float',
		'volume_2h' => 'float',
		'volume_6h' => 'float',
		'volume_12h' => 'float',
		'hot_score1' => 'float',
		'hot_score2' => 'float',
		'hot_score3' => 'float',
		'hot_score4' => 'float'
	];

	protected $fillable = [
		'symbol',
		'perc_change_5m',
		'perc_change_15m',
		'perc_change_30m',
		'perc_change_2h',
		'perc_change_6h',
		'perc_change_12h',
		'volume_5m',
		'volume_15m',
		'volume_30m',
		'volume_2h',
		'volume_6h',
		'volume_12h',
		'hot_score1',
		'hot_score2',
		'hot_score3',
		'hot_score4'
	];
}
