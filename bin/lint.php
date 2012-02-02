<?php
global $argc, $argv;
$bin_dir = dirname(__FILE__);
$app_dir = dirname($bin_dir);
$config_dir = $app_dir . "/conf";
$modules_dir = $app_dir . "/modules";
$libs_dir = $app_dir . "/libs";
$exit = 0;
$config = array(
    "file_path" => "",
    "config_dir" => $config_dir,
    "modules_dir" => $modules_dir,
    "libs_dir" => $libs_dir,
    "pre_load" => "Lint",
);

for($i = 1; $i < $argc; $i++){
    $arr = explode("=",$argv[$i]);
    if(count($arr) < 2){
        continue;
    }
    $k = trim(array_shift($arr));
    $v = trim(implode("=",$arr));
    $config[$k] = $v;
}
//ini_set('include_path',ini_get('include_path').':' . $libs_dir .':');
$preMods = explode(",",$config["pre_load"]);
$sysMods = array();
foreach($preMods as $sm){
    load_mod($sm,$sysMods,$libs_dir);
}

$lint_confs = parse_lint_confs($config["config_dir"]);
$type = get_file_type($config["file_path"]);
if(empty($type)){
    exit($exit);
}

$lint_conf = array();
if(isset($lint_confs[$type])){
    $lint_conf = $lint_confs[$type];
}
$mods = array();
$contents = file_get_contents($config["file_path"]);

foreach($lint_conf as $ruler){
    if(!isset($ruler["range"])){
        continue;
    }
    $range = trim($ruler["range"]);
    if($range != 'file'){
        continue;
    }
    $mod = $ruler["load"];
    $ret = load_mod($mod,$mods,$modules_dir);
    if(!$ret){
        continue;
    }
    $mods = $ret["mods"];
    $class = $ret["class"];
    if(!isset($ruler["show"])){
        $ruler["show"] = "NOTICE";
    }
    $show = $ruler["show"];
    $obj = new $class();

    $obj -> init($config["file_path"],"",$show,null,0,$libs_dir);
    $output = $obj -> check_contents($contents);
    if( $show != "NOTICE" && $exit === 0 && $output === false){
        $exit = 1;
    }

}


$handle = fopen($config["file_path"],"r");
$path = $config["file_path"];
$no = 0;
$pre_rs = '';
$post_rs = '';
while(!feof($handle)){
    $rs = fgets($handle);
    $offset = ftell($handle);
    if(!feof($handle)){
        $post_rs = fgets($handle);
        fseek($handle,$offset);
    }
    $no++;
    foreach($lint_conf as $ruler){
        if(!isset($ruler["range"])){
            $range = 'row';
        }else{
            $range = trim($ruler["range"]);
        }
        if($range != 'row'){
            continue;
        }

        $mod = $ruler["load"];
        $ret = load_mod($mod,$mods,$modules_dir);
        if(!$ret){
            continue;
        }
        $mods = $ret["mods"];
        $class = $ret["class"];
        if(!isset($ruler["show"])){
            $ruler["show"] = "NOTICE";
        }
        $show = $ruler["show"];
        $obj = new $class();

        $obj -> init($path,$no,$show,$handle,$offset,$libs_dir);
        $output = $obj -> check_row($rs,$pre_rs,$post_rs);
        if(!feof($handle)){
            $obj -> after_run();
        }
        if($show != "NOTICE" && $exit === 0 && $output === false){
            $exit = 1;
        }
    }
    $pre_rs = $rs;
}
fclose($handle);
exit($exit);

function load_mod($mod,$mods,$modules_dir){
    $mod_path = rtrim($modules_dir,"\\/"). '/' . $mod . ".php";
    if(!file_exists($mod_path)){
        return false;
    }
    if(!in_array($mod,$mods)){
        require_once($mod_path);
        $mods[] = $mod;
    }
    $ret = array();
    $ret["mods"] = $mods;
    $ret["class"] = str_replace("/","_",$mod);
    return $ret;
}

function parse_lint_confs($conf_dir){
    $conf_dir = rtrim($conf_dir, "\\/");
    $conf = array();
    if (is_dir($conf_dir)) {
        if ($dh = opendir($conf_dir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file == "." || $file == "..") continue;
                $path = $conf_dir . "/" . $file;
                $conf[$file] = parse_lint_conf($path);

            }
            closedir($dh);
        }
    }
    $lint_conf = array();
    foreach($conf as $ini_name => $ini){
        foreach($ini as $k => $block){
            if(!isset($block["type"])){
                continue;
            }
            $type = $block["type"];
            $block["block_name"] = $ini_name;
            $lint_conf[$type][] = $block;

        }
    }
    return $lint_conf;
}

function parse_lint_conf($path){
    $conf = parse_ini_file($path, true);
    return $conf;
}
function get_file_type($path){
    if(preg_match("#\.([a-zA-Z0-9][a-zA-Z0-9]*)$#msi", $path, $match)){
        return $match[1];
    }else{

        return false;
    }

}

?>
