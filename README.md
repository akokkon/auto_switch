#### `AUTO_SWITCH` switches automatically the HiveOS 2.0 miners to the most profitable coin accord to whattomine.com profitability calculations   

#### Version 0.5-2018/08/30 is developed by Dim.Kokkonos (kokkonos) & Ant.Kokkonos (akokkon) (`https://github.com/akokkon/auto_switch`)

#### This `auto_switch` script is a fork of the `auto_switch for ethOS` by `allcrypto` (`https://github.com/allcrypto/auto_switch`)		  

### Installation

Run in HiveOS terminal:

`git clone https://github.com/akokkon/auto_switch.git`

Optional but strongly recommended is the installation of `leafpad` and `pcmanfm`, if you do not prefer to use HiveOS' terminal and subsequentlly `nano` as command line editor:

`sudo apt-get install leafpad`	(xhive text editor) 

`sudo apt-get update`

`sudo apt-get install pcmanfm` 	(xhive file manager)

`sudo apt-get update`


### Configuration for NVIDIA GPUs

** To enable a new coin you have to place:

	a. a config file in the `configs` folder
	
  	b. a config file -with the same name as the above- in the `overclockings` folder

** Steps to create config files:

	a. from HiveOS web Interface launch the `flight-sheet` of the coin that you prefer to mine (e.g. ETH)
	
	b. from terminal run `pcmanfm` and then open `wallet.conf` (path: /hive-config/wallet.conf)
	
	c. `edit` the 9th line of `wallet.conf` in this way: 
	   MINER="insert_the_name_of_miner" (e.g. MINER="ethminer")
	   
	d. `save as` the `wallet.conf` file to the path `/auto_switch/configs` with filename in this format: `poolname-TAG_OF_COIN.conf` (e.g. dwarfpool-ETH.conf)
	
	e. for nicehash you have to follow this format: `poolname-TAG_OF_ALGO.conf` (daggerhashimoto-NICEHASH.conf and then daggerhashimoto-ETHASH.conf or daggerhashimoto-EQUIHASH.conf or daggerhashimoto-NeoScrypt.conf ...)
	
	f. from `pcmanfm` open `nvidia-oc.conf` (path: `/hive-config/nvidia-oc.conf`)
	
	g. `save as` the `nvidia-oc.conf` file to the path `/auto_switch/overclockings` with filename: `poolname-ETH.conf` (e.g. dwarfpool-ETH.conf) *** I do not know about AMD GPUs and if there is -in the same way- an `amd-oc.conf` file to `save as` it to the `overclockings` folder ***

** The script will automatically use the tag in the filename to apply the `config` for the specific coin.

	e.g. poolname-ETH.conf = ETH
	
     	poolname-RVN.conf = KMD


### To define your personal settings for auto_switching -to `/scripts/` folder- in `config.php` file:

	a. Insert time in seconds for the next whattomine check-point 
	
	b. Enter Telegram-bot notification settings (chatid, key)
	
	c. Define the percentage of profit above that the system switches to the most profitable coin (greater_profit)
	
	d. Define the WTM URL for looking up current prices from whattomine.com. 
	
	   Enter parameters about your setup, and then parse it according to what you're looking for ... So select your video cards, hashrate and power cost, click calculate, then copy the link and add ".json" after "coins" and you will get output based on what you fed into it.


### Logs

After every coin auto_switch the miner-log is being copied to the `/logs/` folder. That way you can more easily track if a `config` file wasn't working.

There's also a main log of the script itself which is written to `/scripts/log`


### Telegram notifications

After every coin auto_switch you will be notified through the telegram bot you have start in this format:

`HiveOS switched to COIN_TAG (RPOFIT_PERCENT)` (e.g. `HiveOS switched to RVN (125%)`)

To create your telegram-bot you can follow this guide: `https://tutorials.botsfloor.com/creating-a-bot-using-the-telegram-bot-api-5d3caed3266d?gi=45407e1d0131` (at the end of this process you will get and write down the telegram-bot API KEY)

To get your `chatid` you have to open `https://telegram.me/myidbot` --> `open in web` and after authorization send `/getid`.


### To start auto-switch 

In terminal window run: 

/auto_switch/scripts/main.sh


### To stop auto-switch

Close the terminal window in which run auto-switch 


### To auto-start auto-switch on HiveOS boot

step 1: 

`cd /etc/xdg/autostart`

`nano auto_switch.desktop`

step 2:

Copy & paste the following seven (7) lines in `auto_switch.desktop`

[Desktop Entry]

Type=Application

Name=auto_switch-miner-akokkon

Terminal=false 

Exec=sudo lxterminal --geometry=106x42 -e /auto_switch/scripts/main.sh

Icon=system-run

X-GNOME-Autostart-enabled=true

step 3: 

press `ctrl+X` to save `auto_switch.desktop` and `Y` to confirm.


### Donations welcome

Consider sending us a cup of coffee, if you want to support further development of this script.

BTC: `31hpDZyVTuPtMnJZhnF79sjDPUh25z7NLi`

ETH: `0x88a62b84e9972935498d0be563549360f4665eac`

ZEC: `t1MwUEWF9RDRYzJamVwPujYeZnfdA8UnT97`

