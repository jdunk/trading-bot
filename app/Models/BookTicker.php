<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 18 Jan 2018 05:03:33 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class BookTicker
 * 
 * @property int $id
 * @property string $symbol
 * @property \Carbon\Carbon $datetime_
 * @property float $bid_price
 * @property float $ask_price
 * @property float $gap_perc
 * @property float $gap_perc_avg_4
 * @property float $gap_perc_avg_9
 * @property float $gap_perc_ewa_4
 * @property float $gap_perc_ewa_9
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class BookTicker extends Eloquent
{
	protected $table = 'book_ticker';

	protected $casts = [
		'bid_price' => 'float',
		'ask_price' => 'float',
		'gap_perc' => 'float',
		'gap_perc_avg_4' => 'float',
		'gap_perc_avg_9' => 'float',
		'gap_perc_ewa_4' => 'float',
		'gap_perc_ewa_9' => 'float'
	];

	protected $dates = [
		'datetime_'
	];

	protected $fillable = [
		'symbol',
		'datetime_',
		'bid_price',
		'ask_price',
		'gap_perc',
		'gap_perc_avg_4',
		'gap_perc_avg_9',
		'gap_perc_ewa_4',
		'gap_perc_ewa_9'
	];
}
