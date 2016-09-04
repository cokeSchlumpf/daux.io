<?php
  $data = json_decode(file_get_contents('php://input'), true);

  function sh($cmd) {
    echo '> '.$cmd."\n";
    $result = `$cmd`;
    echo $result;
    echo "\n";
    return $result;
  }

  $name = $data["repository"]["name"];
  $full_name = $data["repository"]["full_name"];

  $dest = "/var/www/html/docs";
  $archiveSrc = "https://codeload.github.com/$full_name/tar.gz/master";
  $tmp = "$dest/master.tar.gz";

  $check = sh("test -e $dest/$name.repo.test && echo ok || (test -e $dest/*.repo.test && echo no) || echo ok");
  echo $check;

  if (strcmp($check, "ok")) {
    sh("ls -al $dest");
    sh("curl $archiveSrc --output $tmp");
    sh("ls -al $dest");
    sh("tar xvf $tmp -C $dest && cp -r $dest/$name-master/* $dest/ && rm -rf $dest/$name-master $tmp");
    sh("ls -al $dest");
    sh("touch $dest/$name.repo.test");
  } else {
    echo "Invalud repository $full_name";
  }
?>
