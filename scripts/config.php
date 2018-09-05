<?php
	/////// Debugging mode `true` OR `false` - print `whattomine.com` Coin Profitability List	
	$DEBUG = "false";	

	/////// HiveOS farm and worker name
	$FARM = "INSERT_HERE_YOUR_FARM_NAME";
	$WORKER = "INSERT_HERE_YOUR_WORKER_NAME";

	//////// Time in seconds for the next WTM check-point
	$SLEEP = 300;

 	/////// Telegram notification-bot settings
	$CHATID = "INSERT_HERE_YOUR_CHATID";
	$KEY = "INSERT_HERE_YOUR_TELEGRAM_API_KEY";
	
	/////// Do not edit the next two lines
	$TIME = "15";
	$URL = "https://api.telegram.org/bot$KEY/sendMessage"; 

	/////// Define the greater profit percentage for autoswitching
	$GREATERPROFIT = 10;

	///////// Run HIVE-overclock after config switch
	define('RUN_HIVE_OVERCLOCK', FALSE); 

	/////// API URL for looking up current prices
	/////// Do not forget to add `.json` after `/coins` in the next line)
	define('WTM_API_URL', 'https://whattomine.com/coins.json?utf8=✓&adapt_q_280x=0&adapt_q_380=0&adapt_q_fury=0&adapt_q_470=0&adapt_q_480=3&adapt_q_570=0&adapt_q_580=0&adapt_q_vega56=0&adapt_q_vega64=0&adapt_q_750Ti=0&adapt_q_1050Ti=0&adapt_q_10606=1.18&adapt_q_1070=7.93&adapt_q_1070Ti=0&adapt_q_1080=0&adapt_q_1080Ti=0&eth=true&factor[eth_hr]=247.0&factor[eth_p]=972.0&zh=true&factor[zh_hr]=320.0&factor[zh_p]=1060.0&factor[phi_hr]=45.0&factor[phi_p]=390.0&factor[cnh_hr]=2850.0&factor[cnh_p]=330.0&factor[cn7_hr]=2580.0&factor[cn7_p]=330.0&eq=true&factor[eq_hr]=3650.0&factor[eq_p]=1080.0&lre=true&factor[lrev2_hr]=285000.0&factor[lrev2_p]=1070.0&ns=true&factor[ns_hr]=8050.0&factor[ns_p]=1030.0&factor[tt10_hr]=27.0&factor[tt10_p]=450.0&x16r=true&factor[x16r_hr]=90.0&factor[x16r_p]=940.0&factor[l2z_hr]=1.35&factor[l2z_p]=360.0&factor[phi2_hr]=0.0&factor[phi2_p]=0.0&factor[xn_hr]=4.8&factor[xn_p]=360.0&factor[cost]=0.135&sort=Profit&volume=0&revenue=current&factor[exchanges][]=&factor[exchanges][]=binance&factor[exchanges][]=bitfinex&factor[exchanges][]=bittrex&factor[exchanges][]=cryptobridge&factor[exchanges][]=cryptopia&factor[exchanges][]=hitbtc&factor[exchanges][]=poloniex&factor[exchanges][]=yobit&dataset=Main&commit=Calculate');

?>