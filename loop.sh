#!/bin/bash

while :
do
  ./run.sh &
  RUN_PID=$!
  sleep 20
#  kill $RUN_PID
  killall ffmpeg
done
