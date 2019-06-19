<?php

$sms = <<<SMS_TEXT
Пароль: 4138
Спишется 16р. 70коп.
Перевод на счет 4100175017390
SMS_TEXT;


function extract_sms_data ($sms_text) {
	$wallet = $code = $amount = '';

	$amount_suffixes = '(р|к)*';
	$regex_for_numbers = '/(\d+(\r|\n| [^'.$amount_suffixes.'])|(\d+((\.|,)\d{0,2})?(.+)('.$amount_suffixes.')))/';

	if (preg_match_all($regex_for_numbers, $sms_text, $matches) && isset($matches[0])) {
		foreach ($matches[0] as $k => $number)	 {
			if ((preg_match('/\d{11,20}/', $number) && !$wallet && ($wallet = $number)) ||
				(preg_match('/'.$amount_suffixes.'$/', $number) && !$code && ($code = $number))
			) {
				unset($matches[0][$k]);
			}
		}
		if ($wallet && $code && !$amount) {
			$amount = array_pop($matches[0]);
		}
	}

	return 'wallet='.$wallet.', code='.$code.', amount='.$amount;
}

echo extract_sms_data($sms);

?>
