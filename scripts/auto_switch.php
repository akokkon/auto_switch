<?php
	require('config.php');
	$farm = $FARM;
	$worker = $WORKER;
	$sleep = $SLEEP;
	$greaterprofit = $GREATERPROFIT;
	$chatid = $CHATID;
	$key = $KEY;
	$time = $TIME;
	$url = $URL;
	$debug = $DEBUG;
	$date = date('Y-m-d H:i:s');
	
	print ("[$date] INFO : HiveOS 2.0 mining auto-switcher for NVIDIA GPUs V0.5.2 \n");

class auto_switch {
		public $run_hive_overclock = FALSE;
		private $home_path = '';
		private $hive_path = '';
		private $coins;
		
		function __construct() {
			// PATH TO SCRIPT
			$this->home_path = dirname(dirname(__FILE__)).'/';
			$this->hive_path = '/hive-config/';
			// Change dir to root folder
			chdir($this->home_path);
		}
		
		public function run() {
			$this->load_coins();
			$this->profit_switch();
		}
		
		private function profit_switch() { 	
			global $debug; global $farm; global $worker; global $sleep; global $most_profit_percent; global $most_profit_algo; global $current_coin_tag; global $current_coin_percent;
			$json_coins = file_get_contents($this->wtm_api_url); //');
			$data_coins = json_decode($json_coins, true);
			$profits = FALSE;

			if(isset($data_coins['coins']) && count($data_coins['coins']) > 0) {		
				$date = date('Y-m-d H:i:s');	
				//print ("[$date] INFO : HiveOS 2.0 mining auto-switcher for NVIDIA GPUs V0.5.1 \n");
				if ($debug == "true") {
					print ("[$date] INFO : ===== Get-WTM-Profitability-List ===== \n");
					}
				$flg = 0; $flg1 = 0;	
				foreach($data_coins['coins'] as $label => $coin) {
					if(!isset($this->coins[$coin['tag']]))
						continue; // Skip unsupported coins.
					if($coin['lagging'])
						continue; // Skip lagging coins.
					$tag = $coin['tag'];
					$coin_id = $coin['id'];
					$coin_algo = $coin['algorithm'];
					$coin_profitability = $coin['profitability'];
					$profits[$tag] = floatval($coin['profitability']);
					if ($flg == 0) {
						$most_profit_tag = $tag;
						$most_profit_percent = $coin_profitability;
						$most_profit_algo =  $coin_algo;
						$flg = 1;
					}
					if ($tag == "NICEHASH") {
						if ($flg1 == 0) {
							$nicehash_profit = $coin_profitability;
							$nicehash_algo = $coin_algo;
							$flg1 = 1;
							$nicehash_str = $nicehash_algo;
							$tag = $nicehash_str;
							$profits[$tag] = $nicehash_profit;
							$current_coin_percent = $profits[$tag];
						}
					}
				
				// Print WTM-Profitability-List
				if ($debug == "true") {
					print ("                             * $profits[$tag]% $tag ($coin_algo) \n");

					}
				}
			}
			$current_coin = file_get_contents($this->home_path.'scripts/current_coin.txt');
				$flg2 = 0;
				foreach($data_coins['coins'] as $label => $coin) {
					if ($coin['tag'] == $current_coin) {
						$current_coin_tag = $coin['tag'];
						$current_coin_algo = $coin['algorithm'];
						$current_coin_percent = $coin['profitability'];
						if (($coin['tag'] == "NICEHASH") && ($current_coin == "Equihash" OR "Ethash" OR "X16R" OR "NeoScrypt" OR "Lyra2REv2")){
							if ($flg2 == 0) {
							$nicehash_profit = $coin['profitability'];
							$nicehash_algo = $coin['algorithm'];
							$nicehash_str = $nicehash_algo;
							$flg2 = 1;
							$current_coin_tag = $nicehash_str;
							$current_coin_algo = $nicehash_algo;
							$current_coin_percent = $nicehash_profit;
							break;
							}
						}
					
					} 
				}
			print ("[$date] INFO : ===== Get-Current-Stats ===== \n");
			print ("[$date] INFO : Farm-Name            : $farm\n");
			print ("[$date] INFO : Worker-Name          : $worker\n");
			print ("[$date] INFO : WTM Check-Period     : $sleep secs \n");
			print ("[$date] INFO : Current-Coin         : $current_coin ($current_coin_percent%)\n");
	
			if($profits && count($profits) > 0) {				
				// Sort by profit (reverse)
				global $new_profit;
				uasort($profits, array($this, 'cb_float_rsort'));
				$new_coin = key($profits);
				$new_profit = current($profits);
				if($this->switch_coin($new_coin)) {
					global $chatid; global $key; global $url; global $time;
					// Telegram bot notifications
					$text = ("$farm: $worker switched to $new_coin ($new_profit%)");
					$this->write_log(date('Y-m-d H:i:s')." - $farm: $worker auto-switched to $new_coin ($new_profit%)\r\n");
					shell_exec('curl -s --max-time ' .$time. ' -d "chat_id=' .$chatid. '&disable_web_page_preview=1&text=' .$text. '" ' .$url. ' >/dev/null 2>&1');
					
					sleep(1); 
				}
			}
		}
		// Load all configs ( Coins )
		private function load_coins() {
			$this->coins = FALSE;
			$files = scandir($this->home_path.'configs/');
			// Remove folders
			foreach ($files as $key => $link)
				if(!is_file($this->home_path.'configs/'.$link))
					unset($files[$key]);
			foreach($files as $filename) {
				if(strpos($filename, '-') === FALSE)
					continue;
				$split = explode('-', $filename);
				$pool = $split[0];
				$tag = $split[1];
				if(strpos($tag, '.'))
					$tag = substr($tag, 0, strpos($tag, '.'));
					$this->coins[strtoupper($tag)] = array('config' => $filename, 'folder_path' => '');
			}
		}

		// Check if auto_switch was disabled with a file
		private function auto_switch_disabled() {
			return file_exists($this->home_path.'scripts/no-autoswitch');
		}
			
		private function switch_coin($new_coin) {
			if(file_exists($this->home_path.'scripts/current_coin.txt')) {
				global $current_coin_percent; global $sleep; global $farm; global $worker; global $greaterprofit; global $new_profit; $date = date('Y-m-d H:i:s');
				// Coin already active? Nothing to do..
				$current_coin = file_get_contents($this->home_path.'scripts/current_coin.txt');

				if(($new_coin == $current_coin) OR ($new_profit < $current_coin_percent+$greaterprofit)) {
					print ("[$date] INFO : Most-Profitable-Coin : $new_coin ($new_profit%) \n");
					print ("[$date] INFO : $worker continues mining \e[36m**** $current_coin ($current_coin_percent%) **** \e[0m\n\n");
					return FALSE;
				}
				print ("[$date] INFO : Most-Profitable-Coin : $new_coin ($new_profit%) \n");
				// Log mining output from previous mining
				copy('/run/hive/miner.1', $this->home_path.'logs/'.$current_coin.'.log');
			}
			// Switch coin
			$date = date('Y-m-d H:i:s'); global $most_profit_percent; global $most_profit_algo; global $current_coin_tag; global $current_coin_percent;
			file_put_contents($this->home_path.'scripts/current_coin.txt', $new_coin, LOCK_EX);
		isset($config_file);
		$config_file = $this->coins[$new_coin]['config'];
			if ($new_coin == 'Ethash') {
				$config_file = "daggerhashimoto-ETHASH.conf";
			}
			else if ($new_coin == 'Equihash') {
				$config_file = "daggerhashimoto-EQUIHASH.conf";
			}
			else if ($new_coin == 'Lyra2REv2') {
				$config_file = "daggerhashimoto-LYRA2REV2.conf";
			}
			else if ($new_coin == 'NeoScrypt') {
				$config_file = "daggerhashimoto-NeoScrypt.conf";
			}
			else if ($new_coin == 'X16R') {
				$config_file = "daggerhashimoto-X16R.conf";
			}
			else {
				$config_file = $this->coins[$new_coin]['config'];
			}
			copy($this->home_path."configs/".$config_file, $this->hive_path.'wallet.conf');
			print ("[$date] INFO : Applied -$config_file- to -wallet.conf-\n");
			sleep(1);
			copy($this->home_path."overclockings/".$config_file, $this->hive_path.'nvidia-oc.conf');
			print ("[$date] INFO : Applied -$config_file- to -nVidia-oc.conf- \n");
			sleep(1);
			$output = shell_exec('/hive/bin/miner stop');
			sleep(1);
			$this->output("[$date] INFO : $output \e[1A");
			shell_exec('/hive/sbin/nvidia-oc');
			sleep(1);
			print ("[$date] INFO : Applied -$config_file- OC to GPUs \n");
			$output = shell_exec('/hive/bin/miner start');
                        sleep(1);
			$this->output("[$date] INFO : $output \e[1A");
			if($this->run_hive_overclock) {
				shell_exec('/hive/sbin/nvidia-oc-log'); 
				$this->output("[$date] INFO : Applied -$config_file- OC to -nVidia-oc.conf- \n");
			}
				$this->output("[$date] INFO : $worker auto-switched to \e[32m**** $new_coin $new_profit% **** \e[0m\n");

			return TRUE;
		}

		/////////////////
		//// HELPERS

		private function output($message) {
			echo "$message\r\n";
		}

		private function write_log($log) {
			file_put_contents($this->home_path.'scripts/log', $log, FILE_APPEND | LOCK_EX);
		}

		public function cb_float_rsort($a, $b) {
			if ($a == $b) {
				return 0;
			}
			return ($a > $b) ? -1 : 1;
		}
	}
	$flg3=0;

	do {
		$app = new auto_switch();
		$app->run_hive_overclock = RUN_HIVE_OVERCLOCK;
		$app->wtm_api_url = WTM_API_URL;
		$app->run();
		$flg3++;
		$date = date('Y-m-d H:i:s');
		sleep($sleep);
	} 
	while ($flg3 > 0);
	
?>