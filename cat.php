<?php
parse_str(implode('&', array_slice($argv, 1)), $_GET);
if (empty($_GET["amount"])) {
	die("You must specify ");
}

function progressBar($done, $total) {
    $percentage = $done / $total * 100;
    $char_full = "░░";
    $char_empty = "██";
    $num = floor($percentage / 4);
    $nums = floor(25 - $num);
    $result = "";
    for ($i=0; $i < $num; $i++) { 
        $result = $result . $char_empty;
    }
    for ($i=0; $i < $nums; $i++) { 
        $result = $result . $char_full;
    }
    $result = $result . " - " . $percentage . "%";
    return $result;
}
function folderSize ($dir)
{
    $size = 0;

    foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
        $size += is_file($each) ? filesize($each) : folderSize($each);
    }

    return $size;
}

function human_filesize($bytes, $decimals = 2) {
    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}

function replaceCommandOutput(array $output) {
  static $oldLines = 0;
  $numNewLines = count($output) - 1;
 
  if ($oldLines == 0) {
    $oldLines = $numNewLines;
  }
 
  echo implode(PHP_EOL, $output);
  echo chr(27) . "[0G";
  echo chr(27) . "[" . $oldLines . "A";
 
  $numNewLines = $oldLines;
}
require("./vendor/autoload.php");
use Spatie\Async\Pool;

$files = glob('cats/*');
foreach($files as $file){
  if(is_file($file)) {
    unlink($file);
  }
}

$pool = Pool::create();
file_put_contents("i.txt", 0);
$b = 0;
echo "                  _                        " . PHP_EOL;
echo "                  \`*-.                    " . PHP_EOL;
echo "                   )  _`-.                 " . PHP_EOL;
echo "                  .  : `. .                " . PHP_EOL;
echo "                  : _   '  \               " . PHP_EOL;
echo "                  ; *` _.   `*-._          " . PHP_EOL;
echo "                  `-.-'          `-.       " . PHP_EOL;
echo "                    ;       `       `.     " . PHP_EOL;
echo "                    :.       .        \    " . PHP_EOL;
echo "                    . \  .   :   .-'   .   " . PHP_EOL;
echo "                    '  `+.;  ;  '      :   " . PHP_EOL;
echo "                    :  '  |    ;       ;-. " . PHP_EOL;
echo "                    ; '   : :`-:     _.`* ;" . PHP_EOL;
echo "                 .*' /  .*' ; .*`- +'  `*' " . PHP_EOL;
echo "                 `*-*   `*-*  `*-*'        " . PHP_EOL;
echo "========================" . PHP_EOL;
echo ">>> Catto downloader <<<" . PHP_EOL;
echo "========================" . PHP_EOL;
for ($b=0; $b < $_GET["amount"]; $b++) {
    $pool->add(function () use ($b) {
        $catpicurl = file_get_contents("https://api.mythicalkitten.com/cats/shadowcat");
        file_put_contents("cats/" . $b . ".png", file_get_contents($catpicurl));
    })->then(function ($output) {
        $loads = sys_getloadavg();
        $core_nums = trim(shell_exec("grep -P '^processor' /proc/cpuinfo|wc -l"));
        $load = round($loads[0]/($core_nums + 1)*100, 2);
        $i = file_get_contents("i.txt");
        $i++;
        $foldersize = folderSize("cats");
        $output = [];
        $output[] = "";
        $output[] = "Im eating so i'm leaving this run ok bye";
        $output[] = "";
        $output[] = 'Downloaded cattos: ';
        $output[] = progressBar($i, $_GET["amount"]);
        $output[] = "Downloaded: " . $i . "/" . $_GET["amount"];
        $output[] = "Total size: " . human_filesize($foldersize);
        $output[] = "Available space: " . human_filesize(disk_free_space("cats"));
        $output[] = "CPU usage: " . $load . "%";
        file_put_contents("i.txt", $i);
        replaceCommandOutput($output);
    })->catch(function (Throwable $exception) {
        // Handle exception
    });
	
}
$pool->wait();
for ($i=0; $i < 5; $i++) { 
	echo PHP_EOL;
}
echo "███╗   ███╗██╗ █████╗  ██████╗ ██╗   ██╗" . PHP_EOL;
echo "████╗ ████║██║██╔══██╗██╔═══██╗██║   ██║" . PHP_EOL;
echo "██╔████╔██║██║███████║██║   ██║██║   ██║" . PHP_EOL;
echo "██║╚██╔╝██║██║██╔══██║██║   ██║██║   ██║" . PHP_EOL;
echo "██║ ╚═╝ ██║██║██║  ██║╚██████╔╝╚██████╔╝" . PHP_EOL;
echo "╚═╝     ╚═╝╚═╝╚═╝  ╚═╝ ╚═════╝  ╚═════╝ " . PHP_EOL;
echo "Fini.";
?>