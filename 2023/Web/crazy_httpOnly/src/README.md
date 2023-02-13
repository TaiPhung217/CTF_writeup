# Đề bài:
A dinosaur theme park has just been opened in Vietnam. But was attacked by a group of cybercriminals. Can you help us determine what attack they used. Your reward will be the flag.

## Cung cấp:
web report: localhost:9000
bot admin: localhost:90001

## triển khai:
cung cấp cho CTFer web report để khai thác và bot admin để lấy cookie của admin.

docker command:
* web report:
- docker build -t httponly .
- docker run -d -p 9000:8080 httponly

* bot admin: 
- 