[CmdletBinding(PositionalBinding=$false)]
param (
    [string]$php = "",
    [switch]$Loop = $false,
    [string]$file = "",
    [string][Parameter(ValueFromRemainingArguments)]$extraFazeCoreArgs
)

if($php -ne ""){
    $binary = $php
}elseif(Test-Path "bin\php\php.exe"){
    $env:PHPRC = ""
    $binary = "bin\php\php.exe"
}else{
    $binary = "php"
}

if($file -eq ""){
    if(Test-Path "FazeCore.phar"){
        $file = "FazeCore.phar"
    }elseif(Test-Path "src\pocketmine\PocketMine.php"){
        $file = "src\pocketmine\PocketMine.php"
    }else{
        echo "The core is installed incorrectly!"
        pause
        exit 1
    }
}

function StartServer{
    $command = "powershell -NoProfile " + $binary + " " + $file + " " + $extraFazeCoreArgs
    iex $command
}

$loops = 0

StartServer

while($Loop){
    if($loops -ne 0){
        echo ("Restarted " + $loops + " times")
    }
    $loops++
    echo "To exit the loop, press CTRL + C now. Otherwise, wait 5 seconds while the server restarts."
    echo ""
    Start-Sleep 5
    StartServer
}
