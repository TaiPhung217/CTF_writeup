# Mô tả:
Juggling PHP in web

web: localhost:8004

## writeup
Theo mặc định các CTFers sẽ có thể thấy được code php trong trình duyệt

```php
<?php

if (isset($_GET['hash'])) {
    if ($_GET['hash'] === "10932435112") {
        die('Not so easy mate.');
    }

    $hash = sha1($_GET['hash']);
    $target = sha1(10932435112);
    if($hash == $target) {
        include('flag.php');
        print $flag;
    } else {
        print "csictf{loser}";
    }
} else {
    show_source(__FILE__);
}
?>
```

chall này kiểm tra xem hash đầu vào được cung cấp có bằng với hash sha1 của 10932435112 hay không?
Nếu nó bằng nhau thì in ra flag. 
Ở đây phần so sánh mình sử dụng == thay cho === nên sẽ không kiểm tra type của chuỗi đầu vào.
Vì vậy bất kỳ hàm băm nào khác sẽ khớp với hàm băm này, vì nó không kiểm tra type vì vậy chúng sẽ được coi là số.
Từ đó chúng ta có thể rút ngăn được wordlist để brute force.
những payload khớp là:
aaroZmOk
aaK1STfY
aaO8zKZF
...

/?hash=aaroZmOk

flag: flag{Jungling_in_php}