# How to use camera

## Step 1: Install Chrome Headless

For Debian 8, refer to https://qiita.com/sinri/items/f34ca832192119c0ef4d

## Step 2: Install Node.js

Neglect.

## Step 3: Use Camera.js

Let Chrome Headless listen to a port 9222.

```bash
nohup google-chrome --headless --hide-scrollbars --remote-debugging-port=9222 --disable-gpu >> /var/log/chrome_headless/$(date +\%Y\%m\%d).log 2>&1 &
```

Then run camera.js

```bash
node camera.js --fullPage true --viewportWidth 1366 --viewportHeight 768 --delay 5000 --outputFile ./1.png
```