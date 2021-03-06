--TEST--
swoole_async: linux native aio readfile & writefile

--SKIPIF--
<?php require  __DIR__ . '/../include/skipif.inc'; ?>
--FILE--
<?php
require __DIR__ . '/../include/bootstrap.php';

swoole_async_readfile(TEST_IMAGE, function ($filename, $content)
{
    assert(md5_file($filename) == md5($content));
    $wFile = __DIR__ . '/tmp';
    $wData = str_repeat('A', 8192 * 128);
    swoole_async::writeFile($wFile, $wData, function ($file) use ($wData)
    {
        assert(md5_file($file) == md5($wData));
        echo "SUCCESS\n";
        swoole_event::defer(function() use ($file) {
            unlink($file);
        });
    });
    echo "SUCCESS\n";
});

swoole_event::wait();
?>
--EXPECT--
SUCCESS
SUCCESS
