#!/usr/bin/env bash

# This command is designed under Debian 8
# And Chrome Headless instance is defined as command `google-chrome`

checkNotRoot() {
    if [ "`whoami`" == "root" ]; then
        echo "You are running this as ROOT, which is not safe. EXIT"
        exit 1
    fi
}

startChromeHeadless() {
    if [ ! -d /var/log/chrome_headless ]; then
        mkdir -p /var/log/chrome_headless;
        chmod -R 777 /var/log/chrome_headless;
    fi
    nohup google-chrome --headless --hide-scrollbars --remote-debugging-port=9222 --disable-gpu >> /var/log/chrome_headless/chrome_headless.$(date +\%Y\%m\%d).log 2>&1 &
}

stopChromeHeadless() {
    ps aux|grep chrome|grep 9222
    ps aux|grep chrome|grep 9222|awk '{print "kill ",$2}'|bash
}

statusOfChromeHeadless() {
    chrome_headless_pid=`ps aux|grep chrome|grep 9222|awk '{print $2}'`
    if [ "${chrome_headless_pid}" == '' ]; then
        echo Chrome Headless is not running!
        return 0;
    else
        echo "current running chrome headless pid: " ${chrome_headless_pid}
        return 1;
    fi
}

case $1 in
    start)
        checkNotRoot
        echo starting
        statusOfChromeHeadless
        if [ $? != 0 ]; then
            echo "Has been running, EXIT";
            exit 1;
        fi;
        startChromeHeadless
        statusOfChromeHeadless
        ;;
    stop)
        echo stopping
        statusOfChromeHeadless
        if [ $? == 0 ]; then
            echo "Need not stop, EXIT";
            exit 1
        fi;
        stopChromeHeadless
        statusOfChromeHeadless
        ;;
    status)
        statusOfChromeHeadless
        ;;
    *)
        echo "Possible command is start, stop and status only!"
        exit 1;
        ;;
esac
