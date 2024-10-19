#!/bin/bash
rtmpdump --live --timeout=9 -r $1 -a $2 -y $3  --stop 1 -o - | avconvert -i - -s 720x404 -vframes 1 $4
