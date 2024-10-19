#!/bin/bash

#ffmpeg -y -loglevel quiet -i $1/$2 -f image2 -vframes 1 -s 225x400 $2-$3.png
#ffmpeg -y -loglevel quiet -i $1/$2 -f image2 -vframes 1 -s 225x400 $2.png

#ffmpeg -y -i $1/$2 -f image2 -vframes 1 -s 112x200 ./snaps/$2.png
ffmpeg -y -loglevel quiet -i $1/$2 -f image2 -vframes 1 -s 112x200 ./snaps/$2.png &

#ffmpeg -y -loglevel quiet -i $1/$2 -f image2 -vframes 1 -s 112x200 ./snaps/$2.png
#FF_PID=$!
#sleep 5
#kill $FF_PID
#echo done
