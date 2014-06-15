<?php
/**
 * Created by PhpStorm.
 * User: deathnerd
 * Date: 6/15/14
 * Time: 6:37 PM
 */

$LOCAL_ROOT = "/home/www";
$LOCAL_REPO_NAME = "EKULMS";
$LOACL_REPO = "{$LOCAL_ROOT}/{$LOCAL_REPO_NAME}";
$REMOTE_REPO = "git.github.com:Deathnerd/EKULMS.git";
$DESIRED_BRANCH = "dev";

if(file_exists($LOCAL_REPO)) {
	shell_exec("rm -rf {$LOCAL_REPO}");
}

echo shell_exec("CD {$LOCAL_ROOT} && git clone {$REMOTE_REPO} {$LOCAL_REPO_NAME} && cd {$LOCAL_REPO} && git checkout {$DESIRED_BRANCH}");