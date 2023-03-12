

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

trong trang login ta có thể thấy một dòng chữ "Applying for role hacker ?"
Điều này cho thấy ta cần thay đổi giá trị role trong mã jwt thành hacker để có thể truy cập được dữ liệu khác trong máy tính.  Đến đây mình vẫn chưa tìm thấy chỗ UPload ở đây cả?
khả năng cao phải tạo được token mới , mới truy cập được chức năng upload.

Đến đây mình có thể xây dựng ý tưởng như sau:
    - Khôi phục mã RSA private key
    - Sử dụng khoá RSA sau khi đã khôi phục để có thể chỉnh sửa được trường role thành "hacker"

### Khôi phục mã RSA private key


