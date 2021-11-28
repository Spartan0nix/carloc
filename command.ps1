foreach($arg in $args){
    if($arg -eq "--test") {
        $cmd = "php .\bin\phpunit --debug"
        Invoke-Expression $cmd
    }

    if($arg -eq "--run") {
        $cmd = '(symfony server:start) -and (yarn build --watch)'
        Invoke-Expression $cmd
    }
}