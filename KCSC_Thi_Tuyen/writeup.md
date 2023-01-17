# BinggChillinggg
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