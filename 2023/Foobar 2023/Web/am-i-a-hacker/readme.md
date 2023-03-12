

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


